#include <SparkFunTSL2561.h>

#include <Servo.h>
#include <SparkfunTSL2561.h>
#include <Wire.h>
Servo onServo;
Servo offServo;

//curtain puller
int directionPin1 = 3;
int directionPin2 = 4;
int PWMpin = 11;
int readPin = 2;
int curtainState = 0;
int OldPin=-1;
//
int flick = 0;
int dragon_pin = 0;
SFE_TSL2561 light;
boolean gain;
unsigned int ms;
boolean wasBright = false;
int autoSensorPin = 1;
boolean turningDark;
int DragonPinDataOld = -1;
int loops = 0;
int outputpin=2;
int autoTurnedOn = 0;

void setup() {

//curtain puller
pinMode(directionPin1, OUTPUT);
pinMode(directionPin2, OUTPUT);
pinMode(PWMpin, OUTPUT);
pinMode(readPin, INPUT);
//
onServo.attach(9);
offServo.attach(10);
Serial.begin(9600);
pinMode(outputpin,OUTPUT);
digitalWrite(outputpin,LOW);
light.begin();
unsigned char ID;
if(!(light.getID(ID))){
  byte error = light.getError();
}
gain = 0;
unsigned char time = 2;
light.setTiming(gain,time,ms);
light.setPowerUp();
}

void loop() {

int DragonPinAnalog = analogRead(dragon_pin);
int DragonPinData;
if(DragonPinAnalog < 250)
  DragonPinData = 0;
else
  DragonPinData = 1;

if(loop != 0){
  if(DragonPinData != DragonPinDataOld)
    flick = 1;
  else
    flick = 0;
}
Serial.print("DraonPinData = ");
Serial.println(DragonPinData);


    
unsigned int data0, data1;
light.getData(data0,data1);
double lux;
light.getLux(gain,ms,data0,data1,lux);

Serial.print("Lux = ");
Serial.println(lux);

boolean isBright;

if(lux < 60)
  isBright = false;
else
  isBright = true;

Serial.print("isBright = ");
Serial.println(isBright);

if((!isBright) && (wasBright)){
  turningDark = true;
}
else{
  turningDark = false;
}
wasBright = isBright;

int autoSensor = analogRead(autoSensorPin);

Serial.print(turningDark);
Serial.print("  ");
Serial.println(autoSensor);
Serial.print("flick:");
Serial.println(flick);
if(((DragonPinData == 0)&&(flick == 1) )|| (turningDark && autoSensor)){ 
      
        digitalWrite(outputpin,HIGH);
        Serial.println("This is on?");
        onServo.write(180); //flicks the switch ON
        delay(500);
        onServo.write(0);   //returns servo to initial position

       // if(!autoSensor)
        flick = 1;             //prevents the servomotor from rotating infinitely
        //turningDark = false;
        if(turningDark && autoSensor)
          flick = 0;
         else
          autoTurnedOn = 0;
      
    }

    else if(DragonPinData != 0){
      if(flick == 1){
        digitalWrite(outputpin,LOW);
        offServo.write(0);
        delay(500);
        
        offServo.write(180);
        flick = 0;
       }
   }

DragonPinDataOld = DragonPinData;
loops++;


Serial.print("oldprint:");
Serial.print(OldPin);
int readPinCurr;
if(analogRead(readPin)>250)
readPinCurr=1;
else
readPinCurr=0;
Serial.print("readpin :");
Serial.println(readPinCurr);

if (readPinCurr && (OldPin!=readPinCurr)){
  Serial.print("safvdvzdvdfvdfvdrfvd");
  digitalWrite(directionPin1, HIGH);
  digitalWrite(directionPin2, LOW);
  analogWrite(PWMpin, 100);
  delay(400);
  curtainState =! curtainState;
  OldPin=readPinCurr;
  //analogWrite(PWMpin, 0);
  digitalWrite(directionPin1, LOW);
  digitalWrite(directionPin2, LOW);

}
else if(OldPin!=readPinCurr && !readPinCurr){
  digitalWrite(directionPin1, LOW);
  digitalWrite(directionPin2, HIGH);
  analogWrite(PWMpin, 100);
  delay(400);
  curtainState != curtainState;
  OldPin=readPinCurr;
  //analogWrite(PWMpin, 0);
  digitalWrite(directionPin1, LOW);
  digitalWrite(directionPin2, LOW);
}
  
delay(ms);
}

    


  

