<?php
  include_once "../controller/controller.php";
  error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 'off');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../styles/style.css">
    <script src="https://kit.fontawesome.com/e993ae77d4.js" crossorigin="anonymous"></script>
    <title>Monitoring</title>
</head>
<body>
<header class="header">
    <a href="" class="logo">Monitoring</a>
  <input class="menu-btn" type="checkbox" id="menu-btn" />
  <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
  <ul class="menu">
  <li><a href="http://monitoringprojectsyllabustest.com/mvc/view/index.php">На головну</a></li>
  <li><a href="http://monitoringprojectsyllabustest.com/mvc/view/index.php">Моніторинг методичного забезпечення</a></li>
    <li><a href="http://monitoringprojectsyllabustest.com/mvc/view/syllabus_creating.php">Формування силабусів</a></li>
  </ul>
</header>
<main class="container">

  <div class="filters">
  <div class="filters__inner">
  <div class="filters__inner__speciality_names">
      <span>Спеціальність</span>
      <select name='specialities_name[]' class="select-box">
      <option value="">---</option>

        <option value="">---</option>
      <?php
      $specialitiesArr = array_unique(array_column($info, 'specialities_name'));

      foreach ($specialitiesArr as $el) {
          echo "<option value='$el'>$el</option>";
      }
      ?>
    </select>
    </div> 
    <div class="filters__inner__group_names">
      <span>Групи</span>
      <select name='group_name[]'>
      <option value="">---</option>

      <?php
      $groupsArr = array_unique(array_column($info, 'group_name'));

      foreach ($groupsArr as $el) {
          echo "<option value='$el'>$el</option>";
      }
      ?>
      </select>
    </div>
    <div class="filters__inner__courses">
        <span>Курс</span>

        <select name='course_year[]'>
        <option value="">---</option>

        <?php
        $coursesArr = array_unique(array_column($info, 'course_year'));

        foreach ($coursesArr as $el) {
            echo "<option value='$el'>$el</option>";
        }
        ?>
        </select>
      </div>
      <div class="filters__inner__education_lvl">
      <span>Рівень освіти</span>

      <select name='education_level_name[]'>
      <option value="">---</option>

      <?php
      $educationLvlsArr = array_unique(array_column($info, 'education_level_name'));

      foreach ($educationLvlsArr as $el) {
          echo "<option value='$el'>$el</option>";
      }
      ?>
      </select>
    </div>
      <div class="filters__inner__disciplines_names">
      <span>Дисципліни</span>

      <select name='discipline_name[]'>
      <option value="">---</option>

      <?php
      $disciplinesArr = array_unique(array_column($info, 'discipline_name'));

      foreach ($disciplinesArr as $el) {
          echo "<option value='$el'>$el</option>";
      }
      ?>
      </select>
    </div>
    <div class="filters__inner__lector_names">
      <span>Лектори</span>

      <select name='lector_name[]'>
      <option value="">---</option>

      <?php
      $lectorsArr = array_map(function($row) {
        return $row['lector_name'] . ' ' . $row['lector_surname'] . ' ' . $row['lector_patronymic'];
      }, $info);
    
      $uniqueLectorsArr = array_unique($lectorsArr);
    
      foreach ($uniqueLectorsArr as $el) {
          echo "<option value='$el'>$el</option>";
      }
      ?>
      </select>
    </div>
    <div class="filters__inner__practitioner_names">
      <span>Практики</span>

      <select name='practitioner_name[]'>
      <option value="">---</option>
      <?php
      $practitionersArr = array_map(function($row) {
        return $row['practitioner_name'] . ' ' . $row['practitioner_surname'] . ' ' . $row['practitioner_patronymic'];
      }, $info);
    
      $uniquePractitionersArr = array_unique($practitionersArr);
    
      foreach ($uniquePractitionersArr as $el) {
          echo "<option value='$el'>$el</option>";
      }
      ?>
      </select>
    </div>
    
  </div>
