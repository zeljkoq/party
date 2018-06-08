@extends('admin.layouts.app')

@section('content')
    <div id="success" style="display: none" class="alert alert-success"></div>
    <div id="error" style="display: none" class="alert alert-danger"></div>
    <div class="box">
        <h3>Add a party</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <label>Name</label>
            <input type="text" name="name" id="name"/>
            <p id="nameError" style="display: none"></p><br><br>
            <label>Date</label>
            <input type="text" class="date" name="date" id="date"/>
            <p id="dateError" style="display: none"></p><br><br>
            <label>Duration</label>
            <input type="text" name="duration" id="duration"/>
            <p id="durationError" style="display: none"></p><br><br>
            <label>Capacity</label>
            <input type="text" name="capacity" id="capacity"/>
            <p id="capacityError" style="display: none"></p><br><br>
            <label>Description</label>
            <input type="text" name="description" id="description"/>
            <p id="descriptionError" style="display: none"></p><br><br>
            <label>Tags</label>
            <select name="tags[]" id="selectBody" class="select2" style="min-width:300px" multiple>
            </select>
            <p id="tagsError" style="display: none"></p><br><br>
            <label>Cover Photo</label>
            <input type="file" name="cover_photo" id="cover_photo"/>
            <p id="coverPhotoError" style="display: none"></p><br><br>
            <button type="button" id="submitAddSong">Submit</button><br><br>
        </form>
    </div>
    <div class="box">
        <h3>List of songs</h3>
        <table id="dataTable">
            <thead style="background-color: #ddd; font-weight: bold;">
            <tr>
                <td>Id</td>
                <td>Name</td>
                <td>Date</td>
                <td>Duration</td>
                <td>Capacity</td>
                <td>Description</td>
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
            if (typeof data.date !== 'undefined') {
                $('#dateError').text(data.date);
                $('#dateError').css('display', 'block');
            }
            if (typeof data.duration !== 'undefined') {
                $('#durationError').text(data.duration);
                $('#durationError').css('display', 'block');
            }
            if (typeof data.capacity !== 'undefined') {
                $('#capacityError').text(data.capacity);
                $('#capacityError').css('display', 'block');
            }
            if (typeof data.description !== 'undefined') {
                $('#descriptionError').text(data.description);
                $('#descriptionError').css('display', 'block');
            }
            if (typeof data.tags !== 'undefined') {
                $('#tagsError').text(data.tags);
                $('#tagsError').css('display', 'block');
            }
            if (typeof data.cover_photo !== 'undefined') {
                $('#coverPhotoError').text(data.cover_photo);
                $('#coverPhotoError').css('display', 'block');
            }
        }
        function hidePageMessages(){
            hideMessages();
            $('#nameError').css('display', 'none');
            $('#dateError').css('display', 'none');
            $('#durationError').css('display', 'none');
            $('#capacityError').css('display', 'none');
            $('#descriptionError').css('display', 'none');
            $('#tagsError').css('display', 'none');
            $('#coverPhotoError').css('display', 'none');
        }
    </script>
    <script>
        function fillTable(parties){
            var html = '';
            for (i=0;i<parties.length;i++){
                html += '<tr>';
                html += '<td>' + parties[i].id + '</td>';
                html += '<td>' + parties[i].name + '</td>';
                html += '<td>' + parties[i].date + '</td>';
                html += '<td>' + parties[i].duration + '</td>';
                html += '<td>' + parties[i].capacity + '</td>';
                html += '<td>' + parties[i].description + '</td>';
                html += '<td><a href="'+parties[i].edit_link+'">edit</a></td>';
                html += '<td><a href="javascript:void(0)" onclick="confirmationModal(\'Are you sure you want to delete this party?\', \'Delete\', \''+parties[i].delete_link+'\')">delete</a></td>';
                html += '</tr>';
            }
            return html;
        }

        function fillSelect(tags){
            var html = '';
            for (i=0;i<tags.length;i++){
                html += '<option value="'+ tags[i].id+'">' + tags[i].name + '</option>';
            }
            return html;
        }

        $(document).ready(function() {
            if(!checkInStorage("Authorization")) {
                return false;
            }
            $.ajax({
                url: '{{ route('admin.parties.index') }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function (data) {
                    html = fillTable(data.parties);
                    $('#tableBody').html(html);
                    html = fillSelect(data.tags);
                    $('#selectBody').html(html);
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
            var formData = new FormData(document.forms[0]);
            formData.append('cover_photo', $('#cover_photo')[0].files[0]);
            $.ajax({
                url: '{{ route('admin.parties.store') }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                type: 'POST',
                contentType: false,
                processData: false,
                data: formData,
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