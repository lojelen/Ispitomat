<?php require_once "view/_header.php";

 require_once "view/_navSubject.php"; ?>

 <form id='newExamForm' action="ispitomat.php?rt=teacher/addExamInput" method="POST">
 	<span class="col-3">Šifra predmeta: </span><input type="text" name="subjectID" class="col-70"><br>
 	<span class="col-3">Semestar: </span><input type="text" name="semestar" class="col-70"><br>
 	<span class="col-3">Datum: </span><input type="date" name="date" class="col-70"><br>
 	<span class="col-3">Mjesto: </span><input type="text" name="place" class="col-70"><br>
 	<span class="col-3">Opis: </span><textarea name="abstract" rows="5" cols="50" class="col-70">Što uključuje ispit...</textarea><br>
 	<span class="col-3"><button type="submit">Dodaj novi ispit!</button></span>
 </form>

 <?php require_once "view/_footer.php"; ?>
