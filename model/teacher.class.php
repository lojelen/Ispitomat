<?php

require_once "model/user.class.php";

class Teacher extends User
{
	protected $oib, $title;

	function __construct($userID, $name, $surname, $gender, $dateOfBirth, $address, $oib, $title)
	{
		parent::__construct($userID, $name, $surname, $gender, $dateOfBirth, $address);
		$this->OIB = $oib;
		$this->title = $title;
	}
}

?>
