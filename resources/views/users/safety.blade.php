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
                            <!-- User Pills -->
                            @include('users.includes.top-menu')
                            <!--/ User Pills -->

                            <!-- connection -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-75">Safety</h4>
                                    <p>Display content from your connected accounts on your site</p>

                                    <!-- Connections -->
                                    <div class="d-flex mt-2">
                                        <div class="d-flex align-item-center justify-content-between flex-grow-1">
                                            <div class="me-1">
                                                <p class="fw-bolder mb-0">Allow comments</p>
                                            </div>
                                            <div class="mt-50 mt-sm-0">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class="form-check-input" id="checkboxGoogle" checked />
                                                    <label class="form-check-label" for="checkboxGoogle">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <div class="d-flex align-item-center justify-content-between flex-grow-1">
                                            <div class="me-1">
                                                <p class="fw-bolder mb-0">Allow duets</p>
                                            </div>
                                            <div class="mt-50 mt-sm-0">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class="form-check-input" id="checkboxSlack" />
                                                    <label class="form-check-label" for="checkboxSlack">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <div class="d-flex align-item-center justify-content-between flex-grow-1">
                                            <div class="me-1">
                                                <p class="fw-bolder mb-0">Allow messages</p>
                                            </div>
                                            <div class="mt-50 mt-sm-0">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class="form-check-input" id="checkboxGithub" checked />
                                                    <label class="form-check-label" for="checkboxGithub">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <div class="d-flex align-item-center justify-content-between flex-grow-1">
                                            <div class="me-1">
                                                <p class="fw-bolder mb-0">Allow downloads</p>
                                            </div>
                                            <div class="mt-50 mt-sm-0">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class="form-check-input" id="checkboxMailchimp" />
                                                    <label class="form-check-label" for="checkboxMailchimp">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <div class="d-flex align-item-center justify-content-between flex-grow-1">
                                            <div class="me-1">
                                                <p class="fw-bolder mb-0">Block List</p>
                                            </div>
                                            <div class="mt-50 mt-sm-0">
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class="form-check-input" id="checkboxAsana" />
                                                    <label class="form-check-label" for="checkboxAsana">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center pt-2">
                                        <a href="javascript:;" class="btn btn-primary me-1">Update</a>
                                        <a type="reset" class="btn btn-outline-danger reset">Cancel</a>
                                    </div>
                                    <!-- /Connections -->
                                </div>
                            </div>
                            <!--/ connection -->
                        </div>
                        <!--/ User Content -->
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection