@extends('layouts.template')

@section('nav_class', 'navbar-light')

@section('nav')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('songs') }}">Songs</a>
    </li>
    <li class="nav-item" id="navParties">
        <a class="nav-link" href="{{ route('parties') }}">My Parties</a>
    </li>
    <li class="nav-item" id="navLogin">
        <a class="nav-link" href="{{ route('loginForm') }}">Login</a>
    </li>
    <li class="nav-item" id="navRegister">
        <a class="nav-link" href="{{ route('registerForm') }}">Register</a>
    </li>
    <li class="nav-item" id="navAdmin">
        <a class="nav-link" href="{{ route('admin.home') }}">Admin</a>
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
            $('#navParties').css('display', 'none')
            $('#navAdmin').css('display', 'none')
            $('#navLogout').css('display', 'none')
        }
    </script>
@stop

{{--postman--}}