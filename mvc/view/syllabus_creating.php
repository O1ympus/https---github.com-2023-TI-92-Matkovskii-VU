<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../styles/syllabus_creating_style.css">
    <script src="https://cdn.tiny.cloud/1/91eizmh31jot2wwkjt05nnrsquvnn60cqejosonx6ri7kqrz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Creating syllabus</title>
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
  <div class="form-stationary" id="form-stationary">
    <form action="../controller/generate_pdf.php" method="POST">
    
        <h3>Реквізити</h3>

        <div class="props_container">
            <div class="props_el"> 
                <label for="high_education_lvl">Рівень вищої освіти</label>
                <select id="high_education_lvl" name="high_education_lvl">
                <option value="---">---</option>
                <option value="Перший (бакалаврський)">Перший (бакалаврський)</option>
                <option value="Другий (магістерський)">Другий (магістерський)</option>
                <option value="Третій (освітньо-науковий)">Третій (освітньо-науковий)</option>
                </select>
            </div>

            <div class="props_el">
            <label for="expertise_field">Галузь знань</label>
            <input type="text" id="expertise_field" name="expertise_field" placeholder="Галузь знань..">
            </div>

            <div class="props_el">
            <label for="speciality">Спеціальність</label>
            <input type="text" id="speciality" name="speciality" placeholder="Спеціальність..">
            </div>

            <div class="props_el">
            <label for="educational_program">Освітня програма</label>
            <input type="text" id="educational_program" name="educational_program" placeholder="Освітня програма..">
            </div>

            <div class="props_el">
            <label for="discipline_status">Статус дисципліни</label>
            <input type="text" id="discipline_status" name="discipline_status" placeholder="Статус дисципліни..">
            </div>
            
            <div class="props_el">
            <label for="study_form">Форма навчання</label>
            <input type="text" id="study_form" name="study_form" placeholder="Форма навчання..">
            </div>

            <div class="props_el">
            <label for="year_semester">Рік підготовки, семестр</label>
            <input type="text" id="year_semester" name="year_semester" placeholder="Рік підготовки, семестр..">
            </div>

            <div class="props_el">
            <label for="discipline_scope">Обсяг дисципліни</label>
            <input type="text" id="discidiscipline_scope" name="discipline_scope" placeholder="Обсяг дисципліни..">
            </div>

            <div class="props_el">
            <label for="semester_control">Семестровий контроль</label>
            <input type="text" id="semester_control" name="semester_control" placeholder="Семестровий контроль..">
            </div>

            <div class="props_el">
            <label for="class_schedule">Розклад занять</label>
            <input type="text" id="class_schedule" name="class_schedule" placeholder="Розклад занять..">
            </div>

            <div class="props_el">
            <label for="instruction_lang">Мова викладання</label>
            <input type="text" id="disciinstruction_lang" name="instruction_lang" placeholder="Мова викладання..">
            </div>

            <div class="props_el">
            <label for="teacher">Інформація про керівника курсу</label>
            <input type="text" id="teacher" name="teacher" placeholder="Інформація про керівника курсу..">
            </div>

            <div class="props_el">
            <label for="course_location">Розміщення курсу</label>
            <input type="text" id="course_location" name="course_location" placeholder="Розміщення курсу..">
            </div>
        </div> 

        <h3>Програма навчальної дисципліни</h3>

        <label for="descr_field">1. Опис навчальної дисципліни, її мета, предмет вивчання та результати навчання</label>
        <textarea  name="descr_field" id="descr_field"></textarea>

        <label for="props_field">2. Пререквізити та постреквізити дисципліни (місце в структурно-логічній схемі навчання за відповідною освітньою програмою)</label>
        <textarea name="props_field" id="props_field"></textarea>

        <label for="content_field">3. Зміст навчальної дисципліни </label>
        <textarea name="content_field" id="content_field"></textarea>

        <label for="materials_field">4. Навчальні матеріали та ресурси</label>
        <textarea name="materials_field" id="materials_field"></textarea>
        
        <h3>Навчальний контент</h3>

        <label for="methodology_field">5. Методика опанування навчальної дисципліни (освітнього компонента)</label>
        <textarea name="methodology_field" id="methodology_field"></textarea>

        <label for="indep_work_field">6. Самостійна робота здобувача вищої освіти</label>
        <textarea name="indep_work_field" id="indep_work_field"></textarea>

        <h3>Політика та контроль</h3>

        <label for="politics_field">7. Політика навчальної дисципліни (освітнього компонента)</label>
        <textarea name="politics_field" id="politics_field"></textarea>

        <label for="control_type_field">8. Види контролю та рейтингова система оцінювання результатів навчання (РСО)</label>
        <textarea name="control_type_field" id="control_type_field"></textarea>

        <label for="extra_inf_field">9. Додаткова інформація з дисципліни (освітнього компонента)</label>
        <textarea name="extra_inf_field" id="extra_inf_field"></textarea>

        <h3>Робочу програму навчальної дисципліни (силабус):</h3>

        <div class="approval">

            <div class="approval_el">
            <label for="compiled_field">Складено</label>
            <input type="text" id="compiled_field" name="compiled_field" placeholder="посада, науковий ступінь, вчене звання, ПІБ..">
            </div>

            <div class="approval_el">
            <label for="approved_field">Ухвалено</label>
            <input type="text" id="approved_field" name="approved_field" placeholder="кафедрою __________ (протокол № ___ від ____________)..">
            </div>

            <div class="approval_el">
            <label for="agreed_field">Погоджено</label>
            <input type="text" id="agreed_field" name="agreed_field" placeholder="Методичною комісією факультету  (протокол № ___ від ____________)..">
            </div>
        </div>
        <input type="submit" value="Створити силабус">
    </form>
  </div>
<script src="/../js/syllabus_creating_script.js">

</script>
</body>
</html>