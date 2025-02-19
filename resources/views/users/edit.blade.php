@extends('layouts.app')
@section('content')

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Update user details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">users</a>
                                    </li>
                                    <li class="breadcrumb-item active">update user details
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <a href="{{ route('users.index') }}" class="btn-icon btn btn-primary btn-round btn-sm" type="button" title="" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Back">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Update user details</h4>
                                </div>
                                <div class="card-body">
                                    @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">
                                        <div class="alert-body"><strong>Error!</strong> {{ $error }}</div>
                                    </div>
                                    @endforeach
                                    <form class="form" id="form" action="{{ route('users.update',array($user->id)) }}" method="POST">
                                        {{ method_field('PUT') }}
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Name <span class="required_sign">*</span></label>
                                                    <input type="text" id="name" class="form-control" placeholder="Enter name" name="name" value="{{ isset($user->name) ? $user->name : old('name') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email">Email <span class="required_sign">*</span></label>
                                                    <input type="email" id="email" class="form-control" placeholder="Enter email" name="email" value="{{ isset($user->email) ? $user->email : old('email') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="username">Username <span class="required_sign">*</span></label>
                                                    <input type="text" id="username" class="form-control" placeholder="Enter username" name="username" value="{{ isset($user->username) ? $user->username : old('username') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="country_code">Country code <span class="required_sign">*</span></label>
                                                    <input type="text" id="country_code" class="form-control" placeholder="Enter country code" name="country_code" value="{{ isset($user->country_code) ? $user->country_code : old('country_code') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="mobile_no">Mobile no <span class="required_sign">*</span></label>
                                                    <input type="text" id="mobile_no" class="form-control" placeholder="Enter mobile no" name="mobile_no" value="{{ isset($user->mobile_no) ? $user->mobile_no : old('mobile_no') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="page_name">Page name <span class="required_sign">*</span></label>
                                                    <input type="text" id="page_name" class="form-control" placeholder="Enter page name" name="page_name" value="{{ isset($user->page_name) ? $user->page_name : old('page_name') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="dob">DOB <span class="required_sign">*</span></label>
                                                    <input type="text" id="dob" class="form-control flatpickr-basic" placeholder="Select date of birthday" name="dob" value="{{ isset($user->dob) ? date('d-m-Y',strtotime($user->dob)) : old('dob') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="profile_image">Profile image <span class="required_sign">*</span></label>
                                                    <input type="file" id="profile_image" class="form-control" placeholder="Select profile image" name="profile_image"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="website">Website <span class="required_sign">*</span></label>
                                                    <input type="text" id="website" class="form-control" placeholder="Enter website" name="website" value="{{ isset($user->website) ? $user->website : old('website') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="bio">BIO <span class="required_sign">*</span></label>
                                                   <textarea type="text" id="bio" class="form-control" placeholder="Enter bio" name="bio">{{ isset($user->bio) ? $user->bio : old('bio') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                     <label class="form-label" for="website">Other Details <span class="required_sign">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="allow_find_me" name="allow_find_me" @if($user->allow_find_me == 1) checked="" @endif>
                                                        <label class="form-check-label" for="allow_find_me">Allow find me</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="private_account" name="private_account" @if($user->private_account == 1) checked="" @endif>
                                                        <label class="form-check-label" for="private_account">Private account</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_vip" name="is_vip" @if($user->is_vip == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_vip">VIP User</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                     <label class="form-label" for="website">Safety Details <span class="required_sign">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_allow_comments" name="is_allow_comments" @if($safety->is_allow_comments == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_allow_comments">Allow comments</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_allow_duets" name="is_allow_duets" @if($safety->is_allow_duets == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_allow_duets">Allow duets</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_allow_messages" name="is_allow_messages" @if($safety->is_allow_messages == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_allow_messages">Allow messages</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_allow_downloads" name="is_allow_downloads" @if($safety->is_allow_downloads == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_allow_downloads">Allow downloads</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                     <label class="form-label" for="website">Notifications Details <span class="required_sign">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_likes" name="is_likes" @if($notificationsettings->is_likes == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_likes">Likes</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_comments" name="is_comments" @if($notificationsettings->is_comments == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_comments">Comments</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_new_followers" name="is_new_followers" @if($notificationsettings->is_new_followers == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_new_followers">New followers</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_mentions" name="is_mentions" @if($notificationsettings->is_mentions == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_mentions">Mentions</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_direct_messages" name="is_direct_messages" @if($notificationsettings->is_direct_messages == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_direct_messages">Direct messages</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_videos_from_follow" name="is_videos_from_follow" @if($notificationsettings->is_videos_from_follow == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_videos_from_follow">Videos from accounts you follow</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_video_suggestions" name="is_video_suggestions" @if($notificationsettings->is_video_suggestions == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_video_suggestions">Video suggestions</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_livestreams_from_follow" name="is_livestreams_from_follow" @if($notificationsettings->is_livestreams_from_follow == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_livestreams_from_follow">Livestreams from accounts you follow</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_recommended_broadcasts" name="is_recommended_broadcasts" @if($notificationsettings->is_recommended_broadcasts == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_recommended_broadcasts">Recommended broadcasts</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-1">
                                                    <div class="form-check form-check-primary">
                                                        <input type="checkbox" class="form-check-input" id="is_customized_updates" name="is_customized_updates" @if($notificationsettings->is_customized_updates == 1) checked="" @endif>
                                                        <label class="form-check-label" for="is_customized_updates">Customized updates and more</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <input type="hidden" name="safety_id" value="{{ $safety->id }}">
                                                <input type="hidden" name="notificationsettings_id" value="{{ $notificationsettings->id }}">
                                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                                <button type="reset" class="btn btn-outline-secondary reset">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
