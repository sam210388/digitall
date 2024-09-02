  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <title>
       @isset($judul)
           {{$judul}}
       @else
           Welcome To DigitAll
       @endisset
   </title>
  <link rel="icon" type="image/x-icon" href="{{env('APP_URL')."/".asset('/logo/logodigitall.png')}}">

  <!-- jQuery -->
  <script src="{{ env('APP_URL')."/".asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('/AdminLTE/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/dataTables-fixedColumns/css/fixedColumns.bootstrap4.css') }}" >
  <link rel="stylesheet" href="{{env('APP_URL')."/".asset('AdminLTE/plugins/dataTables-fixedHeader/css/fixedHeader.bootstrap4.css') }}" >

  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/jquery-numeric/jquery.numeric.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/jquery-numeric/jquery.numeric.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-fixedheader/js/dataTables.fixedHeader.js')}}"></script>


  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/pdfmake/pdfmake.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/pdfmake/vfs_fonts.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/js/jszip.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/js/buttons.flash.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/moment/moment.min.js')}}"></script>
  <script src="{{env('APP_URL')."/".asset('AdminLTE/plugins/daterangepicker/daterangepicker.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.4/autoNumeric.js" integrity="sha512-uHhJD1WOK5O11pTjFZzz8QG6P5WrF6vZFCvDQSVNAyBGPNVQ1HYpBb84r+aH+lPXr1DDlynlf5s/uWQgotBsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.4/autoNumeric.min.js" integrity="sha512-oy12ZbZubVh9o88NpH8ypywObq7ZM6dLo4pX0dIWeNfXEhJk8e23NZ2H/163ddnUOLIsW7lybYlOgf708Y8vxA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet" type="text/css" />
