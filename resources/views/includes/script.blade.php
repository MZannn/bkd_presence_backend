 <!-- Bootstrap core JavaScript-->
 <script src="{{ url('admin/vendor/jquery/jquery.min.js') }}"></script>
 <script src="{{ url('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

 <!-- Core plugin JavaScript-->
 <script src="{{ url('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

 <!-- Custom scripts for all pages-->
 <script src="{{ url('admin/js/sb-admin-2.min.js') }}"></script>

 <!-- Page level plugins -->
 <script src="{{ url('admin/vendor/chart.js/Chart.min.js') }}"></script>

 <!-- Page level custom scripts -->
 <script src="{{ url('admin/js/demo/chart-area-demo.js') }}"></script>
 <script src="{{ url('admin/js/demo/chart-pie-demo.js') }}"></script>
 {{-- @include('sweetalert::alert') --}}
 <script>
     var msg = '{{ Session::get('alert') }}';
     var exist = '{{ Session::has('alert') }}';
     if (exist) {
         alert(msg);
     }
 </script>
