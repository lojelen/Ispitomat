<?php require_once "view/_header.php";

 if (strcmp($errorMsg, "OK") === 0) {
 	echo "<div id='errorMsg'>Akcija je uspjela!<br>";
 	echo "<a href='ispitomat.php?rt=teacher'>Odvedi me natrag!</a></div>";
 }

 else if (strcmp($errorMsg, "NOT_SET") === 0)
 	echo "<div id='errorMsg'>Akcija nije uspjela, niste unijeli tražene podatke!</div><br>";

 else
 	echo "<div id='errorMsg'>Akcija nije uspjela. Greška: " . $errorMsg . "</div><br>";

 require_once "view/_footer.php"; ?>
