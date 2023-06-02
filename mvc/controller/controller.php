<?php
  include "../model/model.php";
  function outputRecords()
{
  global $info;

  global $filterData;
  global $queryData;

  if (isset($_GET['query'])) {
    $queryData['query'] = $_GET['query'];
  }

  if (isset($_GET['group_name'])) {
      $filterData['group_name'] = $_GET['group_name'];
  }
  if (isset($_GET['specialities_name'])) {
    $filterData['specialities_name'] = $_GET['specialities_name'];
  }
  if (isset($_GET['discipline_name'])) {
    $filterData['discipline_name'] = $_GET['discipline_name'];
  }
  if (isset($_GET['course_year'])) {
    $filterData['course_year'] = $_GET['course_year'];
  }
  if (isset($_GET['education_level_name'])) {
    $filterData['education_level_name'] = $_GET['education_level_name'];
  }
  if (isset($_GET['lector_name'])) {
    $filterData['lector_name'] = $_GET['lector_name'];
  }
  if (isset($_GET['practitioner_name'])) {
    $filterData['practitioner_name'] = $_GET['practitioner_name'];
  }
  if (isset($_GET['search_text'])) {
    $filterData['search_text'] = $_GET['search_text'];
  }
  $data = getRecords($filterData, $queryData);

  $info = $data['info'];
}


  outputRecords();
?>
