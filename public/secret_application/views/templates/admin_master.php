<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom_admin.css'); ?>">
</head>
<body>
    <main class="d-flex flex-nowrap">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
                <span class="fs-4">App Test</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <?php if($this->session->userdata('user_type') == 1): ?> 
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' ? 'active': 'text-white'?>" aria-current="page">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('employees') ?>" class="nav-link <?= uri_string() == 'employees' ? 'active': 'text-white'?>">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg>
                        Employees
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('employee-time-records') ?>" class="nav-link <?= uri_string() == 'employee-time-records' ? 'active': 'text-white'?>">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"/></svg>
                        Employee Time Records
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('users') ?>" class="nav-link <?= uri_string() == 'users' ? 'active': 'text-white'?>">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"/></svg>
                        Users
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' ? 'active': 'text-white'?>" aria-current="page">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('employee-time-records') ?>" class="nav-link <?= uri_string() == 'employee-time-records' ? 'active': 'text-white'?>">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"/></svg>
                        Employee Time Records
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong><?= ucfirst($this->session->userdata('user_name')) ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>">Sign out</a></li>
                </ul>
            </div>
        </div>
        <div class="px-4 py-5" style="height: min(100%, 100vh); width: 100%; overflow-y: auto;">
            <?php echo $content; ?>
        </div>
    </main>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <strong class="me-auto">App Test</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('assets/js/zxing-browser.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/qrcode.min.js'); ?>"></script>
    <script>
        const baseUrl = '<?= site_url(); ?>';
        const pathUrl = '<?= uri_string(); ?>';
    </script>
    <script src="<?php echo base_url('assets/js/custom_helper.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/custom_admin.js'); ?>"></script>
</body>
</html>