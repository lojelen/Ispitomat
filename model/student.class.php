<?php

require_once "model/user.class.php";

class Student extends User
{
	protected $jmbag;

	function __construct($userID, $name, $surname, $gender, $dateOfBirth, $address, $jmbag)
	{
		parent::__construct($userID, $name, $surname, $gender, $dateOfBirth, $address);
		$this->jmbag = $jmbag;
	}
}

?>
