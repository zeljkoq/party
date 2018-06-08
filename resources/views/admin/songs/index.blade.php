@extends('admin.layouts.app')

@section('content')
    <div id="success" style="display: none" class="alert alert-success"></div>
    <div id="error" style="display: none" class="alert alert-danger"></div>
    <div class="box">
        <h3>Add a song</h3>
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
            <button type="button" id="submitAddSong">Submit</button>
        </form>
    </div>
    <div class="box">
        <h3>List of songs</h3>
        <table id="dataTable">
            <thead style="background-color: #ddd; font-weight: bold;">
            <tr>
                <td>Id</td>
                <td>Name</td>
                <td>Author</td>
                <td>Link</td>
                <td>Duration</td>
                <td>Edit</td>
                <td>Delete</td>
            </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
        <div id="pagination"></div>
    </div>
    @include('layouts._unauthorize_modal')
    @include('layouts._confirmation_modal')
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
        function fillTable(songs){
            var html = '';
            for (i=0;i<songs.length;i++){
                html += '<tr>';
                html += '<td>' + songs[i].id + '</td>';
                html += '<td>' + songs[i].name + '</td>';
                html += '<td>' + songs[i].author + '</td>';
                html += '<td><a target="_blank" href="' + songs[i].link + '">' + songs[i].link + '</a></td>';
                html += '<td>' + songs[i].duration + ' minute/s</td>';
                html += '<td><a href="'+songs[i].edit_link+'">edit</a></td>';
                html += '<td><a href="javascript:void(0)" onclick="confirmationModal(\'Are you sure you want to delete this song?\', \'Delete\', \''+songs[i].delete_link+'\')">delete</a></td>';
                html += '</tr>';
            }
            return html;
        }

        $(document).ready(function() {
            if(!checkInStorage("Authorization")) {
                return false;
            }
            $.ajax({
                url: '{{ route('admin.songs.index') }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function (data) {
                    html = fillTable(data.data);
                    $('#tableBody').html(html);
                    $('#dataTable').each(function() {
                        dt = $(this).dataTable();
                        dt.fnDraw();
                    });
                },
                error: function (data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                }
            });
        });

        $('#submitAddSong').on('click', function(){
            hidePageMessages();
            var name = $('#name').val();
            var author = $('#author').val();
            var link = $('#link').val();
            var duration = $('#duration').val();
            $.ajax({
                url: '{{ route('admin.songs.store') }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                type: 'POST',
                data: ({name: name, author: author, link: link, duration: duration}),
                success: function(data) {
                    showPageMessages(data);
                    html = fillTable([data.data]);
                    $('#tableBody').prepend(html);
                },
                error: function(data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                    showPageMessages(data.responseJSON.errors);
                }
            });
        });

        function confirmationModal(message, button, url) {
            $('#conf-message').text(message);
            $('#button').text(button);
            $('#confirmationModal').css('display', 'block');
            $('#button').on('click', function(){
                $('#confirmationModal').css('display', 'none');
                hidePageMessages();
                $.ajax({
                    url: url,
                    headers: {
                        "Authorization":getFromStorage('Authorization')
                    },
                    type: 'DELETE',
                    success: function(data) {
                        if (typeof data.id !== 'undefined') {
                            $('td:contains("'+data.id+'")').parent().css('display', 'none');
                        }
                        showPageMessages(data);
                    },
                    error: function (data) {
                        if(data.statusText == "Unauthorized"){
                            window.location = "/";
                        }
                    }
                });
            });
        }
    </script>
@stop