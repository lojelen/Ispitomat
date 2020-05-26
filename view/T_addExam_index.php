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
   <span class="col-3"><b>Datum:</b></span><span class="col-4"><input type="date" name="date" required = "required"></span><br>
   <?php
   if($subject->oralExam === true){ ?>
     <span class="col-3"><b>Vrsta:</b></span><span class="col-4">
     <input type="radio" id="written" onclick="javascript:writtenCheck();" name="type" value="written" checked> Pismeni
     <input type="radio" id="oral" onclick="javascript:writtenCheck();" name="type" value="oral"> Usmeni</span><br>
   <?php } else { ?>
     <span class="col-3"><b>Vrsta:</b></span><span class="col-4">
     <input type="radio" id="written" onclick="javascript:writtenCheck();" name="type" value="written" checked> Pismeni</span><br>
 <?php } ?>
 <div id="ifWritten" style="display:block">
   <span class="col-3"><b>Vrijeme:</b></span><span class="col-4"><input type="text" name="time" value="HH:MM"></span><br>
   <span class="col-3"><b>Trajanje:</b></span><span class="col-4"><input type="number" name="duration" min="0" max="240"> min</span><br>
 </div>
   <span class="col-3"><b>Mjesto:</b></span><span class="col-4"><input type="text" name="location" required = "required"></span><br>
   <span class="col-3"><b>Maksimalan broj bodova:</b></span><span class="col-4"><input type="number" name="max" step="1" min="0" value="100"></span><br>
   <button type="submit" class="addButton">Dodaj novi ispit!</button>
 </form>

 <?php require_once "view/_footer.php"; ?>
