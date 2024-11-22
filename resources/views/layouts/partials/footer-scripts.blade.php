@php
    $output = config('ziggy.output.script', \Tightenco\Ziggy\Output\Script::class);
//    $function = app('Tightenco\Ziggy\BladeRouteGenerator')->getRouteFunction();
    $function = file_get_contents(base_path('vendor/tightenco/ziggy/dist/index.js'));
    echo new $output(new \Tightenco\Ziggy\Ziggy(), $function, '')
@endphp

{{--@routes--}}
<script>
    Ziggy.url = '{{ env('APP_URL') }}';
</script>

<!-- Translations -->
<script src="{{ route('translations', ['locale' => session()->get('locale', config('app.locale'))]) }}"></script>

<!-- jQuery -->
<script src="{{ urlModTime('/assets/js/jquery-3.7.0.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ urlModTime('/assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Feather Js -->
<script src="{{ urlModTime('/assets/js/feather.min.js') }}"></script>

<!-- Slimscroll -->
<script src="{{ urlModTime('/assets/js/jquery.slimscroll.js') }}"></script>

<!-- Select2 Js -->
<script src="{{ urlModTime('/assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ urlModTime('/assets/plugins/select2/js/custom-select.js') }}"></script>

<!-- Datatables JS -->
<!--<script src="{{ urlModTime('/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ urlModTime('/assets/plugins/datatables/datatables.min.js') }}"></script>-->

<!-- counterup JS -->
<script src="{{ urlModTime('/assets/js/jquery.waypoints.js') }}"></script>
<script src="{{ urlModTime('/assets/js/jquery.counterup.min.js') }}"></script>

<!-- Apexchart JS -->
<script src="{{ urlModTime('/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ urlModTime('/assets/plugins/apexchart/chart-data.js') }}"></script>

<!-- Calendar Js -->
<script src="{{ urlModTime('/assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
<script src="{{ urlModTime('/assets/js/calander.js') }}"></script>

<!-- Circle Progress JS -->
<script src="{{ urlModTime('/assets/js/circle-progress.min.js') }}"></script>

<!-- Slick JS -->
<script src="{{ urlModTime('/assets/plugins/slick/slick.js') }}"></script>

<!-- Datepicker Core JS -->
<script src="{{ urlModTime('/assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ urlModTime('/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<script src="{{ urlModTime('/assets/plugins/light-gallery/js/lightgallery-all.min.js') }}"></script>

<!-- Summernote JS -->
<script src="{{ urlModTime('/assets/plugins/summernote/summernote-bs5.min.js') }}"></script>

<!-- Ck Editor JS -->
<script src="{{ urlModTime('/assets/js/ckeditor.js') }}"></script>

<!-- Full Calendar -->
<script src="{{ urlModTime('/assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ urlModTime('/assets/js/fullcalendar.min.js') }}"></script>
<script src="{{ urlModTime('/assets/js/jquery.fullcalendar.js') }}"></script>

<!-- Mask JS -->
<script src="{{ urlModTime('/assets/js/jquery.maskedinput.min.js') }}"></script>

@if (Route::is(['add-blog', 'edit-blog']))
    <!-- Tagsinput JS -->
    <script src="{{ urlModTime('/assets/js/tagsinput.js') }}"></script>
@endif

@if (Route::is(['seo-settings']))
    <!-- Bootstrap Tagsinput JS -->
    <script src="{{ urlModTime('/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}"></script>
@endif

<!-- Sweetalert 2 -->
<script src="{{ urlModTime('/assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ urlModTime('/assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

<!-- Daterangepicker JS -->
<script src="{{ urlModTime('/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

<!-- Fileupload JS -->
{{--<script src="{{ urlModTime('/assets/plugins/fileupload/fileupload.min.js') }}"></script>--}}
<script src="{{ urlModTime('/assets/plugins/fileupload/fileupload.js') }}"></script>

<script src="{{ urlModTime('/assets/plugins/lightbox/glightbox.min.js') }}"></script>

<!-- Da data -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/js/jquery.suggestions.min.js"></script>

@livewireScriptConfig

@stack('scripts-libs')

<!-- Custom JS -->
{{--<script src="{{ urlModTime('/assets/js/app.js') }}"></script>--}}
{{--<script src="{{ urlModTime('/assets/js/app-functions.js') }}"></script>--}}
{{--<script src="{{ urlModTime('/assets/js/app-common.js') }}"></script>--}}

<!-- Vite Scripts -->
@vite(['resources/js/app.js'])

@stack('scripts-app')
