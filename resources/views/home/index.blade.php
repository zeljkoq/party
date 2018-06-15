@extends('layouts.app')

@section('content')
    <div id="slideshow">
        <div>
            <img src="{{ asset('images/quantox1.jpg') }}">
        </div>
        <div>
            <img src="{{ asset('images/quantox2.jpg') }}">
        </div>
    </div>
    <br>
    <div class="alert alert-danger" id="errorMess" style="display: none"></div>
    <div id="parties">

    </div>
    <br>
    <div class="row">
        <div class="col-md-6 text-center">
            <br><br>
            Adderss: Kneza Mihaila 112<br><br>
            City: 34000 Kragujevac<br><br>
            Phone: +381 60 000 000 0
        </div>
        <div class="col-md-6 text-center">
            <div class="alert alert-success" id="success" style="display: none"></div>
            <div class="alert alert-danger" id="error" style="display: none"></div>
            <h3>Contact us</h3>
            <form>
                Name: <input type="text" id="name" name="name"><br><br>
                Email: <input type="text" id="email" name="email"><br><br>
                Message: <textarea id="message" name="message"></textarea><br><br>
                <button type="button" id="sendMail">Submit</button>
            </form>
        </div>
    </div>
    <br><br>


    <div id="map"></div>
    @include('layouts._confirmation_modal')
@stop
@section('per_page_scripts')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvXfffc-1y5IA31mA03cfgCvTICaUMTV4&callback=initMap"></script>
    <script>
        $("#slideshow > div:gt(0)").hide();

        setInterval(function () {
            $('#slideshow > div:first')
                .fadeOut(1000)
                .next()
                .fadeIn(1000)
                .end()
                .appendTo('#slideshow');
        }, 3000);
    </script>
    <script>
        function initMap() {
            var uluru = {lat: 44.005352, lng: 20.9012218};
            var map = new google.maps.Map(
                document.getElementById('map'), {zoom: 17, center: uluru});
            var marker = new google.maps.Marker({position: uluru, map: map});
        }
    </script>
    <script>
        function fillTable(parties) {
            var html = '';
            for (i = 0; i < parties.length; i++) {
                if (i == 0 || ((i / 3) % 1) == 0) {
                    html += '<div class="row">';
                }

                html += '<div class="col-md-4">';
                html += '<div class="thumbnail">';
                if(parties[i].registered) {
                    html += '<div id="registered_'+parties[i].id+'" class="alert alert-success" style="color:green">You are registered on this party</div>';
                } else {
                    html += '<div id="registered_'+parties[i].id+'" class="alert alert-success" style="color:green;display:none">You are registered on this party</div>';
                }
                html += '<img src="/storage/' + parties[i].cover_photo + '">';
                html += '<div class="caption">';
                html += '<h3>' + parties[i].name + '</h3>';
                html += '<p>';
                html += 'Date: ' + parties[i].date + '<br>';
                html += 'Duration: ' + parties[i].duration + '<br>';
                html += 'Capacity: ' + parties[i].capacity + '<br>';
                html += '</p>';
                if(!parties[i].start) {
                    if (parties[i].registered) {
                        html += '<p><button id="singOut" onclick="confModal(\'Are you sure you want to sing out from this party?\', \'Sing out\', \'' + parties[i].sing_out_link + '\')" class="btn btn-primary">Sing out</button>';
                        if (!parties[i].filled) {
                            html += '<p><button id="singUp" onclick="confModal(\'Are you sure you want to sing up for this party?\', \'Sing up\', \'' + parties[i].sing_up_link + '\')" class="btn btn-primary" style="display: none">Sing up</button>';
                        }
                    } else {
                        if (!parties[i].filled) {
                            html += '<p><button id="singUp" onclick="confModal(\'Are you sure you want to sing up for this party?\', \'Sing up\', \'' + parties[i].sing_up_link + '\')" class="btn btn-primary" role="button">Sing up</button>';
                        }
                        html += '<p><button id="singOut" onclick="confModal(\'Are you sure you want to sing out from this party?\', \'Sing out\', \'' + parties[i].sing_out_link + '\')" class="btn btn-primary" style="display: none">Sing out</button>';
                    }
                }
                html += '</p>';
                html += '</div>';
                html += '</div>';
                html += '</div>';

                if ((((i + 1) / 3) % 1) == 0 || i == parties.length - 1) {
                    html += '</div>';
                }
            }
            return html;
        }

        if (checkInStorage('Authorization')) {
            $(document).ready(function () {
                $.ajax({
                    url: '{{ route('home.index') }}',
                    headers: {
                        "Authorization": getFromStorage('Authorization')
                    },
                    success: function (data) {
                        $('#parties').html(fillTable(data.data));
                    },
                    error: function (data) {
                        if (data.statusText == "Unauthorized") {
                            logoutUser();
                            window.location = "/";
                        }
                    }
                });
            });
        }else{
            $(document).ready(function () {
                $.ajax({
                    url: '{{ route('home.parties') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#parties').html(fillTable(data.data));
                    }
                });
            });
        }

        $('#sendMail').on('click', function () {
            var name = $('#name').val();
            var email = $('#email').val();
            var message = $('#message').val();
            $('#success').css('display', 'none');
            $('#error').css('display', 'none');
            $.ajax({
                url: '{{ route('send.mail') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: ({name: name, email: email, message: message}),
                success: function (data) {
                    if (typeof data.success !== 'undefined') {
                        $('#success').text(data.success);
                        $('#success').css('display', 'block');
                    }
                    if (typeof data.error !== 'undefined') {
                        $('#error').text(data.error);
                        $('#error').css('display', 'block');
                    }
                }
            });
        });

        function confModal(message, button, url) {
            $('#conf-message').text(message);
            $('#button').text(button);
            $('#confirmationModal').css('display', 'block');
            $('#button').attr('onclick', 'singUp(\'' + url + '\')');
        }

        function singUp(url) {
            $('#confirmationModal').css('display', 'none');
            $('#successMess').css('display', 'none');
            $('#errorMess').css('display', 'none');
            $.ajax({
                url: url,
                headers: {
                    "Authorization": getFromStorage('Authorization')
                },
                type: 'GET',
                success: function (data) {
                    if (typeof data.success !== 'undefined') {
                        if (data.success) {
                            $('#registered_' + data.id).css('display', 'block');
                            $('#singOut').css('display', 'block');
                            $('#singUp').css('display', 'none');
                        } else {
                            $('#registered_' + data.id).css('display', 'none');
                            $('#singUp').css('display', 'block');
                            $('#singOut').css('display', 'none');
                        }
                    }
                    if (typeof data.error !== 'undefined') {
                        $('#errorMess').text(data.error);
                        $('#errorMess').css('display', 'block');
                    }
                },
                error: function (data) {
                    if (data.statusText == "Unauthorized") {
                        logoutUser();
                        window.location = "/";
                    }
                }
            });
        }
    </script>
@stop