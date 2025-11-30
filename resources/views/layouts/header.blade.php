@php
    // Ambil tanggal hari ini di zona waktu WIB
    $today = \Carbon\Carbon::now('Asia/Jakarta')->locale('id');
@endphp

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    {{-- Tanggal di sisi kiri header --}}
    <div class="d-none d-md-flex align-items-center text-gray-600">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                 style="width: 36px; height: 36px; background-color: #f8f9fc; border: 1px solid #e3e6f0;">
                <i class="far fa-calendar-alt text-primary"></i>
            </div>
            <div>
                <span class="small font-weight-bold">
                    {{ $today->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::guard('admin')->user()->name }}</span>
                <div class="bg-secondary d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 35px; height: 35px;">
                    <i class="fas fa-user text-white" style="font-size: 0.875rem;"></i>
                </div>
            </a>

            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>