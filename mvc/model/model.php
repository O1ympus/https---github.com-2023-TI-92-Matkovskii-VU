<?php
    include "connect.php";
    require_once '../../vendor/autoload.php';
    require_once '../../PHPSimpleHTMLDOMParser/simple_html_dom.php';
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 'off');
    function getRecords($filterData, $queryData)
{
    global $db;
    $countQuery = "SELECT COUNT(*) AS count FROM `disciplines`";
    $countResult = $db->query($countQuery);

    $searchFields = [
      'courses.course_year',
      'education_levels.name',
      'groups.name',
      'specializations.name',
      'specialities.name',
      'disciplines.name',
      'users.name',
      'users.surname',
      'users.patronymic',
      'users.position_at_work',
      'users_2.name',
      'users_2.surname',
      'users_2.patronymic',
      'users_2.position_at_work',
      'GROUP_CONCAT(document_types.name)',
      'GROUP_CONCAT(documents.name)',
      'GROUP_CONCAT(documents.link)',
      'GROUP_CONCAT(documents.file)',
    ];

    $disableGroupByModeQuery = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";

    $db->query($disableGroupByModeQuery);
    $selectQuery = "
    SELECT
      disciplines.id,
      courses.course_year AS course_year,
      education_levels.name AS education_level_name,
      groups.name AS group_name,
      specializations.name AS specialization_name,
      specialities.name AS specialities_name,
      disciplines.name AS discipline_name,
      users.name AS lector_name,
      users.surname AS lector_surname,
      users.patronymic AS lector_patronymic,
      users.position_at_work AS lector_position,
      users_2.name AS practitioner_name,
      users_2.surname AS practitioner_surname,
      users_2.patronymic AS practitioner_patronymic,
      users_2.position_at_work AS practitioner_position,
      GROUP_CONCAT(document_types.name) AS document_types,
      GROUP_CONCAT(documents.name) AS document_names,
      GROUP_CONCAT(documents.link) AS document_links,
      GROUP_CONCAT(documents.file) AS document_files,
      COUNT(*) AS empty_fields_count
    FROM
      disciplines
      LEFT JOIN courses ON disciplines.course_id = courses.id
      LEFT JOIN education_levels ON courses.education_level_id = education_levels.id
      LEFT JOIN `groups` ON courses.group_id = groups.id
      LEFT JOIN specializations ON courses.specialization_id = specializations.id
      LEFT JOIN specialities ON specializations.speciality_id = specialities.id
      LEFT JOIN users ON disciplines.lector_id = users.id
      LEFT JOIN users AS users_2 ON disciplines.practitioner_id = users_2.id
      LEFT JOIN discipline_document ON disciplines.id = discipline_document.discipline_id
      LEFT JOIN documents ON discipline_document.document_id = documents.id
      LEFT JOIN document_types ON documents.document_type_id = document_types.id
      WHERE 1 ";

    if (!empty($filterData['group_name'])) {
      $groupFiltersQuery = "AND groups.name IN ('{$filterData['group_name']}')";
      $selectQuery .= " $groupFiltersQuery";
    }
  
    if (!empty($filterData['specialities_name'])) {
      $specialityFiltersQuery = "AND specialities.name IN ('{$filterData['specialities_name']}')";
      $selectQuery .= " $specialityFiltersQuery";
    }
    
    if (!empty($filterData['discipline_name'])) {
      $disciplineFiltersQuery = "AND disciplines.name IN ('{$filterData['discipline_name']}')";
      $selectQuery .= " $disciplineFiltersQuery";
    }
    if (!empty($filterData['course_year'])) {
      $courseFiltersQuery = "AND courses.course_year IN ('{$filterData['course_year']}')";
      $selectQuery .= " $courseFiltersQuery";
    }
  
    if (!empty($filterData['education_level_name'])) {
      $educationLevelFiltersQuery = "AND education_levels.name IN ('{$filterData['education_level_name']}')";
      $selectQuery .= " $educationLevelFiltersQuery";
    }
    if (!empty($filterData['lector_name'])) {
      $lectorFullNameArr = explode(" ", $filterData['lector_name']);
      $lectorFiltersQuery = "AND users.name IN ('{$lectorFullNameArr[0]}') AND users.surname IN ('{$lectorFullNameArr[1]}') AND users.patronymic IN ('{$lectorFullNameArr[2]}')";
      $selectQuery .= " $lectorFiltersQuery";
    }
  
    if (!empty($filterData['practitioner_name'])) {
      $practitionerFullNameArr = explode(" ", $filterData['practitioner_name']);
      $practitionerFiltersQuery = "AND users_2.name IN ('{$practitionerFullNameArr[0]}') AND users_2.surname IN ('{$practitionerFullNameArr[1]}') AND users_2.patronymic IN ('{$practitionerFullNameArr[2]}')";
      $selectQuery .= " $practitionerFiltersQuery";
    }

    if ($queryData['query'] == 'entriesToBeFilledSelect') {
      $selectQuery .= " AND (disciplines.id IS NULL
      OR courses.course_year IS NULL
      OR education_levels.name IS NULL
      OR groups.name IS NULL
      OR specializations.name IS NULL
      OR specialities.name IS NULL
      OR disciplines.name IS NULL
      OR users.name IS NULL
      OR users.surname IS NULL
      OR users.patronymic IS NULL
      OR users.position_at_work IS NULL
      OR users_2.name IS NULL
      OR users_2.surname IS NULL
      OR users_2.patronymic IS NULL
      OR users_2.position_at_work IS NULL
      OR document_types.name IS NULL
      OR documents.name IS NULL
      OR documents.link IS NULL)";
    }
    

    if (!empty($filterData['search_text'])) {
      $searchConditions = [];
      foreach ($searchFields as $field) {
      $searchConditions[] = "{$field} LIKE '%{$filterData['search_text']}%'";
      }
      $searchCondition = implode(' OR ', $searchConditions);
      $searchCondition = 'HAVING (' . $searchCondition . ')';
    }

    if ($queryData['query']=='fullFilledEntriesSelect') {
      $queryConditions = [];
      foreach ($searchFields as $field) {
        $queryConditions[] = "{$field} IS NOT NULL";
      }
      $queryCondition = implode(' AND ', $queryConditions);
      if(empty($filterData['search_text'])) {
        $queryCondition = 'HAVING (' . $queryCondition . ')';
      } else {
        $queryCondition = ' AND (' . $queryCondition . ')';
      }
    }

    $selectQuery .= "
    GROUP BY
      disciplines.id,
      courses.course_year,
      education_levels.name,
      `groups`.name,
      specializations.name,
      specialities.name,
      disciplines.name,
      users.name,
      users.surname,
      users.patronymic,
      users.position_at_work,
      users_2.name,
      users_2.surname,
      users_2.patronymic,
      users_2.position_at_work
      ";
    $selectQuery .= "{$searchCondition}";
    $selectQuery .= "{$queryCondition}";
    if (!empty($filterData['search_text']) && $queryData['query']=='entriesToBeFilledSelect') {
      $selectQuery .= "\nORDER BY empty_fields_count ASC";
    } else if(empty($filterData['search_text']) && $queryData['query']=='entriesToBeFilledSelect') {
      $selectQuery .= "\nHAVING empty_fields_count IS NOT NULL ORDER BY empty_fields_count ASC";
    }
    $selectResult = $db->query($selectQuery);
    $info = $selectResult->fetchAll(PDO::FETCH_ASSOC);

    return [
        'info' => $info,
    ];
}


  function generatePDF($formData) {

      $high_education_lvl = $formData['high_education_lvl'];
      $expertise_field = $formData['expertise_field'];
      $speciality = $formData['speciality'];
      $educational_program = $formData['educational_program'];
      $discipline_status = $formData['discipline_status'];
      $study_form = $formData['study_form'];
      $year_semester = $formData['year_semester'];
      $discipline_scope = $formData['discipline_scope'];
      $semester_control = $formData['semester_control'];
      $class_schedule = $formData['class_schedule'];
      $instruction_lang = $formData['instruction_lang'];
      $teacher = $formData['teacher'];
      $course_location = $formData['course_location'];
    
      $descr_field = $formData['descr_field'];
      $props_field = $formData['props_field'];
      $content_field = $formData['content_field'];
      $materials_field = $formData['materials_field'];
      $methodology_field = $formData['methodology_field'];
      $indep_work_field = $formData['indep_work_field'];
      $politics_field = $formData['politics_field'];
      $control_type_field = $formData['control_type_field'];
      $extra_inf_field = $formData['extra_inf_field'];
    
      $compiled_field = $formData['compiled_field'];
      $approved_field = $formData['approved_field'];
      $agreed_field = $formData['agreed_field'];
    
      $stylesheet = file_get_contents('../../styles/pdf_style.css');
    
      $mpdf = new \Mpdf\Mpdf();
      $footerHtml = <<<HTML
      <div class="syllabus_footer">
          <p><b>Робочу програму навчальної дисципліни (силабус):</b></p>
          <p><b>Складено</b> $compiled_field</p>
          <p><b>Ухвалено</b> $approved_field</p>
          <p><b>Погоджено</b> $agreed_field</p>
      </div>
      HTML;
      
      $html = <<<HTML
      <div class="header">
      <div class="logo"><img src="../../img/logo.png" alt="" style="width: auto; height: 17mm;"></div>
      <div class="department">Кафедра АПЕПС</div>
      </div>
      <h2>Засади усного професійного мовлення (риторика)</h2>
      <h6>Робоча програма навчальної дисципліни (Силабус)</h6>
      <p class="p_header p_header_first">Реквізити навчальної дисципліни</p>
      <table class="props_table">
        <tr>
          <td>Рівень вищої освіти</td>
          <td>$high_education_lvl</td>
        </tr>
        <tr>
          <td>Галузь знань</td>
          <td>$expertise_field</td>
        </tr>
        <tr>
          <td>Спеціальність</td>
          <td>$speciality</td>
        </tr>
        <tr>
          <td>Освітня програма</td>
          <td>$educational_program</td>
        </tr>
        <tr>
          <td>Статус дисципліни</td>
          <td>$discipline_status</td>
        </tr>
        <tr>
          <td>Форма навчання</td>
          <td>$study_form</td>
        </tr>
        <tr>
          <td>Рік підготовки,<br>семестр</td>
          <td>$year_semester</td>
        </tr>
        <tr>
          <td>Обсяг дисципліни</td>
          <td>$discipline_scope</td>
        </tr>
        <tr>
          <td>Семестровий<br>контроль/ контрольні<br>заходи</td>
          <td>$semester_control</td>
        </tr><tr>
          <td>Розклад занять</td>
          <td>$class_schedule</td>
        </tr>
        <tr>
          <td>Мова викладання</td>
          <td>$instruction_lang</td>
        </tr>
        <tr>
          <td>Інформація про<br>керівника курсу /<br>викладачів</td>
          <td>$teacher</td>
        </tr>
        <tr>
          <td>Розміщення курсу</td>
          <td>$course_location</td>
        </tr>
      </table>
      <div class="discipline_program">
          <p class="p_header">Програма навчальної дисципліни</p>
          <p class="p_enclosed_header p_enclosed_header_first">&emsp;&emsp;1. Опис навчальної дисципліни, її мета, предмет вивчання та результати навчання</p>
          <p>$descr_field</p>
          <p class="p_enclosed_header">&emsp;&emsp;2. Пререквізити та постреквізити дисципліни (місце в структурно-логічній схемі навчання за<br>відповідною освітньою програмою)</p>
          <p>$props_field</p>
          <p class="p_enclosed_header">&emsp;&emsp;3. Зміст навчальної дисципліни</p>
          <p>$content_field</p>
          <p class="p_enclosed_header">&emsp;&emsp;4. Навчальні матеріали та ресурси</p>
          <p>$materials_field</p>
        </div>
        <div class="educational_content">
        <p class="p_header">Навчальний контент</p>
        <p class="p_enclosed_header p_enclosed_header_first">&emsp;&emsp;5. Методика опанування навчальної дисципліни (освітнього компонента)</p>
        <p>$methodology_field</p>
        <p class="p_enclosed_header">&emsp;&emsp;6. Самостійна робота студента/аспіранта</p>
        <p>$indep_work_field</p>
      </div>
      <div class="politics_control">
        <p class="p_header">Політика та контроль</p>
        <p class="p_enclosed_header p_enclosed_header_first">&emsp;&emsp;7. Політика навчальної дисципліни (освітнього компонента)<br>Відвідування занять</p>
        <p>$politics_field</p>
        <p class="p_enclosed_header">&emsp;&emsp;8. Види контролю та рейтингова система оцінювання результатів навчання (РСО)</p>
        <p>$control_type_field</p>
        <!--  -->
        <p style="margin-top:20px;">Таблиця відповідності рейтингових балів оцінкам за університетською шкалою:</p>
        <table class="mark_table next-page">
          <tr>
            <td>Кількість балів</td>
            <td>Оцінка</td>
          </tr>
          <tr>
            <td>100-95</td>
            <td>Відмінно</td>
          </tr>
          <tr>
            <td>94-85</td>
            <td>Дуже добре</td>
          </tr>
          <tr>
            <td>84-75</td>
            <td>Добре</td>
          </tr>
          <tr>
            <td>74-65</td>
            <td>Задовільно</td>
          </tr>
          <tr>
            <td>64-60</td>
            <td>Достатньо</td>
          </tr>
          <tr>
            <td>Менше 60</td>
            <td>Незадовільно</td>
          </tr>
          <tr>
            <td>Не виконані умови допуску</td>
            <td>Не допущено</td>
          </tr>
        </table>
        <p class="p_enclosed_header">&emsp;&emsp;9. Додаткова інформація з дисципліни (освітнього компонента)</p>
        <p>$extra_inf_field</p>
      </div>
      HTML;
    
      $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
      $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
      $mpdf->SetHTMLFooter($footerHtml);
      $mpdf->Output('Силабус.pdf', 'D');
      exit;
    }
?>