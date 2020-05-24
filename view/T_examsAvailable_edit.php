<?php require_once "view/_header.php";

  require_once "view/_navSubject.php"; ?>

  <form id='editExamForm' action="ispitomat.php?rt=teacher/editExamInput" method="POST">
    <div id="edit" style="display:none">
      <input type="text" name="id" value ="<?php echo $exam->id; ?>"><br>
    </div>
  	<span class="col-3">Datum: <?php echo date("d.m.Y", strtotime($exam->date)); ?></span><br>
   <?php
    if (strcmp($exam->type, "written") === 0){ ?>
      <span class="col-3">Vrsta: pismeni </span><br>
      <span class="col-3">Vrijeme: <?php echo $exam->time; ?></span><br>
      <span class="col-3">Trajanje: <?php echo $exam->duration; ?> min</span><br>
   <?php }
   else ?> <span class="col-3">Vrsta: usmeni </span><br>
  	<span class="col-3">Mjesto: </span><input type="text" name="location" value="<?php echo $exam->location; ?>"><br>
   <span class="col-3">Maksimalan broj bodova: </span><input type="number" name="max" step="1" min="0" value="<?php echo $exam->maxScore; ?>"> <br>
  	<span class="col-3"><button type="submit" class="addButton">Uredi!</button></span>
  </form>

  <?php require_once "view/_footer.php"; ?>
