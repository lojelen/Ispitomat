<script type="text/javascript">

function writtenCheck() {
    if (document.getElementById('written').checked) {
        document.getElementById('ifWritten').style.display = 'block';
    }
    else document.getElementById('ifWritten').style.display = 'none';

}

</script>

<?php require_once "view/_header.php";

 require_once "view/_navSubject.php"; ?>

 <form id='newExamForm' action="ispitomat.php?rt=teacher/addExamInput" method="POST">
 	<span class="col-3">Datum: </span><input type="date" name="date" required = "required"><br>
  <span class="col-3">Vrsta: </span>
  <input type="radio" id="written" onclick="javascript:writtenCheck();" name="type" value="written"> Pismeni
  <input type="radio" id="oral" onclick="javascript:writtenCheck();" name="type" value="oral"> Usmeni <br>
  <div id="ifWritten" style="display:none">
    <span class="col-3">Vrijeme: </span><input type="text" name="time" value="HH:MM" required = "required"><br>
    <span class="col-3">Trajanje: </span><input type="text" name="duration" required = "required"><br>
  </div>
 	<span class="col-3">Mjesto: </span><input type="text" name="location" required = "required"><br>
  <span class="col-3">Maksimalan broj bodova: </span><input type="number" name="max" step="1" min="0" value="100"> <br>
 	<span class="col-3">Opis: </span><textarea name="abstract" rows="3" cols="50">Što uključuje ispit...</textarea><br>
 	<span class="col-3"><button type="submit" class="addButton">Dodaj novi ispit!</button></span>
 </form>

 <?php require_once "view/_footer.php"; ?>
