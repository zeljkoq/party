@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div id="success" style="display: none" class="alert alert-success"></div>
        <div id="error" style="display: none" class="alert alert-danger"></div>
        <div>
            <h3>Edit a song</h3>
            <form>
                <label>Name</label>
                <input type="text" name="name" id="name"/>
                <p id="nameError" style="display: none"></p><br><br>
                <label>Author</label>
                <input type="text" name="author" id="author"/>
                <p id="authorError" style="display: none"></p><br><br>
                <label>Link</label>
                <input type="text" name="link" id="link"/>
                <p id="linkError" style="display: none"></p><br><br>
                <label>Duration</label>
                <input type="text" name="duration" id="duration"/>
                <p id="durationError" style="display: none"></p><br><br>
                <button type="button" id="submitUpdateSong">Submit</button>
            </form>
        </div>
    </div>
@stop
@section('per_page_scripts')
    <script>
        function showPageMessages(data){
            showMessages(data);
            if (typeof data.name !== 'undefined') {
                $('#nameError').text(data.name);
                $('#nameError').css('display', 'block');
            }
            if (typeof data.author !== 'undefined') {
                $('#authorError').text(data.author);
                $('#authorError').css('display', 'block');
            }
            if (typeof data.link !== 'undefined') {
                $('#linkError').text(data.link);
                $('#linkError').css('display', 'block');
            }
            if (typeof data.duration !== 'undefined') {
                $('#durationError').text(data.duration);
                $('#durationError').css('display', 'block');
            }
        }
        function hidePageMessages(){
            hideMessages();
            $('#nameError').css('display', 'none');
            $('#authorError').css('display', 'none');
            $('#linkError').css('display', 'none');
            $('#durationError').css('display', 'none');
        }
    </script>
    <script>
        function fillForm(song){
            $('#name').val(song.name);
            $('#author').val(song.author);
            $('#link').val(song.link);
            $('#duration').val(song.duration);
        }

        $(document).ready(function() {
            if(!checkInStorage("Authorization")) {
                return false;
            }
            $.ajax({
                url: '{{ route('admin.songs.show', ['song_id' => $song_id]) }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function(data) {
                    showPageMessages(data);
                    fillForm(data.data);
                },
                error: function (data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                }
            });
        });

        $('#submitUpdateSong').on('click', function(){
            hidePageMessages();
            var name = $('#name').val();
            var author = $('#author').val();
            var link = $('#link').val();
            var duration = $('#duration').val();
            $.ajax({
                url: '{{ route('admin.songs.update', ['song_id' => $song_id]) }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                type: 'PUT',
                data: ({name: name, author: author, link: link, duration: duration}),
                success: function(data) {
                    showPageMessages(data);
                    fillForm(data.data);
                },
                error: function(data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                    showPageMessages(data.responseJSON.errors);
                }
            });
        });
    </script>
@stop