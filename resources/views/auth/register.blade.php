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
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                <p id="emailMess"></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
                                <p id="nameMess"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                <p id="passwordMess"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <p id="passwordConfirmMess"></p>
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
                'email', 'name', 'password', 'passwordConfirm'
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
            var email = $('#email').val();
            var name = $('#name').val();
            var password = $('#password').val();
            var passwordConfirm = $('#password-confirm').val();
            var userRole = $('#roles').val();
            $.ajax({
                url: '{{ route('register') }}',
                type: 'POST',
                data: ({email: email, name: name, password: password, passwordConfirm: passwordConfirm, userRole: userRole}),
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
