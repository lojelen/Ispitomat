<?php require_once "view/_header.php";

 require_once "view/_navSubject.php"; ?>

 <div id="exams">

 <?php

 foreach($exams as $exam)
 {
   $examInfo = "<div class='examAvailable'><ul><li><b>Šifra predmeta:</b> " . $exam["subjectID"] . "</li><li><b>Naziv predmeta:</b> " .
                $exam["subjectName"] . "</li><li><b>Semestar:</b> ";
   if (strcmp($exam["semester"], "Z") === 0)
     $examInfo = $examInfo . "zimski";
   else
     $examInfo = $examInfo . "ljetni";
   $examInfo = $examInfo . "</li><li><b>ID ispita:</b> " . $exam["examID"] . "</li><li><b>Datum ispita:</b> " .
                date("d.m.Y", strtotime($exam["date"]));
   echo $examInfo; ?>

 </li></ul><br><button type="submit" class="editButton" id="edit_<?php echo $exam["examID"]; ?>">Uredi</button>
 </div>
 <?php } ?>

 <div/>

 <?php require_once "view/_footer.php"; ?>
