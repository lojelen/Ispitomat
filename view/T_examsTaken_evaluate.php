<script type="text/javascript">

function passCheck(jmbag) {
  if (document.getElementById('pass_'+jmbag).checked) {
    document.getElementById('ifPassed_'+jmbag).style.display = 'block';
  }
  else document.getElementById('ifPassed_'+jmbag).style.display = 'none';
}

</script>

<?php require_once "view/_header.php";

  require_once "view/_navSubject.php"; ?>

  <div class="students">
  <?php
    if (empty($data)){
      echo "<div class='noStudents'>Nema studenata s neupisanim bodovima.</div>";
    }
    else {
  ?>
    <form id="saveForm" method="post" action="ispitomat.php?rt=teacher/save&examID=<?php echo $data[0]["exam"]->examID; ?>">
    <ul>
  <?php

  foreach($data as $st)
  {
    echo '<li><div class="studentScore"> '.$st["student"]->jmbag . '<hr>';
    echo '<input type="text" name="jmbag[]" value = "'.$st["student"]->jmbag . '" style="display:none">';
    echo 'Broj bodova: <input type="number" name="score_'.$st["student"]->jmbag.'" step="1" min="0" max="'.$st["exam"]->maxScore.'" value="0" required = "required" checked> <br>';
    echo 'Prošao/la: <input type="radio" id="pass_'.$st["student"]->jmbag.'" onclick="javascript:passCheck('.$st["student"]->jmbag.');" name="passed_'.$st["student"]->jmbag.'" value="DA" required = "required"> DA';
    echo '<input type="radio" id="fail_'.$st["student"]->jmbag.'" onclick="javascript:passCheck('.$st["student"]->jmbag.');" name="passed_'.$st["student"]->jmbag.'" value="NE"> NE <br>';

    if($st["subject"]->oralExam === false || ($st["subject"]->oralExam === true && strcmp($st["exam"]->type, "oral") === 0)) {
      echo '<div id="ifPassed_'.$st["student"]->jmbag.'" style="display:block"> Ocjena: <input type="number" name="grade_'.$st["student"]->jmbag.'" step="1" min="2" max="5" value="2"></div>';
    }
    echo '</div></li>';
  } ?>
    </ul>

    <button type="submit" name="saveButton" id="save_<?php echo $data[0]["exam"]->examID; ?>">Spremi bodove</button>
    </form>

  <?php } ?>
  </div>

  <?php require_once "view/_footer.php"; ?>
