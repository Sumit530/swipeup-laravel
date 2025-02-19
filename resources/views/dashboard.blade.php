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
                <!-- Dashboard Analytics Start -->
                <section id="dashboard-analytics">
                    <div class="row match-height">
                        <!-- Greetings Card starts -->
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card card-congratulations">
                                <div class="card-body text-center">
                                    <img src="{{ asset('public/app-assets/images/elements/decore-left.png') }}" class="congratulations-img-left" alt="card-img-left" />
                                    <img src="{{ asset('public/app-assets/images/elements/decore-right.png') }}" class="congratulations-img-right" alt="card-img-right" />
                                    <div class="avatar avatar-xl bg-primary shadow">
                                        <div class="avatar-content">
                                            <i data-feather="award" class="font-large-1"></i>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h1 class="mb-1 text-white">Congratulations {{ Auth::user()->name }},</h1>
                                        <p class="card-text m-auto w-75">
                                            You have a past 7 days total <strong>{{ $past_seven_days_users }} </strong> new user joined. Check all users then go to user secion and see.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Greetings Card ends -->
                    </div>

                    <div class="row match-height">
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ $total_users }}</h3>
                                        <span>Total Users</span>
                                    </div>
                                    <div class="avatar bg-light-primary p-50">
                                        <span class="avatar-content">
                                            <i data-feather="user" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ $total_videos }}</h3>
                                        <span>Total Videos</span>
                                    </div>
                                    <div class="avatar bg-light-danger p-50">
                                        <span class="avatar-content">
                                            <i data-feather="video" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ $total_songs }}</h3>
                                        <span>Total Songs</span>
                                    </div>
                                    <div class="avatar bg-light-success p-50">
                                        <span class="avatar-content">
                                            <i data-feather="music" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ $total_categories }}</h3>
                                        <span>Total Categories</span>
                                    </div>
                                    <div class="avatar bg-light-warning p-50">
                                        <span class="avatar-content">
                                            <i data-feather="align-justify" class="font-medium-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <!--  <div class="card">
                        <div class="card-header">
                            <h2>Simple QR Code</h2>
                        </div>
                        <div class="card-body">
                            {!! QrCode::size(300)->generate('anand') !!}
                        </div>
                        <div class="card-body">
                            {!! QrCode::size(300)->color(255,91,113)->generate('patel') !!}
                        </div>
                        <div class="card-body">
                        </div>
                    </div> -->
                    <!-- image qr code  -->
                    <!-- https://hackthestuff.com/article/generate-qr-code-in-laravel-8 -->

                    <div class="row match-height">
                        <!-- Company Table Card -->
                        <div class="col-lg-8 col-12">
                            <div class="card card-company-table">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Mobile No</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($last_users) > 0)
                                                    @foreach($last_users as $user)
                                                        <tr>
                                                            <td>{{ $user->id }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->username }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->mobile_no }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Company Table Card -->
                        <!-- Goal Overview Card -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Overview users</h4>
                                    <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                                </div>
                                <div class="card-body p-0">
                                    <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                                    <div class="row border-top text-center mx-0">
                                        <div class="col-6 border-end py-1">
                                            <p class="card-text text-muted mb-0">Active users</p>
                                            <h3 class="fw-bolder mb-0">{{ $total_active_users }}</h3>
                                        </div>
                                        <div class="col-6 py-1">
                                            <p class="card-text text-muted mb-0">Block users</p>
                                            <h3 class="fw-bolder mb-0">{{ $total_block_users }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Goal Overview Card -->
                    </div>
                </section>
                <!-- Dashboard Analytics end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection

