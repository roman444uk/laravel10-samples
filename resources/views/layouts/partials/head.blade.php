<!-- Bootstrap CSS -->
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/bootstrap.min.css') }}">

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{ urlModTime('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ urlModTime('assets/plugins/fontawesome/css/all.min.css') }}">

<!-- Feathericon CSS -->
<link rel="stylesheet" href="{{ urlModTime('assets/plugins/feather/feather.css') }}">

<link rel="stylesheet" href="{{ urlModTime('assets/plugins/icons/ionic/ionicons.css') }}">

<!-- Select2 CSS -->
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/select2.min.css') }}">

<!-- Datatables CSS -->
<link rel="stylesheet" href="{{ urlModTime('assets/plugins/datatables/datatables.min.css') }}">

<!-- Calendar CSS -->
<link rel="stylesheet" href="{{ urlModTime('assets/plugins/simple-calendar/simple-calendar.css') }}">

<!-- Datepicker CSS -->
<link rel="stylesheet" href="{{ urlModTime('assets/css/bootstrap-datetimepicker.min.css') }}">

<!-- Slick CSS -->
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/plugins/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/plugins/slick/slick-theme.css') }}">

<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/plugins/light-gallery/css/lightgallery.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/plugins/lightbox/glightbox.min.css') }}">

<!-- Summernote CSS -->
<link rel="stylesheet" href="{{ urlModTime('assets/plugins/summernote/summernote-bs5.min.css') }}">
{{--@styleTag('assets/plugins/summernote/summernote-bs5.min.css');--}}

@if (Route::is(['others-settings']))
    <!-- Ck Editor -->
    <link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/ckeditor.css') }}">
@endif

@if (Route::is(['add-blog', 'edit-blog']))
    <!-- Tagsinput CSS -->
    <link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/tagsinput.css') }}">
@endif

@if (Route::is(['seo-settings']))
    <link rel="stylesheet" href="{{ urlModTime('assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">
@endif

<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/fullcalendar.min.css') }}">


<!-- Da data -->
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/css/suggestions.min.css" rel="stylesheet" />

@stack('styles-libs')

<!-- Main CSS -->
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/style-timeline.css') }}">
<link rel="stylesheet" type="text/css" href="{{ urlModTime('assets/css/style-custom.css') }}">

@stack('styles-app')

<script type="text/javascript">
    @stack('scripts-data')
</script>

<!-- Vite Scripts -->
@vite(['resources/css/app.css'])
