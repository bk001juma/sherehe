{{-- resources/views/components/alerts.blade.php --}}
@if (session('success'))
    <div class="alert alert-success fade-out" id="alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger fade-out" id="alert-error">
        {{ session('error') }}
    </div>
@endif

{{-- Add script for fade-out --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.fade-out');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    });
</script>
