<?php

require_once "model/service.class.php";

class IndexController
{
	public function index()
	{
		$tus = new Service();

		// Klasa User, UserID = korisnička oznaka, User može biti nastavnik ili student
		// $korisnik = $tus->getUserByUserID($_SESSION["userID"]);
		if (!isset($_SESSION["userID"])) {
			header("Location: ispitomat.php?rt=login");
			exit();
		} else {
			// $_SESSION["type"] je string koji može poprimiti dvije vrijednosti: nastavnik/student
			if (strcmp($_SESSION["type"], "Teacher") === 0) {
				// teacher = glavni izbornik za nastavnika
				header("Location: ispitomat.php?rt=teacher");
				exit();
			} else {
					// student = glavni izbornik za studenta
					header("Location: ispitomat.php?rt=student");
					exit();
			}
		}
	}
};

?>
