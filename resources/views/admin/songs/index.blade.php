@extends('layouts.app')

@section('content')
    <div class="box">
        <h3>List of songs</h3>
        <table>
            <thead style="background-color: #ddd; font-weight: bold;">
            <tr>
                <td>Id</td>
                <td>Artist</td>
                <td>Track</td>
                <td>Link</td>
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
                        html += '<td>' + songs[i].duration + '</td>';
                        html += '</tr>';
                    }
                    $('#tableBody').html(html);
                    paginate = makePagination(data.links);
                    $('#pagination').html(paginate);
                }
            });
        });
        function getPaginate(link){
            $.ajax({
                url: link,
                headers: {
                    "Authorization":getFromStorage('Authorization')
                },
                success: function(data) {
                    songs = data.data;
                    html = '';
                    for (i = 0; i < songs.length; i++) {
                        html += '<tr>';
                        html += '<td>' + songs[i].id + '</td>';
                        html += '<td>' + songs[i].name + '</td>';
                        html += '<td>' + songs[i].author + '</td>';
                        html += '<td><a target="_blank" href="' + songs[i].link + '">' + songs[i].link + '</a></td>';
                        html += '<td>' + songs[i].duration + '</td>';
                        html += '</tr>';
                    }
                    $('#tableBody').html(html);
                    paginate = makePagination(data.links);
                    $('#pagination').html(paginate);
                }
            });
        }
    </script>
@stop