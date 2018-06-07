@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>
                                <p id="usernameError"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                <p id="passwordError"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <p id="passwordConfirmError"></p>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" id="register" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('per_page_scripts')
    <script>
        function showPageMessages(data){
            showMessages(data);
            if (typeof data.username !== 'undefined') {
                $('#usernameError').text(data.username);
                $('#usernameError').css('display', 'block');
            }
            if (typeof data.password !== 'undefined') {
                $('#passwordError').text(data.password);
                $('#passwordError').css('display', 'block');
            }
            if (typeof data.passwordConfirm !== 'undefined') {
                $('#passwordConfirmError').text(data.passwordConfirm);
                $('#passwordConfirmError').css('display', 'block');
            }
        }
        function hidePageMessages(){
            hideMessages();
            $('#usernameError').css('display', 'none');
            $('#passwordError').css('display', 'none');
            $('#passwordConfirmError').css('display', 'none');
        }
    </script>
    <script>
        $('#register').on('click', function(){
            hidePageMessages();
            var username = $('#username').val();
            var password = $('#password').val();
            var passwordConfirm = $('#password-confirm').val();
            $.ajax({
                url: '{{ route('register') }}',
                type: 'POST',
                data: ({username: username, password: password, passwordConfirm: passwordConfirm}),
                success: function(data) {
                    addInStorage('Authorization', 'Bearer ' + data.access_token);
                    window.location = "{{ route('home') }}";
                },
                error: function(data) {
                    showPageMessages(data.responseJSON.errors);
                }
            });
        });
    </script>
@stop
