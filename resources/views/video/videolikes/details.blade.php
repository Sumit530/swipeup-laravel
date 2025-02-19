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
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Video List</h4>
                                </div>
                                <div class="card-body">
                                    <table class="datatables-basic table" id="datatables">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Cover Image</th>
                                                <th>Video Url</th>
                                                <th>Total Likes</th>
                                                <th>Total Comments</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($video_list))
                                                @foreach($video_list as $k => $row)
                                                    @php 
                                                    //cover image
                                                    if ($row->cover_image != '') 
                                                    {
                                                        $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                                                        if(File::exists($deldestinationPath.'/'.$row->cover_image)) {
                                                            $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$row->cover_image);
                                                        }
                                                        else
                                                        {
                                                            $cover_image = "";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $cover_image = "";
                                                    }

                                                    // video
                                                    if ($row->file_name != '') 
                                                    {
                                                       $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                                                        if(File::exists($delddestinationPath.'/'.$row->file_name)) {
                                                            $video_url = url('storage/app/public/uploads/videos/videos/'.$row->file_name);
                                                        }
                                                        else
                                                        {
                                                            $video_url = "";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $video_url = "";
                                                    }
                                                    @endphp
                                                <tr>
                                                    <td>{{ $k+1 }}</td>
                                                    <td><a href="{{ $cover_image }}" target="_blank">{{ $row->cover_image }}</a></td>
                                                    <td><a href="{{ $video_url   }}" target="_blank">{{ $row->file_name }}</a></td>
                                                    <td>{{ $row->total_likes }}</td>
                                                    <td>{{ $row->total_comments }}</td>
                                                    <td>
                                                        @if($row->status == 1)
                                                            <span class="badge rounded-pill badge-light-success me-1">Active</span>
                                                        @else
                                                            <span class="badge rounded-pill badge-light-danger me-1">Deactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                @if($row->status == 1)
                                                                 <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#status-model" data-id="{{ $row->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock me-50"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                                    <span>Status</span>
                                                                </a>
                                                                @else
                                                                 <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#status-model" data-id="{{ $row->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock me-50"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                                                                    <span>Status</span>
                                                                </a>
                                                                @endif
                                                                <a class="dropdown-item" href="#">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                                                    <span> View Likes</span>
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                                    <span> View Comments</span>
                                                                </a>
                                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete-model" data-id="{{ $row->id }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                                    <span>Delete</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
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
