<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<title>Switch Control</title>
</head>

<body>
<header>
	<h1>Switch Control</h1>
<?php
	include "GPIO.php";
?>



</header>
<?php
//Set pin numbers to colors.
$Manual_Switch = 28;
$LUX = 33;
$Curtain_Switch = 35;
$servoDetecter = 34;

//Create GPIO objects for each color.
$M_S = new GPIO($Manual_Switch, "out");
$L_S = new GPIO($LUX, "out");
$C_S = new GPIO($Curtain_Switch, "out");
$S_D = new GPIO($servoDetecter, "in");

//If red/green/blue button is pressed turn them on/off.
if(isset($_POST['Man_S'])) { 
	$M_S->toggle();
	$L_S->write(0);
}
if(isset($_POST['Auto'])) { 
	$L_S->toggle();
}

if(isset($_POST['CurtS'])) { 
	$C_S->toggle();
}
?>
<!-- Instructions for how to interact with the LEDS -->
<p>
</p>

<center> <!-- centers everything on the page -->

<?php
sleep(1);
$a=$S_D->read();
$b=$L_S->read();
$c=$C_S->read();
?>
<!-- Create a whole form for each LED/button -->
<form method="post"> <!-- red form -->
	<!-- PHP here changes the color of the button depending on whether the LED is ON or OFF -->
	<input type="submit" class="button_colors" id="Man" name="Man_S" value="<?php 
                           if($a==0)
                            echo "OFF";
                            else
                            echo "ON";
                                ?>"
	 style="background-color:<?php echo (!$a?"red":"green"); ?>">
	<input type="submit" class="button_colors" id="Lux" name="Auto" value="<?php 
                           if($b==1)
                            echo "Auto";
                            else
                            echo "Manual";
                                ?>"
	 style="background-color:<?php echo (!$L_S->read()?"grey":"green"); ?>">
	<input type="submit" class="button_colors" id="Curt" name="CurtS" value="<?php 
                           if($c==1)
                            echo "Curtain Opened";
                            else
                            echo "Curtain Closed";
                                ?>"
	 style="background-color:<?php echo ($C_S->read()?"cyan":"grey"); ?>">
</form>
</center> <!-- END center -->

</body>
</html>
