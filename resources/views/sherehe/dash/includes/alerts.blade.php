<!-- resources/views/includes/alerts.blade.php -->
@if (session('error'))
    <div id="error-alert" class="alert alert-error mt-3" style="background-color: red; color: white;">
        {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('error-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000); // 3 seconds
    </script>
@endif

@if (session('success'))
    <div id="success-alert" class="alert alert-success mt-3" style="background-color: green; color: white;">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000); // 3 seconds
    </script>
@endif
