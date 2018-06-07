<div id="unauthorizeModal" class="unauthorize-modal" style="display: none">
    <div class="modal-background"></div>
    <div class="modal-card text-center">
        <p id="message">You are not logged in.<br>Please login to your account and come back.</p><br>
        <div class="modal-card-actions">
            <button onclick="window.location = '{{ route('loginForm') }}'" type="button" class="btn btn-info">Login now</button>
        </div>
    </div>
</div>