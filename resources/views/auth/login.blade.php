@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="username" class="col-sm-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="button" id="login" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="alert alert-danger" id="error" style="display: none"></div>
                        <div class="alert alert-success" id="success" style="display: none"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('per_page_scripts')
    <script>
        $('#login').on('click', function(){
            hideMessages();
            var username = $('#username').val();
            var password = $('#password').val();
            $.ajax({
                url: '{{ route('login') }}',
                type: 'POST',
                data: ({username: username, password: password}),
                success: function(data) {
                    addInStorage('Authorization', 'Bearer ' + data.access_token);
                    window.location = "{{ route('home') }}";
                },
                error: function(data) {
                    showMessages(data.responseJSON);
                }
            });
        });
    </script>
@stop
