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
                            <h2 class="content-header-title float-start mb-0">Update song details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('songs.index') }}">songs</a>
                                    </li>
                                    <li class="breadcrumb-item active">update song details
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <a href="{{ route('songs.index') }}" class="btn-icon btn btn-primary btn-round btn-sm" type="button" title=""  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Back">
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
                                    <h4 class="card-title">Update song details</h4>
                                </div>
                                <div class="card-body">
                                    @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">
                                        <div class="alert-body"><strong>Error!</strong> {{ $error }}</div>
                                    </div>
                                    @endforeach
                                    <form class="form" id="form" action="{{ route('songs.update',array($songs->id)) }}" method="POST" enctype="multipart/form-data">
                                        {{ method_field('PUT') }}
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Song Name <span class="required_sign">*</span></label>
                                                    <select class="form-control" name="cat_id">
                                                        <option value="">-- Select category name --</option>
                                                        @if(count($categories) > 0)
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" @if($cat->id == $songs->cat_id) selected @endif>{{ $cat->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Singer Name </label>
                                                    <select class="form-control" name="singer_id">
                                                        <option value="">-- Select singer name --</option>
                                                        @if(count($singers) > 0)
                                                            @foreach($singers as $singer)
                                                                <option value="{{ $singer->id }}">{{ $singer->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="duration">Song Duration </label>
                                                    <input type="text" class="form-control" placeholder="Enter duration" name="duration" id="duration" value="{{ isset($songs->duration) ? $songs->duration : old('duration') }}" readonly disabled />
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Song Name <span class="required_sign">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Enter name" name="name" id="name" value="{{ isset($songs->name) ? $songs->name : old('name') }}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="description">Song Description </label>
                                                    <textarea class="form-control" id="description" name="description">{{ isset($songs->description) ? $songs->description : old('description') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="banner_image">Song Banner image </label>
                                                    <input type="file" class="form-control" placeholder="Select banner image" name="banner_image" id="banner_image" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="attachment">Song File </label>
                                                    <input type="file" class="form-control" placeholder="Select song file" name="attachment" id="attachment" />
                                                </div>
                                            </div>
                                            <div class="col-12">
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
