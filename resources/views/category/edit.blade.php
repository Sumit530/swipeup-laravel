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
                            <h2 class="content-header-title float-start mb-0">Update category details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('category.index') }}">category</a>
                                    </li>
                                    <li class="breadcrumb-item active">update category details
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <a href="{{ route('category.index') }}" class="btn-icon btn btn-primary btn-round btn-sm" type="button" title=""  data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Back">
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
                                    <h4 class="card-title">Update category details</h4>
                                </div>
                                <div class="card-body">
                                    @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">
                                        <div class="alert-body"><strong>Error!</strong> {{ $error }}</div>
                                    </div>
                                    @endforeach
                                    <form class="form" id="form" action="{{ route('category.update',array($category->id)) }}" method="POST">
                                        {{ method_field('PUT') }}
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Category Name <span class="required_sign">*</span></label>
                                                    <input type="text" id="name" class="form-control" placeholder="Enter name" name="name" value="{{ isset($category->name) ? $category->name : old('name') }}"/>
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
