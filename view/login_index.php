<?php require_once "view/_loginHeader.php";

if (isset($message))
	echo "<div id='errorMsg'>" . $message . " Pokušajte ponovno.</div><br>"; ?>

<form id="loginForm" method="post" action="ispitomat.php?rt=login/authentication">
	<span class="col-1">Korisnička oznaka: </span>
	<input class="col-2" type="text" name="userID">
	<button type="submit" name="login" id="login">Login</button>
</form>

<?php require_once "view/_footer.php"; ?>
