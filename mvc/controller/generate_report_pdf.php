<?php
include "../model/model_create_report.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['generate_report'])) {
    generateReportPDF();
  }
}
?>
