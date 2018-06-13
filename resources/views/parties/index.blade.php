@extends('admin.layouts.app')

@section('content')
    <div class="box">
        <h3>List of songs</h3>
        <table id="dataTable">
            <thead style="background-color: #ddd; font-weight: bold;">
            <tr>
                <td>Name</td>
                <td>Author</td>
                <td>Link</td>
                <td>Duration</td>
                <td>Party Name</td>
                <td>Party Date</td>
            </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
    <br><br>
    <div id="parties">

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
                url: '{{ route('parties.index') }}',
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function (data) {
                    var parties = data.parties;
                    var html = '';
                    for (i = 0; i < parties.length; i++) {
                        if (i == 0 || ((i / 3) % 1) == 0) {
                            html += '<div class="row">';
                        }

                        html += '<div class="col-md-4">';
                        html += '<div class="thumbnail">';
                        html += '<img src="/storage/' + parties[i].cover_photo + '">';
                        html += '<div class="caption">';
                        html += '<h3>' + parties[i].name + '</h3>';
                        html += '<p>';
                        html += 'Date: ' + parties[i].date + '<br>';
                        html += 'Duration: ' + parties[i].duration + '<br>';
                        if (parties[i].description) {
                            html += 'Description: ' + parties[i].description + '<br>';
                        }
                        html += '</p>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';

                        if ((((i + 1) / 3) % 1) == 0 || i == parties.length - 1) {
                            html += '</div>';
                        }
                    }
                    $('#parties').html(html);

                    var songs = data.songs;
                    html = '';
                    for (i = 0; i < songs.length; i++) {
                        html += '<tr>';
                        html += '<td>' + songs[i].name + '</td>';
                        html += '<td>' + songs[i].author + '</td>';
                        html += '<td><a target="_blank" href="' + songs[i].link + '">' + songs[i].link + '</a></td>';
                        html += '<td>' + songs[i].duration + ' minute/s</td>';
                        html += '<td>' + songs[i].party_name + '</td>';
                        html += '<td>' + songs[i].party_date + '</td>';
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