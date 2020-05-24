<?php require_once "view/_header.php";

 require_once "view/_navSubject.php"; ?>

 <div id="exams">

 <?php

 foreach($exams as $exam)
 {
   echo "<div class='examAvailable'>";
   $examInfo = "<ul><li><b>Datum ispita:</b> " . date("d.m.Y", strtotime($exam["exam"]->date)) .
               "</li><li><b>Vrsta ispita:</b> " . (strcmp($exam["exam"]->type, "written") === 0 ? "pismeni" : "usmeni") . "</li>";
   if (strcmp($exam["exam"]->type, "written") === 0) {
     $examInfo = $examInfo . "<li><b>Vrijeme ispita:</b> " . $exam["exam"]->time . "</li><li><b>Trajanje ispita: </b>" .
                 $exam["exam"]->duration . " min</li>";
   }
   $examInfo = $examInfo . "<li><b>Lokacija ispita:</b> " . $exam["exam"]->location . "</li>";
   $examInfo = $examInfo . "<li><b>Maksimalan broj bodova:</b> " . $exam["exam"]->maxScore . "</li></ul>";
   echo $examInfo; ?>

   <form id="evaluateForm" method="post" action="ispitomat.php?rt=teacher/edit&examID=<?php echo $exam["exam"]->id; ?>">
     <button type="submit" class="editButton" id="edit_<?php echo $exam["exam"]->id; ?>">Uredi</button>
   </form>
 </div>
 <?php } ?>

 <div/>

 <?php require_once "view/_footer.php"; ?>
