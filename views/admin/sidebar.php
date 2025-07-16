<aside class="sidebar">
    <div class="sidebar-header p-4">
        <div class="d-flex align-items-center">
            <div class="sidebar-logo me-3">
                <i class="bi bi-building-fill" style="font-size: 2rem; color: white;"></i>
            </div>
            <div>
                <h4 class="mb-0">Admin Kost</h4>
                <small class="text-white-50">Management System</small>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $active=='dashboard'?' active':'' ?>" href="?page=admin&menu=dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $active=='penghuni'?' active':'' ?>" href="?page=admin&menu=penghuni">
                    <i class="bi bi-people-fill"></i>
                    <span>Penghuni</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $active=='kamar'?' active':'' ?>" href="?page=admin&menu=kamar">
                    <i class="bi bi-door-closed-fill"></i>
                    <span>Kamar</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $active=='barang'?' active':'' ?>" href="?page=admin&menu=barang">
                    <i class="bi bi-box-seam"></i>
                    <span>Barang</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $active=='tagihan'?' active':'' ?>" href="?page=admin&menu=tagihan">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Tagihan</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $active=='pembayaran'?' active':'' ?>" href="?page=admin&menu=pembayaran&action=pembayaran_list">
                    <i class="bi bi-cash-coin"></i>
                    <span>Pembayaran</span>
                </a>
            </li>
        </ul>
        
        <!-- Logout Section -->
        <div class="sidebar-footer mt-auto p-3">
            <hr class="border-white-50">
            <a href="?page=admin&menu=logout" class="nav-link text-white-50">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside> 