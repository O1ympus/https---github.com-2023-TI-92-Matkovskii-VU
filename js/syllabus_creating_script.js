function updateToolbar() {
    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var toolbar = 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | outdent indent | bullist numlist';
  
    if (width >= 481 && width <= 767) {
      toolbar = toolbar.replace(/align(left|center|right)|bold|italic/g, ''); 
    }
    if (window.innerWidth >= 480) {
        tinymce.init({
        selector: 'textarea',
        plugins: 'lists',
        toolbar: toolbar,
        menubar: false,
        branding: false,
        });
    }
  }
  window.addEventListener('load', updateToolbar);