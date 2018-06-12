@extends('admin.layouts.app')

@section('content')
    <div id="successMess" style="display: none" class="alert alert-success"></div>
    <div id="errorMess" style="display: none" class="alert alert-danger"></div>
    <div class="box">
        <h3>Add a song</h3>
        <form>
            <label>Name</label>
            <input type="text" name="name" id="name"/>
            <p id="nameMess" style="display: none"></p><br><br>

            <label>Author</label>
            <input type="text" name="author" id="author"/>
            <p id="authorMess" style="display: none"></p><br><br>

            <label>Link</label>
            <input type="text" name="link" id="link"/>
            <p id="linkMess" style="display: none"></p><br><br>

            <label>Duration (minutes)</label>
            <input type="text" name="duration" id="duration"/>
            <p id="durationMess" style="display: none"></p><br><br>

            <input type="hidden" name="id" id="id">
            <input type="hidden" id="editIndex">
            <input type="hidden" id="deleteIndex">

            <button type="button" id="submitAdd">Create Song</button>
            <button type="button" id="submitEdit" style="display: none;">Edit Song</button>
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
        function getElementsForMessages() {
            return [
                'error', 'success', 'name', 'author', 'link', 'duration'
            ]
        }

        var fields = [
            'id', 'name', 'author', 'link', 'duration'
        ];

        var links = {
            get: '{{ route('admin.songs.index') }}',
            create: '{{ route('admin.songs.store') }}',
            update: '{{ route('admin.songs.update') }}',
        };

        function generateRow(model, index) {
            var html = '';

            html += '<tr>';
            html += '<td>' + model.id + '</td>';
            html += '<td>' + model.name + '</td>';
            html += '<td>' + model.author + '</td>';
            html += '<td><a target="_blank" href="' + model.link + '">' + model.link + '</a></td>';
            html += '<td>' + model.duration + ' minute/s</td>';
            html += '<td><a href="javascript:void(0)" onclick="crud.fillEditForm(' + index + ')">edit</a></td>';
            html += '<td><a href="javascript:void(0)" onclick="deleteModal(\'Are you sure you want to delete this song?\', \'Delete\', \'' + model.delete_link + '\', ' + index + ')">delete</a></td>';
            html += '</tr>';

            return html;
        }

        function onLoad(data) {
        }

        var formFiles = [];

        var hideElements = [];
    </script>
@stop