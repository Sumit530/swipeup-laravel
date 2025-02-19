@extends('layouts.app')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="app-user-view-connections">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                            @include('users.includes.left-prodofile')
                        </div>
                        <!-- User Content -->
                        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">21,459</h3>
                                                <span>Total Users</span>
                                            </div>
                                            <div class="avatar bg-light-primary p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-4"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">4,567</h3>
                                                <span>Paid Users</span>
                                            </div>
                                            <div class="avatar bg-light-danger p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">19,860</h3>
                                                <span>Active Users</span>
                                            </div>
                                            <div class="avatar bg-light-success p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">237</h3>
                                                <span>Pending Users</span>
                                            </div>
                                            <div class="avatar bg-light-warning p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">21,459</h3>
                                                <span>Total Users</span>
                                            </div>
                                            <div class="avatar bg-light-primary p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user font-medium-4"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">4,567</h3>
                                                <span>Paid Users</span>
                                            </div>
                                            <div class="avatar bg-light-danger p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">19,860</h3>
                                                <span>Active Users</span>
                                            </div>
                                            <div class="avatar bg-light-success p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="fw-bolder mb-75">237</h3>
                                                <span>Pending Users</span>
                                            </div>
                                            <div class="avatar bg-light-warning p-50">
                                                <span class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x font-medium-4"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ User Content -->
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection