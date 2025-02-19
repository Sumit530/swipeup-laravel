<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="#" />
    <meta name="keywords" content="#" />
    <meta name="robots" content="index,follow" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Swipe up - @yield('title')</title>
    <link rel="apple-touch-icon" href="{{ asset('public/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/vendors.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/extensions/toastr.min.css') }}"> -->
    <!-- END: Vendor CSS-->
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}"> -->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/authentication.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/charts/chart-apex.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/dashboard-ecommerce.css') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/app-invoice-list.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/forms/form-validation.css') }}">
    <!-- END: Page CSS-->

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
    
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css') }}">
    <!-- END: Custom CSS-->

    <style type="text/css">
        /*datable css start*/
        #datatables_length{
            margin-left: 10px !important;
        }
        #datatables_filter{
            margin-right: 10px !important;
        }
        #datatables_info{
            margin-left: 10px !important;
        }
        #datatables_paginate{
            margin-right: 10px !important;
        }
        /*datatable css end*/
        /*required sign css*/
        .error
        {
            color: red;
        }
        .required_sign
        {
            color: red;
        }
        /*end required sign css*/
    </style>
    
</head>
@if(isset(Auth::user()->id))
@php
    $class_name = "pace-done vertical-layout vertical-menu-modern navbar-floating footer-static menu-expanded"
@endphp
@else
@php
    $class_name = "pace-done vertical-layout vertical-menu-modern blank-page navbar-floating footer-static";
@endphp
@endif

<body class="{{ $class_name }}" data-open="click" data-menu="vertical-menu-modern" data-col="">
<!--container-->
<div id="container">
    @if(isset(Auth::user()->id))
        @include('layouts.header')
    @endif

    @yield('content')

    @if(isset(Auth::user()->id))
        @include('layouts.footer')
    @endif
</div>
</body>
</html>
