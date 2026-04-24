<div style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
    @if (session()->has('success'))
        <div class="alert alert-success shadow-lg" id="flash-card-success" style="min-width: 300px; border-right: 5px solid #28a745;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle ml-2"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="close ml-auto" onclick="closeFlash('flash-card-success')">
                    <span>&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-lg" id="flash-card-error" style="min-width: 300px; border-right: 5px solid #dc3545;">
            <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle ml-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close ml-auto" onclick="closeFlash('flash-card-error')">
                <span>&times;</span>
            </button>
        </div>
    @endif
</div>

<script>
    function closeFlash(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Auto hide after 5 seconds
    setTimeout(function() {
        var successCard = document.getElementById('flash-card-success');
        var errorCard = document.getElementById('flash-card-error');
        if (successCard) successCard.style.display = 'none';
        if (errorCard) errorCard.style.display = 'none';
    }, 5000);
</script>