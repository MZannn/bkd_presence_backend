 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
         <div class="sidebar-brand-icon">
             <img src="{{ url('admin/img/logo_bkd.png') }}" alt="" width="50">
         </div>
         <div class="sidebar-brand-text">
             @if (Auth::user()->roles == 'SUPER ADMIN')
                 Super Admin
             @elseif (Auth::user()->roles == 'ADMIN')
                 Admin
             @endif
         </div>
     </a>

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <!-- Nav Item - Dashboard -->
     <li class="nav-item">
         <a class="nav-link" href="{{ route('dashboard') }}">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>
     @if (Auth::user()->roles == 'SUPER ADMIN')
         <li class="nav-item">
             <a class="nav-link" href="{{ route('office.index') }}">
                 <i class="fas fa-fw fa-building"></i>
                 <span>Kantor</span></a>
         </li>
     @endif
     <li class="nav-item">
         <a class="nav-link" href="{{ route('employee.index') }}">
             <i class="fas fa-fw fa-user"></i>
             <span>Pegawai</span></a>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('presence.index') }}">
             <i class="fas fa-fw fa-calendar-check"></i>
             <span>Presensi</span></a>
     </li>
     @if (Auth::user()->roles == 'SUPER ADMIN')
         <li class="nav-item">
             <a class="nav-link" href="{{ route('user.index') }}">
                 <i class="fas fa-fw fa-user-tie"></i>
                 <span>Admin Kantor</span></a>
         </li>
     @endif

     <li class="nav-item">
         <a class="nav-link" href="{{ route('permissionAndSick') }}">
             <i class="fas fa-fw fa-virus"></i>
             <span>Izin dan Sakit</span></a>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('bussinessTrip') }}">
             <i class="fas fa-fw fa-briefcase"></i>
             <span>Perjalanan Dinas</span></a>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('vacation.index') }}">
             <i class="fas fa-fw fa-map"></i>
             <span>Cuti</span></a>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('reportChangeDevice') }}">
             <i class="fas fa-fw fa-mobile"></i>
             <span>Penggantian Device</span></a>
     </li>
     <!-- Divider -->
     <hr class="sidebar-divider d-none d-md-block">

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>



 </ul>
 <!-- End of Sidebar -->
