<?php
class Inputs {
	private $username, $password;
	function __construct($uname,$passwd){
		$this->username=$uname;
		$this->password=$passwd;
	}
	function validUserName($uname){
		if (empty($uname)){
			$error="Username is required!";
		}
		return $error;
	}
	function validPassword($passwd){
		if (empty($passwd)){
			$error="Password is required!";
		}
		return $error;
	}



}









?>