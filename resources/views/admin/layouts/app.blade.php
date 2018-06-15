@extends('layouts.template')

@section('nav_class', 'navbar-inverse')

@section('nav')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.home') }}">Home</a>
    </li>
    <li class="nav-item" id="songs">
        <a class="nav-link" href="{{ route('admin.songs') }}">Songs</a>
    </li>
    <li class="nav-item" id="parties">
        <a class="nav-link" href="{{ route('admin.parties') }}">Parties</a>
    </li>
    <li class="nav-item" id="navAdmin">
        <a class="nav-link" href="{{ route('home') }}">Back to Site</a>
    </li>
    <li class="nav-item" id="navLogout">
        <a class="nav-link" href="javascript:void(0)" onclick="logoutUser()">Logout</a>
    </li>
@stop

@section('app')
    @yield('content')
@stop

@section('nav_scripts')
    <script>
        if(checkInStorage('Authorization')){
            $('#navLogin').css('display', 'none')
            $('#navRegister').css('display', 'none')
        }
        if(!checkInStorage('Authorization')){
            $('#navLogout').css('display', 'none')
            $('#navAdmin').css('display', 'none')
        }
    </script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: '{{ route('home.routes') }}',
                headers: {
                    "Authorization": getFromStorage('Authorization')
                },
                success: function (data) {
                    if(!data.includes('Admin') && !data.includes('DJ')) {
                        $('#songs').css('display', 'none')
                    }
                    if(!data.includes('Admin') && !data.includes('Party Maker')) {
                        $('#parties').css('display', 'none')
                    }
                },
                error: function (data) {
                    if (data.status == "401") {
                        logoutUser();
                        window.location = "/";
                    }
                }
            });
        });
    </script>
@stop