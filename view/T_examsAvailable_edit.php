<?php require_once "view/_header.php";

  require_once "view/_navSubject.php"; ?>

  <form id='editExamForm' action="ispitomat.php?rt=teacher/editExamInput" method="POST">
    <div id="edit" style="display:none">
      <input type="text" name="id" value ="<?php echo $exam->examID; ?>"><br>
    </div>
  	<span class="col-3"><b>Datum:</b></span><span class="col-4"><?php echo date("d.m.Y", strtotime($exam->date)); ?></span><br>
   <?php
    if (strcmp($exam->type, "written") === 0){ ?>
      <span class="col-3"><b>Vrsta:</b></span><span class="col-4">pismeni</span><br>
      <span class="col-3"><b>Vrijeme:</b></span><span class="col-4"><?php echo $exam->time; ?></span><br>
      <span class="col-3"><b>Trajanje:</b></span><span class="col-4"><?php echo $exam->duration; ?> min</span><br>
   <?php }
   else { ?> <span class="col-3"><b>Vrsta:</b></span><span class="col-4">usmeni</span><br> <?php } ?>
  	<span class="col-3"><b>Mjesto:</b></span><span class="col-4"><input type="text" name="location" value="<?php echo $exam->location; ?>"></span><br>
   <span class="col-3"><b>Maksimalan broj bodova:</b></span><span class="col-4"><input type="number" name="max" step="1" min="0" value="<?php echo $exam->maxScore; ?>"></span><br>
  	<span class="col-4"><button type="submit" class="addButton">Uredi</button></span>
  </form>

  <?php require_once "view/_footer.php"; ?>
