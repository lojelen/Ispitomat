<?php

require_once "db.class.php";
require_once "user.class.php";
require_once "teacher.class.php";
require_once "student.class.php";

class Service
{
	function getUserByUserID($userID)
	{
		// implementacija dohvaćanja korisnika s danim userIDjem iz baze
		// funkcija vraća odgovarajući Teacher ili Student objekt
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (user:User {userID:'" . $userID . "'}) RETURN user, labels(user) AS labels";
			$result = $client->run($query)->getRecords()[0];
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

		if ($result === null) // provjeriti vraća li null ako ne postoji
			return null;
		else {
			$user = $result->value("user");
			$labels = $result->value("labels");
			foreach ($labels as $label) {
				if (strcmp($label, "Teacher") === 0)
					return new Teacher($user->get("userID"), $user->get("name"), $user->get("surname"),
														 $user->get("gender"), $user->get("dateOfBirth"), $user->get("address"),
														 $user->get("oib"), $user->get("title"));
				else if (strcmp($label, "Student") === 0)
					return new Student($user->get("userID"), $user->get("name"), $user->get("surname"),
													 	 $user->get("gender"), $user->get("dateOfBirth"), $user->get("address"),
													 	 $user->get("jmbag"));
			}
		}
	}
};

?>
