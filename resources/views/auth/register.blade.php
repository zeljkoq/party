@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h3>Register</h3>
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

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">User role</label>

                            <div class="col-md-6">
                                <select name="user_role" id="roles"></select>
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
        function getElementsForMessages(){
            return [
                'error', 'success', 'username', 'password', 'passwordConfirm'
            ]
        }

        function fillSelect(roles){
            var html = '';
            for (i=0;i<roles.length;i++){
                html += '<option value="'+ roles[i].id+'">' + roles[i].name + '</option>';
            }
            return html;
        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('register.roles') }}',
                success: function(data) {
                    $('#roles').html(fillSelect(data.data));
                }
            });
        });

        $('#register').on('click', function(){
            hideMessages(getElementsForMessages());
            var username = $('#username').val();
            var password = $('#password').val();
            var passwordConfirm = $('#password-confirm').val();
            var userRole = $('#roles').val();
            $.ajax({
                url: '{{ route('register') }}',
                type: 'POST',
                data: ({username: username, password: password, passwordConfirm: passwordConfirm, userRole: userRole}),
                success: function(data) {
                    addInStorage('Authorization', 'Bearer ' + data.access_token);
                    window.location = "{{ route('home') }}";
                },
                error: function(data) {
                    showMessages(data.responseJSON.errors, getElementsForMessages());
                }
            });
        });
    </script>
@stop
