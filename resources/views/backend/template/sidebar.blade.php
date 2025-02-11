@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
    $companyProfile = App\Models\CompanyProfile::first();
@endphp

<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset($companyProfile->image) }}" alt="AdminLTE Logo" style="width: 75px;"> {{ $companyProfile->name }}
    </a>
    <div class="sidebar">
        <br>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if (in_array($role, ['superadmin', 'owner', 'admin']))
                <li class="nav-header">Master</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Master Data<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('lantai.index') }}" class="nav-link {{ request()->routeIs('lantai.index') ? 'active' : '' }}">
                                <p>Lantai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('meja.index') }}" class="nav-link {{ request()->routeIs('meja.index') ? 'active' : '' }}">
                                <p>Meja</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (in_array($role, ['superadmin', 'owner']))
                <li class="nav-header">Settings</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Konfigurasi<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('privilage.index') }}" class="nav-link {{ request()->routeIs('privilage.index') ? 'active' : '' }}">
                                <p>Privilage</p>
                            </a>
                        </li>
                        @endif

                        @if (in_array($role, ['superadmin']))
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}" class="nav-link {{ request()->routeIs('role.index') ? 'active' : '' }}">
                                <p>Role</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('statusbooking.index') }}" class="nav-link {{ request()->routeIs('statusbooking.index') ? 'active' : '' }}">
                                <p>Status Booking</p>
                            </a>
                        </li>
                        @endif

                        @if (in_array($role, ['superadmin']))
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                                <p>Manage User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('companyProfile') }}" class="nav-link {{ request()->routeIs('companyProfile') ? 'active' : '' }}">
                                <p>Perusahaan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
