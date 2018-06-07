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
@stop
@section('per_page_scripts')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvXfffc-1y5IA31mA03cfgCvTICaUMTV4&callback=initMap"></script>
    <script>
        $("#slideshow > div:gt(0)").hide();

        setInterval(function() {
            $('#slideshow > div:first')
                .fadeOut(1000)
                .next()
                .fadeIn(1000)
                .end()
                .appendTo('#slideshow');
        },  3000);
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
        $('#sendMail').on('click', function(){
            var name = $('#name').val();
            var email = $('#email').val();
            var message = $('#message').val();
            $('#success').css('display', 'none');
            $('#error').css('display', 'none');
            $.ajax({
                url: '{{ route('send_mail') }}',
                type: 'POST',
                data: ({name: name, email: email, message: message}),
                success: function(data) {
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
    </script>
@stop