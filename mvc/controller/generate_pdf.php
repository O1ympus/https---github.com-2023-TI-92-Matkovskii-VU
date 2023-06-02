<?php
  include "../model/model.php";

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      generatePDF($_POST);
  } 
?>
