@extends('admin.layouts.app')

@section('content')
    <div id="successMess" style="display: none" class="alert alert-success"></div>
    <div id="errorMess" style="display: none" class="alert alert-danger"></div>
    <div class="box">
        <h3>Add a party</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="name"/>
                <p id="nameMess" style="display: none"></p><br><br>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="text" class="date" name="date" id="date"/>
                <p id="dateMess" style="display: none"></p><br><br>
            </div>
            <div class="form-group">
                <label>Duration (hours)</label>
                <input type="text" name="duration" id="duration"/>
                <p id="durationMess" style="display: none"></p><br><br>
            </div>
            <div class="form-group">
                <label>Capacity (persons)</label>
                <input type="text" name="capacity" id="capacity"/>
                <p id="capacityMess" style="display: none"></p><br><br>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" id="description"/>
                <p id="descriptionMess" style="display: none"></p><br><br>
            </div>
            <div class="form-group">
                <label>Tags</label>
                <select name="tags[]" id="tags" class="select2" style="min-width:300px" multiple>
                </select>
                <p id="tagsMess" style="display: none"></p><br><br>
            </div>
            <div class="form-group">
                <label>Cover Photo</label>
                <input type="file" name="cover_photo" id="cover_photo"/>
                <p id="coverPhotoMess" style="display: none"></p><br><br>
            </div>

            <input type="hidden" name="id" id="id">
            <input type="hidden" id="editIndex">
            <input type="hidden" id="deleteIndex">

            <button type="button" id="submitAdd">Create Song</button>
            <button type="button" id="submitEdit" style="display: none;">Edit Song</button>
            <button type="button" id="cancelEdit" style="display: none;" onclick="crud.emptyEditForm()">Cancel</button>
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
                <td>Start</td>
                <td>Details</td>
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
    @include('layouts._details_modal')
@stop
@section('per_page_scripts')
    <script>
        function getElementsForMessages() {
            return [
                'error', 'success', 'name', 'date', 'duration', 'capacity', 'description', 'tags', 'coverPhoto'
            ]
        }

        var fields = [
            'id', 'name', 'date', 'duration', 'capacity', 'description'
        ];

        var allFormFields = [
            'name', 'date', 'duration', 'capacity', 'description', 'tags', 'cover_photo'
        ];

        var links = {
            get: '{{ route('admin.parties.index') }}',
            create: '{{ route('admin.parties.store') }}',
            update: '{{ route('admin.parties.update') }}',
        };

        function generateRow(model, index) {
            var html = '';

            html += '<tr>';
            html += '<td>' + model.id + '</td>';
            html += '<td>' + model.name + '</td>';
            html += '<td>' + model.date + '</td>';
            html += '<td>' + model.duration + ' hour/s</td>';
            html += '<td>' + model.capacity + ' persons</td>';
            html += '<td>';
            if (model.description) {
                html += model.description;
            }
            html += '</td>';
            html += '<td>';
            if (!model.start) {
                html += '<a id="start" href="javascript:void(0)" onclick="confModal(\'Are you sure you want to start this party?\', \'Start\', \'' + model.start_link + '\')">start</a>';
            }
            html += '</td>';
            html += '<td>';
            if (model.start) {
                html += '<a id="details" href="javascript:void(0)" onclick="details(\'' + model.details_link + '\')">details</a>';
            } else {
                html += '<a id="details" style="display: none" href="javascript:void(0)" onclick="details(\'' + model.details_link + '\')">details</a>';
            }
            html += '</td>';
            html += '<td><a href="javascript:void(0)" onclick="crud.fillEditForm(' + index + ')">edit</a></td>';
            html += '<td><a href="javascript:void(0)" onclick="deleteModal(\'Are you sure you want to delete this party?\', \'Delete\', \'' + model.delete_link + '\')">delete</a></td>';
            html += '</tr>';

            return html;
        }

        function onLoad(data) {
            $('#tags').html(fillSelect(data.tags));
        }

        var formFiles = ['cover_photo'];

        var hideElements = ['cover_photo', 'tags'];

        function fillSelect(tags) {
            var html = '';
            for (i = 0; i < tags.length; i++) {
                html += '<option value="' + tags[i].id + '">' + tags[i].name + '</option>';
            }
            return html;
        }

        $(document).ready(function () {
            if (!checkInStorage("Authorization")) {
                return false;
            }
            crud.getAll();
        });

        function confModal(message, button, url) {
            $('#conf-message').text(message);
            $('#button').text(button);
            $('#confirmationModal').css('display', 'block');
            $('#button').attr('onclick', 'startParty(\'' + url + '\')');
        }

        function startParty(url) {
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
                        $('#success').text(data.success);
                        $('#success').css('display', 'block');
                        $('#start').css('display', 'none');
                        $('#details').css('display', 'block');
                    }
                    if (typeof data.error !== 'undefined') {
                        $('#errorMess').text(data.error);
                        $('#errorMess').css('display', 'block');
                    }
                }
            });
        }

        function details(url) {
            $.ajax({
                url: url,
                headers: {
                    "Authorization": getFromStorage('Authorization')
                },
                type: 'GET',
                success: function (data) {
                    var html = '';
                    for (i = 0;i < data.length; i++) {
                        html += '<tr>';
                        html += '<td>' + data[i].name + '</td>';
                        html += '<td>' + data[i].author + '</td>';
                        html += '<td>' + data[i].link + '</td>';
                        html += '<td>' + data[i].duration + '</td>';
                        html += '<td>' + data[i].name + '</td>';
                        html += '</tr>';
                    }
                    $('#detailsModal').css('display', 'block');
                    $('#detailsBody').html(html);
                }
            });
        }
    </script>
@stop