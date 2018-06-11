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
        function getElementsForMessages(){
            return [
                'error', 'success', 'name', 'date', 'duration', 'capacity', 'description', 'tags', 'coverPhoto'
            ]
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
                html += '<td>' + parties[i].duration + ' minute/s</td>';
                html += '<td>' + parties[i].capacity + ' persons</td>';
                html += '<td>' + parties[i].description + '</td>';
                html += '<td><a href="'+parties[i].edit_link+'">edit</a></td>';
                html += '<td><a href="javascript:void(0)" onclick="deleteModal(\'Are you sure you want to delete this party?\', \'Delete\', \''+parties[i].delete_link+'\')">delete</a></td>';
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
                    $('#tableBody').html(fillTable(data.parties));
                    $('#selectBody').html(fillSelect(data.tags));
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
            hideMessages(getElementsForMessages());
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
                    showMessages(data, getElementsForMessages());
                    $('#tableBody').prepend(fillTable([data.data]));
                },
                error: function(data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                    showMessages(data.responseJSON.errors, getElementsForMessages());
                }
            });
        });

        function deleteModal(message, button, url) {
            $('#conf-message').text(message);
            $('#button').text(button);
            $('#confirmationModal').css('display', 'block');
            $('#button').on('click', function(){
                $('#confirmationModal').css('display', 'none');
                hideMessages(getElementsForMessages());
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
                        showMessages(data, getElementsForMessages());
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