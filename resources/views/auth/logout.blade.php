@extends('layouts.app')
@section('per_page_scripts')
    <script>
        logoutUser();
        window.location = "/";
    </script>
@stop
