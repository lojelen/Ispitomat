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
   <?php
   if($subject->oralExam === true){ ?>
     <span class="col-3">Vrsta: </span>
     <input type="radio" id="written" onclick="javascript:writtenCheck();" name="type" value="written" checked> Pismeni
     <input type="radio" id="oral" onclick="javascript:writtenCheck();" name="type" value="oral"> Usmeni <br>
   <?php } else { ?>
     <span class="col-3">Vrsta: </span>
     <input type="radio" id="written" onclick="javascript:writtenCheck();" name="type" value="written" checked> Pismeni
 <?php } ?>
 <div id="ifWritten" style="display:block">
   <span class="col-3">Vrijeme: </span><input type="text" name="time" value="HH:MM"><br>
   <span class="col-3">Trajanje: </span><input type="number" name="duration" min="0" max="240"> min<br>
 </div>
   <span class="col-3">Mjesto: </span><input type="text" name="location" required = "required"><br>
   <span class="col-3">Maksimalan broj bodova: </span><input type="number" name="max" step="1" min="0" value="100"> <br>
   <button type="submit" class="addButton">Dodaj novi ispit!</button>
 </form>

 <?php require_once "view/_footer.php"; ?>