</div>

  <div class="table-wrapper" id="table-wrapper">
    <table class="fl-table" id="fl-table">
        <thead>
        <tr>
            <th>Курс</th>
            <th>Рівень освіти</th>
            <th>Група</th>
            <th>Дисципліна</th>
            <th>Викладач/ <br>Лектор/ Практик</th>
            <th>Силабус</th>
            <th>Навчальний посібник</th>
            <th>Навчальний підручник</th>
            <th>Конспект лекцій<br>(Електронний ресурс)</th>
        </tr>
        </thead>
        <tbody class="list">
        <?php foreach ($info as $el): ?>
        <tr class="item">
            <td><?=$el['course_year'];
        ?></td>
        <td><?=$el['education_level_name'];
        ?></td>
        <td><?=$el['group_name'];
        ?></td>
            <td><?= $el['discipline_name'] ?></td>
            <td><?php
          echo "Лектор: " . $el['lector_name'] . " " . $el['lector_surname'] . " " . $el['lector_patronymic']; 
          echo "<br>Практик: " . $el['practitioner_name'] . " " . $el['practitioner_surname'] . " " . $el['practitioner_patronymic']; 
          ?></td>
          <?php 
          $document_type_array = explode(',', $el['document_types']);
          $document_name_array = explode(',', $el['document_names']);
          $document_file_array = explode(',', $el['document_files']);
          ?>
            <td> <?php 
            for ($i = 0; $i < count($document_type_array); $i++) {
              if($document_type_array[$i] == 'Силабус') {
                echo '<a style="text-decoration: underline;color: #4FC3A1" href="' . $document_file_array[$i] . '">' . $document_name_array[$i] . '</a><br>';
              }
            }?></td>
            <td><?php
          for ($i = 0; $i < count($document_type_array); $i++) {
            if($document_type_array[$i] == 'Посібник') {
              echo '<a style="text-decoration: underline;color: #4FC3A1" href="' . $document_file_array[$i] . '">' . $document_name_array[$i] . '</a><br>';
            }
          }
          ?></td>
          <td><?php
          for ($i = 0; $i < count($document_type_array); $i++) {
            if($document_type_array[$i] == 'Підручник') {
              echo '<a style="text-decoration: underline;color: #4FC3A1" href="' . $document_file_array[$i] . '">' . $document_name_array[$i] . '</a><br>';
            }
          }
          ?></td>
          <td>
            <?php
              $linksArray = explode(",", $el['document_links']);
              $modifiedLinksArray = array_map(function($link) {
                  return str_replace('/', '/&#x200b;', $link);
              }, $linksArray);

              for ($i = 0; $i < count($linksArray); $i++){
                  echo "<p><a style=\"text-decoration: underline;color: #4FC3A1\" href=\"" . $linksArray[$i] . "\">" . $modifiedLinksArray[$i] . "</a></p>";
              }
            ?>
        </td>
        <?php endforeach; ?>
        </tr>
        <tbody>
    </table>
</div>
    <div class="pagination">
      <div class="some_utils">
      
      </div>
      <ul class="listPage">
      </ul>
    <div class="search_div">
      <div class="form__group field">
        <input type="input" class="form__field" placeholder="Введіть текст для пошуку" name="search_text[]" id='name' />
        <label for="name" class="form__label">Текст</label>
      </div>
      <button onclick="applyFilters()">Пошук</button>
    </div>
    </div>
</div>
</main>

<footer class="footer">
  <div class="header_container"><h1>Запити</h1></div>
  <ul>
    <li><a href="#" id="entriesToBeFilled">Показати записи що потрібно заповнити <i class="fa-solid fa-magnifying-glass-arrow-right"></i></a></li>
    <li><a href="#" id="fullFilledEntries">Показати повністю готові записи <i class="fa-solid fa-magnifying-glass-arrow-right"></i></a></li>
  </ul>
  <form method="post" class="button_footer_container" action="../controller/generate_report_pdf.php<?php echo formatQueryParams(); ?>" >
    <button class="button_footer" role="button" name="generate_report">
      <span class="text">Завантажити звіт</span>
    </button>
  </form>
  <?php
function formatQueryParams() {
  $queryParams = $_SERVER['QUERY_STRING'];
  
  if (!empty($queryParams)) {
    $formattedParams = '?' . $queryParams;
    
    $formattedParams = str_replace('&', '&amp;', $formattedParams);
    
    return $formattedParams;
  } else {
    return '';
  }
}
?>
</footer>
<script src="/../js/script.js"></script>
</body>
</html>