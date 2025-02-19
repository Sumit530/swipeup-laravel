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
                            <h2 class="content-header-title float-start mb-0">Video reports</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">Video reports
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <table class="datatables-basic table" id="datatables">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>User Name</th>
                                            <th>Video</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Attchments</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($videoreports))
                                            @foreach($videoreports as $k => $row)
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td>{{ $row['user_name'] }}</td>
                                                <td>{{ $row['video_description'] }}</td>
                                                <td>
                                                    @if($row['type'] == 1)
                                                    Report account
                                                    @else
                                                    Report content
                                                    @endif
                                                </td>
                                                <td>{{ strip_tags(mb_strimwidth($row['description'], 0, 100,'...')) }}</td>
                                                <td>
                                                    @if(count($row['attchments']) > 0){
                                                        @foreach($row['attchments'] as $k => $val)
                                                            <a href="{{ $val['file_url'] }}" target="_blank">{{ $val['file_name'] }}</a><br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($row['status'] == 1)
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
                                                            @if($row['status'] == 1)
                                                             <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#status-model" data-id="{{ $row['id'] }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock me-50"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                                                <span>Status</span>
                                                            </a>
                                                            @else
                                                             <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#status-model" data-id="{{  $row['id'] }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock me-50"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                                                                <span>Status</span>
                                                            </a>
                                                            @endif
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete-model" data-id="{{  $row['id'] }}">
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
                </section>
                <!--/ Basic table -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Status Modal -->
    <div class="modal fade text-start" id="status-model" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Change status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form" id="status_model" action="{{ route('admin.videoreportreqest-status') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>
                        Are you sure want to change this help center status..?
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id">
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
                    <h4 class="modal-title" id="myModalLabel1">Delete Report</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form" id="delete_model" action="{{ route('admin.videoreportreqest-delete') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>
                        Are you sure want to delete this report..?
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-outline-primary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
