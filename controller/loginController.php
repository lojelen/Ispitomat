<?php

require_once "model/service.class.php";

class LoginController
{
	public function index()
	{
		require_once "view/login_index.php";
	}

	public function authentication()
	// Provjera postoji li u bazi korisnik s danom korisničkom oznakom
	{
		$tus = new Service();

		if (isset($_POST["userID"])) {
			$user = $tus->getUserByUserID($_POST["userID"]);

			if ($user === null)
				$message = "Korisnik s tim imenom ne postoji.";
			else {
				$_SESSION["userID"] = $user->userID;
				// User je nadklasa dviju podklasa: Teacher i Student
				$_SESSION["type"] = get_class($user);
				if (strcmp($_SESSION["type"], "Teacher") === 0) {
					header("Location: ispitomat.php?rt=teacher");
					exit();
				} else {
					$_SESSION["JMBAG"] = $user->jmbag;
					header("Location: ispitomat.php?rt=student");
					exit();
				}
			}
		}

		// U slučaju neuspješnog ulogiravanja, korisnik ponovno vidi formu za login, uz poruku o grešci koja se dogodila prilikom prethodnog ulogiravanja
		if (!isset($message))
			$message = "Neuspješan login.";
		require_once "view/login_index.php";
	}

	public function logout() // Funkcija čija je svrha odlogirati korisnika
	{
		session_unset();
		session_destroy();
		header("Location: ispitomat.php");
	}
};

?>
