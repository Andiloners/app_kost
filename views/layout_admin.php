<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Kost' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/app_kost/public/assets/css/admin.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-page">
    <!-- Loading Spinner -->
    <div id="spinnerContainer" class="spinner-container">
        <div class="spinner"></div>
    </div>

    <div class="d-flex">
        <!-- Sidebar -->
        <?php include __DIR__.'/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid py-4">
                <!-- Breadcrumb -->
                <?php include __DIR__.'/admin/breadcrumb.php'; ?>
                
                <!-- Page Content -->
                <div class="content-wrapper">
                    <?= $content ?? '' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/app_kost/public/assets/js/admin.js"></script>
    <script src="/app_kost/public/assets/js/form-validation.js"></script>
    
    <!-- Page Specific Scripts -->
    <script>
        // Global functions
        function showSpinner() {
            document.getElementById('spinnerContainer').style.display = 'flex';
        }
        
        function hideSpinner() {
            document.getElementById('spinnerContainer').style.display = 'none';
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
        
        // Add loading state to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    showSpinner();
                });
            });
        });
    </script>
</body>
</html> 