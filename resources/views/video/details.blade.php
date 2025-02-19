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
                            <h2 class="content-header-title float-start mb-0">Video details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('videos.index') }}">videos</a>
                                    </li>
                                    <li class="breadcrumb-item active">videos details
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <a href="{{ route('videos.index') }}" class="btn-icon btn btn-primary btn-round btn-sm" type="button" title=""  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Back">
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
                                    <h4 class="card-title">Video Details</h4>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><b>User Name </b> : {{ $video->user_name }}({{ $video->user_username }})</p>
                                    <p class="card-text"><b>Description </b> :
                                        {{ $video->description }}
                                    </p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="card-text"><b>Total Likes </b> : {{ $video->total_likes }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="card-text"><b>Total Comments </b> : {{ $video->total_comments }}</p>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="card-text"><b>Upload Date & Time </b> : {{ date("d-m-Y h:i A",strtotime($video->created_at)) }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="card-text"><b>View Type </b> : 
                                            @if($video->is_view == 1)
                                            Public
                                            @elseif($video->is_view == 2)
                                            Friends
                                            @elseif($video->is_view == 3)
                                            Private
                                            @else
                                            -
                                            @endif
                                            </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="card-text"><b>Allow comments </b> : 
                                            @if($video->is_allow_comments == 1)
                                            Yes
                                            @else
                                            No
                                            @endif
                                            </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="card-text"><b>Allow Duet </b> :
                                            @if($video->is_allow_duet == 1)
                                            Yes
                                            @else
                                            No
                                            @endif
                                            </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="card-text"><b>Save to device </b> :
                                            @if($video->is_save_to_device == 1)
                                            Yes
                                            @else
                                            No
                                            @endif 
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Status Modal -->
    <div class="modal fade text-start" id="status-model" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Change video status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form" id="status_model" action="{{ route('admin.videos-data-status') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>
                        Are you sure want to change this video status..?
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id">
                    <input type="hidden" name="video_id" value="{{ $video->id }}">
                    <button type="button" class="btn btn-outline-primary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade text-start" id="delete-model" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Delete video</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form" id="delete_model" action="{{ route('admin.videos-data-delete') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>
                        Are you sure want to delete this video..?
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id">
                    <input type="hidden" name="video_id" value="{{ $video->id }}">
                    <button type="button" class="btn btn-outline-primary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
