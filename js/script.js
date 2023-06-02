  const entriesToBeFilledSelect = document.getElementById('entriesToBeFilled');
  const fullFilledEntriesSelect = document.getElementById('fullFilledEntries');
  const urlParams = new URLSearchParams(window.location.search);

  if (entriesToBeFilledSelect) {
    entriesToBeFilledSelect.addEventListener('click', function() {
      urlParams.set('query', 'entriesToBeFilledSelect');
      window.location.href = `index.php?${urlParams.toString()}`;
    });
    }
    if (fullFilledEntriesSelect) {
      fullFilledEntriesSelect.addEventListener('click', function() {
      urlParams.set('query', 'fullFilledEntriesSelect');
      window.location.href = `index.php?${urlParams.toString()}`;
    });
    }

  function applyFilters() {

  const searchSelect = document.querySelector('input[name="search_text[]"]');
  const searchValue = searchSelect.value;

  const groupSelect = document.querySelector('select[name="group_name[]"]');
  const groupValue = groupSelect.value;

  const specialitySelect = document.querySelector('select[name="specialities_name[]"]');
  const specialityValue = specialitySelect.value;

  const disciplineSelect = document.querySelector('select[name="discipline_name[]"]');
  const disciplineValue = disciplineSelect.value;

  const educationalLvlSelect = document.querySelector('select[name="education_level_name[]"]');
  const educationalLvlValue = educationalLvlSelect.value;

  const courseYearSelect = document.querySelector('select[name="course_year[]"]');
  const courseYearValue = courseYearSelect.value;
  const lectorNameSelect = document.querySelector('select[name="lector_name[]"]');
  const lectorNameValue = lectorNameSelect.value;

  const practitionerNameSelect = document.querySelector('select[name="practitioner_name[]"]');
  const practitionerNameValue = practitionerNameSelect.value;

  if (searchValue) {
    urlParams.set('search_text', searchValue);
  } else {
    urlParams.delete('search_text');
  }
  if (groupValue) {
    urlParams.set('group_name', groupValue);
  } else {
    urlParams.delete('group_name');
  }

  if (specialityValue) {
    urlParams.set('specialities_name', specialityValue);
  } else {
    urlParams.delete('specialities_name');
  }

  if (educationalLvlValue) {
    urlParams.set('education_level_name', educationalLvlValue);
  } else {
    urlParams.delete('education_level_name');
  }

  if (courseYearValue) {
    urlParams.set('course_year', courseYearValue);
  } else {
    urlParams.delete('course_year');
  }

  if (disciplineValue) {
    urlParams.set('discipline_name', disciplineValue);
  } else {
    urlParams.delete('discipline_name');
  }

  if (lectorNameValue) {
    urlParams.set('lector_name', lectorNameValue);
  } else {
    urlParams.delete('lector_name');
  }

  if (practitionerNameValue) {
    urlParams.set('practitioner_name', practitionerNameValue);
  } else {
    urlParams.delete('practitioner_name');
  }
  

    window.location.href = `index.php?${urlParams.toString()}`;
  }

let thisPage = 1;
let limit = 5;
let list = document.querySelectorAll('.list .item');

function loadItem(){
    let beginGet = limit * (thisPage - 1);
    let endGet = limit * thisPage - 1;
    list.forEach((item, key)=>{
        if(key >= beginGet && key <= endGet){
            item.style.display = 'table-row';
        }else{
            item.style.display = 'none';
        }
    })
    listPage();
}
loadItem();
function listPage(){
    let count = Math.ceil(list.length / limit);
    document.querySelector('.listPage').innerHTML = '';

    if(thisPage != 1){
        let prev = document.createElement('li');
        var arrowIcon = document.createElement('i');
        arrowIcon.className = 'arrow left';
        prev.appendChild(arrowIcon);
        prev.setAttribute('onclick', "changePage(" + (thisPage - 1) + ")");
        document.querySelector('.listPage').appendChild(prev);
    }

    for(i = 1; i <= count; i++){
        let newPage = document.createElement('li');
        newPage.innerText = i;
        if(i == thisPage){
            newPage.classList.add('active');
        }
        newPage.setAttribute('onclick', "changePage(" + i + ")");
        document.querySelector('.listPage').appendChild(newPage);
    }

    if(thisPage != count && list.length != 0){
        let next = document.createElement('li');
        var arrowIcon = document.createElement('i');
        arrowIcon.className = 'arrow right';
        next.appendChild(arrowIcon);
        next.setAttribute('onclick', "changePage(" + (thisPage + 1) + ")");
        document.querySelector('.listPage').appendChild(next);
    }
}
function changePage(i){
    thisPage = i;
    loadItem();
}

function addQueryParamsToAction(event) {
  event.preventDefault();
  
  var form = event.target;
  var queryParams = window.location.search;
  var action = form.getAttribute("action");
  
  var finalAction = action + queryParams;
  form.setAttribute("action", finalAction);
  
  form.submit();
}
var container = document.querySelector('.filters');
var isExpanded = false;

container.addEventListener('click', function(event) {
if (window.innerWidth < 650) {
    isExpanded = !isExpanded;
    container.classList.toggle('expanded'); 
}else {
  container.style.height = '70px';
  container.style.overflow = 'visible';
}
});
