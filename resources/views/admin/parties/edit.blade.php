@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div id="success" style="display: none" class="alert alert-success"></div>
        <div id="error" style="display: none" class="alert alert-danger"></div>
        <div>
            <h3>Edit a song</h3>
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
                <select name="tags[]" id="tags" class="select2" style="min-width:300px" multiple>
                </select><br><br>
                <button type="button" id="submitUpdateSong">Submit</button><br><br>
            </form>
        </div>
    </div>
@stop
@section('per_page_scripts')
    <script>
        function getElementsForMessages(){
            return [
                'error', 'success', 'name', 'date', 'duration', 'capacity', 'description'
            ]
        }
    </script>
    <script>
        function fillForm(song){
            $('#name').val(song.name);
            $('#date').val(song.date);
            $('#duration').val(song.duration);
            $('#capacity').val(song.capacity);
            $('#description').val(song.description);
        }

        function fillSelect(tags, selected){
            selectArr = [];
            for (i=0;i<selected.length;i++) {
                selectArr += selected[i].id;
            }
            var html = '';
            for (i=0;i<tags.length;i++){
                html += '<option value="'+ tags[i].id+'"';
                if(selectArr.includes(tags[i].id)) {
                    html += ' selected';
                }
                html += '>' + tags[i].name + '</option>';
            }
            return html;
        }

        $(document).ready(function() {
            if(!checkInStorage("Authorization")) {
                return false;
            }
            $.ajax({
                url: '{{ route('admin.parties.show', ['party_id' => $party_id]) }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function(data) {
                    showMessages(data, getElementsForMessages());
                    fillForm(data.parties);
                    $('#tags').html(fillSelect(data.tags, data.parties.tags));
                },
                error: function (data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                }
            });
        });

        $('#submitUpdateSong').on('click', function(){
            hideMessages(getElementsForMessages());
            var name = $('#name').val();
            var date = $('#date').val();
            var duration = $('#duration').val();
            var capacity = $('#capacity').val();
            var description = $('#description').val();
            var tags = $('#tags').val();
            $.ajax({
                url: '{{ route('admin.parties.update', ['party_id' => $party_id]) }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                type: 'PUT',
                data: ({name: name, date: date, duration: duration, capacity: capacity, description: description, tags: tags}),
                success: function(data) {
                    showMessages(data, getElementsForMessages());
                    fillForm(data.data);
                },
                error: function(data) {
                    if(data.statusText == "Unauthorized"){
                        window.location = "/";
                    }
                    showMessages(data.responseJSON.errors, getElementsForMessages());
                }
            });
        });
    </script>
@stop