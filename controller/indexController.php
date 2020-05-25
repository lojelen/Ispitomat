<?php

require_once "model/service.class.php";

class IndexController
{
	public function index()
	{
		$tus = new Service();

		// Ako korisnik nije ulogiran, preusmjerava ga se na login
		if (!isset($_SESSION["userID"])) {
			header("Location: ispitomat.php?rt=login");
			exit();
		} else {
			// $_SESSION["type"] je string koji može poprimiti dvije vrijednosti: Teacher/Student
			if (strcmp($_SESSION["type"], "Teacher") === 0) {
				// Nastavnika preusmjeravamo na početnu nastavničku stranicu
				header("Location: ispitomat.php?rt=teacher");
				exit();
			} else {
					// Studenta preusmjeravamo na početnu studentsku stranicu
					header("Location: ispitomat.php?rt=student");
					exit();
			}
		}
	}
};

?>
