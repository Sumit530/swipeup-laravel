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
                            <h2 class="content-header-title float-start mb-0">Settings</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    </li>
                                    <li class="breadcrumb-item active"> settings
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <!-- profile -->
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Settings Details</h4>
                            </div>
                            <div class="card-body py-2 my-25">
                                <!-- form -->
                                <form class="validate-form" action="{{ route('admin.setting-update') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="name">Terms of use</label>
                                            <textarea class="form-control" id="terms_of_use" name="terms_of_use">{{ $setting_details->terms_of_use }}</textarea>
                                        </div>
                                        <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="privacy_policy">Privacy policy</label>
                                            <textarea class="form-control" id="privacy_policy" name="privacy_policy">{{ $setting_details->privacy_policy }}</textarea>
                                        </div>
                                        <div class="col-12 col-sm-12 mb-1">
                                            <label class="form-label" for="copyright_policy">Copyright policy</label>
                                            <textarea class="form-control" id="copyright_policy" name="copyright_policy">{{ $setting_details->copyright_policy }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                                            <button type="reset" class="btn btn-outline-secondary mt-1 reset">Discard</button>
                                        </div>
                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace("terms_of_use");
        CKEDITOR.replace("privacy_policy");
        CKEDITOR.replace("copyright_policy");
    </script>
@endsection