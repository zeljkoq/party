<div id="detailsModal" class="confirmation-modal" style="display: none">
    <div class="modal-background"></div>
    <div class="modal-card text-center">
        <h3>Songs</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>Name</td>
                <td>Author</td>
                <td>Link</td>
                <td>Duration</td>
                <td>Artist</td>
            </tr>
            </thead>
            <tbody id="detailsBody"></tbody>
        </table>
        <div id="detailsText"></div><br>
        <div class="modal-card-actions">
            <button onclick="$('#detailsModal').css('display', 'none')" type="button" class="btn btn-info">Cancel</button>&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>