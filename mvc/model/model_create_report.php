<?php
require_once '../../vendor/autoload.php';
require_once '../../PHPSimpleHTMLDOMParser/simple_html_dom.php';
require '../controller/controller.php';

function extractTableContent($html) {
    $pattern = '/<table class="fl-table" id="fl-table">(.*?)<\/table>/s';
    preg_match($pattern, $html, $matches);

    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return null;
    }
}

function generateReportPDF() {
    global $info;
    global $filterData;
    global $queryData;
    $filterDataRenamed;
    $queryDataRenamed;

    if (isset($_GET['query'])) {
        $queryData['query'] = $_GET['query'];
        $queryDataRenamed['Запит'] = ($_GET['query'] == "fullFilledEntriesSelect") ?  "\"Показати повністю готові записи\"" : "\"Показати записи що потрібно заповнити\"";
    }

    if (isset($_GET['group_name'])) {
        $filterData['group_name'] = $_GET['group_name'];
        $filterDataRenamed['Назва групи'] = $_GET['group_name'];
    }
    if (isset($_GET['specialities_name'])) {
        $filterData['specialities_name'] = $_GET['specialities_name'];
        $filterDataRenamed['Назва спеціальності'] = $_GET['specialities_name'];
    }
    if (isset($_GET['discipline_name'])) {
        $filterData['discipline_name'] = $_GET['discipline_name'];
        $filterDataRenamed['Назва дисципліни'] = $_GET['discipline_name'];
    }
    if (isset($_GET['course_year'])) {
        $filterData['course_year'] = $_GET['course_year'];
        $filterDataRenamed['Курс'] = $_GET['course_year'];
    }
    if (isset($_GET['education_level_name'])) {
        $filterData['education_level_name'] = $_GET['education_level_name'];
        $filterDataRenamed['Рівень освіти'] = $_GET['education_level_name'];
    }
    if (isset($_GET['lector_name'])) {
        $filterData['lector_name'] = $_GET['lector_name'];
        $filterDataRenamed['Повне ім\'я лектора'] = $_GET['lector_name'];
    }
    if (isset($_GET['practitioner_name'])) {
        $filterData['practitioner_name'] = $_GET['practitioner_name'];
        $filterDataRenamed['Повне ім\'я практика'] = $_GET['practitioner_name'];
    }
    if (isset($_GET['search_text'])) {
        $filterData['search_text'] = $_GET['search_text'];
        $filterDataRenamed['Пошук за рядком'] = $_GET['search_text'];
    }
    

    $html = '';
    if (!empty($filterDataRenamed)) {
        $html .= "
            <h3>Фільтри</h3>
        ";
        foreach($filterDataRenamed as $filterKey => $filterEl) {
            $html .= "<p> $filterKey: $filterEl; ";
        }
        $html .="</p>";
    } 
    if (!empty($queryDataRenamed)) {
        $html .= "
            <h3>Запити</h3>
        ";
        foreach($queryDataRenamed as $queryKey => $queryEl) {
            $html .= "<p>$queryKey: $queryEl; ";
        }
        $html .="</p>";
    } 
    $html .= '
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
                    <th>Конспект лекцій (Електронний ресурс)</th>
                </tr>
            </thead>
            <tbody class="list">';
            
    foreach ($info as $el) {
        $html .= '<tr class="item">
                    <td>' . $el['course_year'] . '</td>
                    <td>' . $el['education_level_name'] . '</td>
                    <td>' . $el['group_name'] . '</td>
                    <td>' . $el['discipline_name'] . '</td>
                    <td>';
        
        $html .= "Лектор: " . $el['lector_name'] . " " . $el['lector_surname'] . " " . $el['lector_patronymic'];
        $html .= "<br>Практик: " . $el['practitioner_name'] . " " . $el['practitioner_surname'] . " " . $el['practitioner_patronymic'];
        
        $html .= '</td>';
        
        $document_type_array = explode(',', $el['document_types']);
        $document_name_array = explode(',', $el['document_names']);
        
        $html .= '<td>';
        
        for ($i = 0; $i < count($document_type_array); $i++) {
            if ($document_type_array[$i] == 'Силабус') {
                $html .= $document_name_array[$i] . "<br>";
            }
        }
        
        $html .= '</td><td>';
        
        for ($i = 0; $i < count($document_type_array); $i++) {
            if ($document_type_array[$i] == 'Посібник') {
                $html .= $document_name_array[$i] . "<br>";
            }
        }
        
        $html .= '</td><td>';
        
        for ($i = 0; $i < count($document_type_array); $i++) {
            if ($document_type_array[$i] == 'Підручник') {
                $html .= $document_name_array[$i] . "<br>";
            }
        }
        
        $html .= '</td><td>' . $el['document_links'] . '</td></tr>';
    }
    
    $html .= '</tbody></table>';

    $stylesheet = file_get_contents('../../styles/report_generate.css');
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->Output('Звіт.pdf', 'D');
}
?>
