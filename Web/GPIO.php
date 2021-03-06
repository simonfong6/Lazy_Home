<?php
//NOTE: Simon Fong added toggle() function to this and changed the constructor, so that it will set the GPIO pin to whatever its previous value was if the direction is out.
//Ex: Red LED was on and the constructor is called for the Red LED, it will keep it on
//Before it would always default to off when the contructor was called.
	class GPIO {
		/* PRIVATE vars */
		var $_gpio_pin = "null";
		var $_gpio_direction = "null";
		/* CONTRUCTOR */
		public function __construct($_gpio_pin, $_gpio_direction) {
			$this->_set_gpio_pin($_gpio_pin);
			$this->_set_gpio_direction($_gpio_direction);	
		}
		/* PRIVATE METHODS */
		private function _set_gpio_pin($_gpio_pin) {
			$this->_gpio_pin = $_gpio_pin;
		}
		private function _set_gpio_direction($_gpio_direction) {
			if (strtolower($_gpio_direction) == "in" || strtolower($_gpio_direction) == "input")
				$_gpio_direction = "in";
			else if (strtolower($_gpio_direction) == "out" || strtolower($_gpio_direction) == "output") {
				$_gpio_direction = "out";
				$status_current = $this->read(); //reads current state of pin and stores it in $status_current
			}
			$this->_gpio_direction = $_gpio_direction;
			exec("gpio export ". $this->get_gpio_pin() ." ". $this->get_gpio_direction());
			if($this->get_gpio_direction() == "out") //if direction is out
				$this->write($status_current);	 //write the current status to pin
		}
		
		/* PUBLIC METHODS */
		public function get_gpio_pin() {
			return $this->_gpio_pin;
		}
		public function get_gpio_direction() {
			return $this->_gpio_direction;
		}
		public function write($_state) {
			//echo $_state;
			if ($_state== 1)
				shell_exec( "sudo bash ON_".$this->get_gpio_pin().".sh");
			else if($_state== 0) 
			shell_exec( "sudo bash OFF_".$this->get_gpio_pin().".sh");
		}
		public function read() {
			
			return exec( "sudo cat /sys/class/gpio/gpio".$this->get_gpio_pin()."/value", $status);
			// return $status;
		}
		public function read_state() {
			return $this->_state;
		}
		public function toggle() { //toggles between on and off state
			if($this->read()) {
				$this->write(0);
			}
			else {
				$this->write(1);
			}
		}
	}
?>
