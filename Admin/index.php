<?php
session_start();
require_once '../connection.php'; // Ensure you have your database connection set up here

// Check if user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // User is not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

// If user is logged in, get and display the email
$email = $_SESSION['email'];

// Fetch counts from the database
$queryAdmin = "SELECT COUNT(*) as count FROM user";
$queryCategory = "SELECT COUNT(*) as count FROM kategori";
$queryArticle = "SELECT COUNT(*) as count FROM artikel";

$resultAdmin = mysqli_query($conn, $queryAdmin);
$resultCategory = mysqli_query($conn, $queryCategory);
$resultArticle = mysqli_query($conn, $queryArticle);

$countAdmin = mysqli_fetch_assoc($resultAdmin)['count'];
$countCategory = mysqli_fetch_assoc($resultCategory)['count'];
$countArticle = mysqli_fetch_assoc($resultArticle)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">Dashboard</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group"></div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item"><a class="nav-link" href="../User/bloghome.php">Home</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-pie-chart-fill"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="viewAdmin.php">
                            <div class="sb-nav-link-icon"><i class='fas fa-user-friends'></i></div>
                            Admin
                        </a>
                        <a class="nav-link" href="viewCategories.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-newspaper"></i></div>
                            Category
                        </a>
                        <a class="nav-link" href="viewArticle.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                            Article
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= $_SESSION['email'] ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <p>Silahkan pilih salah satu menu berikut!</p>
                    <br>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Admin</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="viewAdmin.php">View Details</a>
                                    <div class="small text-white"><?= $countAdmin ?> <i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">Category</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="viewCategories.php">View Details</a>
                                    <div class="small text-white"><?= $countCategory ?> <i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Article</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="viewArticle.php">View Details</a>
                                    <div class="small text-white"><?= $countArticle ?> <i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="js/scripts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
            <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
