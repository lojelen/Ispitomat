<?php require_once "view/_header.php";

   require_once "view/_navSubject.php"; ?>

   <div class="students">
   <?php
   if (empty($data)) {
     echo "<div class='noStudents'>Nema upisanih bodova.</div>";
   }
   else {
     echo "<ul>";
     foreach($data as $st)
     {
       echo '<li><div class="studentScore">' . $st["student"]->jmbag . '<hr>';
       echo 'Broj bodova: '.$st["score"] . '/' . $st["maxScore"] . '<br>';
       if($st["passed"]){
         echo 'Prošao/la. ';
         if(!is_null($st["grade"])) {
           echo 'Ocjena: '.$st["grade"].' </div></li>';
         }
       }
       else echo 'Nije prošao/la. </div></li>';
     }
   }
  ?>
  </ul>
  </div>
