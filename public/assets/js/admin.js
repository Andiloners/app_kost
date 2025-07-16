// Admin JavaScript - Modern and Interactive

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initSidebar();
    initAnimations();
    initFormValidation();
    initDataTables();
    initCharts();
    initNotifications();
    initSearch();
    initModals();
});

// Sidebar functionality
function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    
    // Add hover effects
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Mobile sidebar toggle
    const toggleBtn = document.querySelector('.sidebar-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }
}

// Animations
function initAnimations() {
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__fadeInUp');
            }
    });
    }, observerOptions);
    
    // Observe cards and tables
    document.querySelectorAll('.card, .table').forEach(el => {
        observer.observe(el);
    });
    
    // Animate statistics
    animateStatistics();
}

// Animate statistics numbers
function animateStatistics() {
    const statElements = document.querySelectorAll('.stat-value');
    
    statElements.forEach(element => {
        const targetValue = parseInt(element.getAttribute('data-value') || '0');
        const duration = 2000; // 2 seconds
        const steps = 60;
        const increment = targetValue / steps;
        let currentValue = 0;
        let step = 0;
        
        const timer = setInterval(() => {
            step++;
            currentValue += increment;
            
            if (step >= steps) {
                element.textContent = targetValue;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(currentValue);
            }
        }, duration / steps);
    });
}

// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                showNotification('Mohon lengkapi semua field yang wajib diisi', 'error');
            }
            form.classList.add('was-validated');
        });
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
}

// Validate individual field
function validateField(field) {
    const isValid = field.checkValidity();
    
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
    }
}

// Data tables functionality
function initDataTables() {
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        // Add sorting functionality
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.addEventListener('click', function() {
                sortTable(table, Array.from(table.querySelectorAll('th')).indexOf(this));
            });
        });
        
        // Add search functionality
        const searchInput = table.parentElement.querySelector('.table-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterTable(table, this.value);
            });
    }
    });
}

// Sort table
function sortTable(table, columnIndex) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const header = table.querySelectorAll('th')[columnIndex];
    const isAscending = header.classList.contains('sort-asc');
    
    // Clear previous sort classes
    table.querySelectorAll('th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });
    
    // Add sort class
    header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        if (isAscending) {
            return bValue.localeCompare(aValue);
        } else {
            return aValue.localeCompare(bValue);
        }
    });
    
    // Reorder rows
    rows.forEach(row => tbody.appendChild(row));
}

// Filter table
function filterTable(table, searchTerm) {
    const rows = table.querySelectorAll('tbody tr');
    const term = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Charts initialization
function initCharts() {
    // Dashboard charts
    const chartElements = document.querySelectorAll('[data-chart]');
    
    chartElements.forEach(element => {
        const chartType = element.getAttribute('data-chart');
        const chartData = JSON.parse(element.getAttribute('data-chart-data') || '{}');
        
        createChart(element, chartType, chartData);
  });
}

// Create chart
function createChart(element, type, data) {
    const ctx = element.getContext('2d');
    
    const config = {
        type: type,
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    };
    
    new Chart(ctx, config);
}

// Notifications system
function initNotifications() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="bi bi-${getNotificationIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Get notification icon
function getNotificationIcon(type) {
    const icons = {
        'success': 'check-circle-fill',
        'error': 'exclamation-triangle-fill',
        'warning': 'exclamation-triangle-fill',
        'info': 'info-circle-fill'
    };
    return icons[type] || 'info-circle-fill';
}

// Search functionality
function initSearch() {
    const searchInputs = document.querySelectorAll('.search-input');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const targetTable = this.getAttribute('data-target');
            const table = document.querySelector(targetTable);
            
            if (table) {
                filterTable(table, searchTerm);
            }
        });
    });
}

// Modal functionality
function initModals() {
    // Confirm delete modals
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm-delete') || 'Apakah Anda yakin ingin menghapus item ini?';
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
}

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

// Loading states
function showLoading(element) {
    element.classList.add('loading');
    element.disabled = true;
}

function hideLoading(element) {
    element.classList.remove('loading');
    element.disabled = false;
}

// Export functions
function exportToExcel(tableId, filename = 'data') {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const ws = XLSX.utils.table_to_sheet(table);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    XLSX.writeFile(wb, `${filename}.xlsx`);
}

function exportToPDF(elementId, filename = 'document') {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    html2pdf()
        .from(element)
        .save(`${filename}.pdf`);
}

// Print functionality
function printElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                ${element.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Global error handler
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    showNotification('Terjadi kesalahan pada sistem', 'error');
});

// Success message handler
window.addEventListener('success', function(e) {
    showNotification(e.detail.message, 'success');
});

// Custom events
window.addEventListener('showNotification', function(e) {
    showNotification(e.detail.message, e.detail.type);
});

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

function initAll() {
    // All initialization code is already in the DOMContentLoaded event
} 