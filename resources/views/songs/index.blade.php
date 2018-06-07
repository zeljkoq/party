@extends('layouts.app')

@section('content')
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
            </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
        <div id="pagination"></div>
    </div>
    @include('layouts._unauthorize_modal')
@stop
@section('per_page_scripts')
    <script>
        $(document).ready(function() {
            if(!checkInStorage("Authorization")) {
                return false;
            }
            $.ajax({
                url: '{{ route('songs.index') }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function (data) {
                    songs = data.data;
                    html = '';
                    for (i = 0; i < songs.length; i++) {
                        html += '<tr>';
                        html += '<td>' + songs[i].id + '</td>';
                        html += '<td>' + songs[i].name + '</td>';
                        html += '<td>' + songs[i].author + '</td>';
                        html += '<td><a target="_blank" href="' + songs[i].link + '">' + songs[i].link + '</a></td>';
                        html += '<td>' + songs[i].duration + ' minute/s</td>';
                        html += '</tr>';
                    }
                    $('#tableBody').html(html);
                    $('#dataTable').each(function() {
                        dt = $(this).dataTable();
                        dt.fnDraw();
                    });
                }
            });
        });
    </script>
@stop