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

 <script
     src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_KUAyGozVXUuA1h-QzMHxCS8OdKMzEpE&callback=initMap&language=id"
     async defer></script>
 <script>
     // Initialize Google Maps
     function initMap() {
         var map = new google.maps.Map(document.getElementById('map'), {
             center: {
                 lat: -2.2097652,
                 lng: 113.90948,
             },
             zoom: 13,
             mapTypeControl: false,
             mapTypeId: google.maps.MapTypeId.ROADMAP
         });

         var geocoder = new google.maps.Geocoder();

         // Add listener for map click
         var marker = new google.maps.Marker({
             position: {
                 lat: -6.21462,
                 lng: 106.84513
             },
             map: map,
             draggable: true
         });

         // Add listener for marker drag event
         marker.addListener('dragend', function(event) {
             // Get latitude and longitude from marker position
             var latLng = marker.getPosition();
             var latitude = latLng.lat();
             var longitude = latLng.lng();

             // Set latitude and longitude input value
             document.getElementById("latitude").value = latitude;
             document.getElementById("longitude").value = longitude;

             // Reverse geocode to get address
             geocoder.geocode({
                 'location': latLng
             }, function(results, status) {
                 if (status === 'OK') {
                     if (results[0]) {
                         // Set address input value
                         var addressInput = document.getElementById("address");
                         addressInput.value = results[0].formatted_address;
                     } else {
                         window.alert('No results found');
                     }
                 } else {
                     window.alert('Geocoder failed due to: ' + status);
                 }
             });
         });

         // Add listener for map click event
         map.addListener('click', function(event) {
             // Get latitude and longitude from click event
             var latLng = event.latLng;
             var latitude = latLng.lat();
             var longitude = latLng.lng();

             // Set marker position
             marker.setPosition(latLng);

             // Set latitude and longitude input value
             document.getElementById("latitude").value = latitude;
             document.getElementById("longitude").value = longitude;
             console.log(latitude, longitude);
             // Reverse geocode to get address
             geocoder.geocode({
                 'location': latLng
             }, function(results, status) {
                 if (status === 'OK') {
                     if (results[0]) {
                         // Set address input value
                         document.getElementById("address").value = results[0]
                             .formatted_address;
                     } else {
                         window.alert('No results found');
                     }
                 } else {
                     window.alert('Geocoder failed due to: ' + status);
                 }
             });
         });
     }
 </script>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
     function confirmDelete(id) {
         Swal.fire({
             title: 'Apakah kamu yakin?',
             text: "Data yang sudah dihapus tidak bisa dikembalikan!",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#dc3545',
             cancelButtonColor: '#6c757d',
             confirmButtonText: 'Ya, hapus!',
             cancelButtonText: 'Batal'
         }).then((result) => {
             if (result.isConfirmed) {
                 document.getElementById('form-delete-' + id).submit();
             }
         })
     }
 </script>

 <script>
     function confirmDeleteOffice(id, office_name) {
         Swal.fire({
             title: 'Data kantor ' + office_name +
                 ' tidak bisa dihapus karena masih ada data pegawai pada kantor ' + office_name +
                 '. Silahkan hapus data pegawai pada kantor ' + office_name + ' terlebih dahulu!',
             showClass: {
                 popup: 'animate__animated animate__fadeInDown'
             },
             hideClass: {
                 popup: 'animate__animated animate__fadeOutUp'
             }
         })
     }
 </script>
