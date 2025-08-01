<?php
session_start();
require 'connect.php';
include "head.php";
?>

<body class="g-sidenav-show bg-gray-200">
    <?php $active = 'hd'; require 'aside.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Content for left column -->
                </div>
                <div class="col-lg-4">
                    <!-- Content for right column -->
                </div>
            </div>
        </div>
    </main>
    <?php $conn->close(); ?>
</body>
</html> 