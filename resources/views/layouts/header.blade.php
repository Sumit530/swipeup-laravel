    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ Auth::user()->name }}</span><span class="user-status">Admin</span></div><span class="avatar"><img class="round" src="{{ asset('public/app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="me-50" data-feather="user"></i> 
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="me-50" data-feather="settings"></i> 
                            Settings
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.logouts') }}">
                            <i class="me-50" data-feather="power"></i> 
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto"><a class="navbar-brand" href="{{ asset('public/html/ltr/vertical-menu-template/index.html') }}"><span class="brand-logo">
                        <h2 class="brand-text">SWIPE UP</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item {{ (request()->is('/') || request()->is('admin/dashboard')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboards">
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/users/*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('users.index') }}"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="Users">Users</span></a>
                </li>
                <li class="nav-item {{ (request()->is('admin/videos/*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('videos.index') }}"><i data-feather="copy"></i><span class="menu-title text-truncate" data-i18n="Users">Videos</span></a>
                </li>
                <li class=" nav-item"><a class="d-flex align-items-center" href="app-chat.html"><i data-feather="bell"></i><span class="menu-title text-truncate" data-i18n="Notifications">Notifications</span></a>
                </li>
                <li class="nav-item {{ (request()->is('admin/helpcenter')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('helpcenter.index') }}"><i data-feather="life-buoy"></i><span class="menu-title text-truncate" data-i18n="Help & Center">Help Centers</span></a>
                </li>
                <li class="nav-item {{ (request()->is('admin/songs') || request()->is('admin/songs')) ? 'has-sub sidebar-group-active open' : '' }}"><a class="d-flex align-items-center" href="#"><i data-feather="grid"></i><span class="menu-title text-truncate" data-i18n="Others">Songs</span></a>
                    <ul class="menu-content">
                        <li class="nav-item {{ (request()->is('admin/songs')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('songs.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">List all songs</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('admin/category')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('category.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Category</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('admin/singers')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('singers.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Singers</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ (request()->is('admin/bannerimages')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('bannerimages.index') }}"><i data-feather="image"></i><span class="menu-title text-truncate" data-i18n="Help & Center">Banner Images</span></a></li>
                <li class="nav-item {{ (request()->is('admin/videoreportreqest')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('videoreportreqest.index') }}"><i data-feather="sun"></i><span class="menu-title text-truncate" data-i18n="Help & Center">Video report reqest</span></a></li>
                <li class="nav-item {{ (request()->is('admin/effects')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('effects.index') }}"><i data-feather="sun"></i><span class="menu-title text-truncate" data-i18n="Help & Center">Effects</span></a></li>
                <li class="nav-item {{ (request()->is('admin/country') || request()->is('admin/language')) ? 'has-sub sidebar-group-active open' : '' }}"><a class="d-flex align-items-center" href="#"><i data-feather="grid"></i><span class="menu-title text-truncate" data-i18n="Others">Others</span></a>
                    <ul class="menu-content">
                        <li class="nav-item {{ (request()->is('admin/language')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('language.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Languages</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('admin/country')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('country.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Country</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('admin/interests')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('interests.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Interests</span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('admin/videoreporttype')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('videoreporttype.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Video report type</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ (request()->is('admin/account_verifications')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('account_verifications.index') }}"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="Users">Account verifications</span></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    @if (session('success'))
    <script type="text/javascript">toastr.success('<?php echo session('success'); ?>');</script>
    @endif
    @if (session('error'))
    <script type="text/javascript">toastr.error('<?php echo session('error'); ?>');</script>
    @endif