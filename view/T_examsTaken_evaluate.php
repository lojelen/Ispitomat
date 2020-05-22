<?php require_once "view/_header.php";

 require_once "view/_navSubject.php"; ?>

 <div id="students">
   <form id="saveForm" method="post" action="ispitomat.php?rt=teacher/save&examID=<?php echo $st["exam"]->id; ?>">
   <ul>
 <?php
 foreach($data as $st)
 {
   echo '<li> <div class="studentScore"> '.$st["student"]->jmbag . '<hr>';
   echo 'Broj bodova: <input type="number" name="score_'.$st["student"]->jmbag.'" step="1" min="0" max="'.$st["exam"]->maxScore.'" value="0"> <br>';
   echo 'Ocjena: <input type="number" name="grade_'.$st["student"]->jmbag.'" step="1" min="1" max="5" value="0">   </div></li>';
   ?>
 <?php
 } ?>
   </ul>

   <button type="submit" name="saveButton" id="save_<?php echo $st["exam"]->id; ?>">Spremi bodove</button>
   </form>

</div>



 <?php require_once "view/_footer.php"; ?>
