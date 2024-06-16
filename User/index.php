<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../connection.php';

// Tentukan jumlah artikel per halaman
$articlesPerPage = 6;

// Ambil halaman saat ini dari parameter URL (default ke halaman 1)
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Hitung offset
$offset = ($currentPage - 1) * $articlesPerPage;

// Query untuk mengambil total jumlah artikel
$totalSql = "SELECT COUNT(*) as total FROM artikel";
$totalResult = $conn->query($totalSql);
$totalArticles = $totalResult->fetch_assoc()['total'];

// Hitung jumlah halaman
$totalPages = ceil($totalArticles / $articlesPerPage);

// Query untuk mengambil artikel berdasarkan limit dan offset
$sql = "SELECT artikel.*, kategori.nama_kategori 
        FROM artikel 
        LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
        ORDER BY tanggal DESC 
        LIMIT $articlesPerPage OFFSET $offset";
$result = $conn->query($sql);

// Query untuk mengambil semua kategori
$categoriesSql = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori";
$categoriesResult = $conn->query($categoriesSql);

// Simpan hasil query ke dalam array
$categories = [];
if ($categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>FarisBlog</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="assets/css/variables.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        /* Custom styles for Swiper */
        .swiper-container {
            width: 100%;
            height: 100%;
        }
        .swiper-slide {
            background-position: center;
            background-size: cover;
        }
        .img-bg {
            width: 100%;
            height: 100%;
            display: block;
            position: relative;
        }
        .img-bg-inner {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);

            .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        }
    </style>
  
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>FarisBlog</h1>
      </a>

      <nav id="navbar" class="navbar">
    <ul>
        <li><a href="index.php">Blog</a></li>
        
        <li class="dropdown "><a  href="category.php"><span>Categories</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
                <?php
                foreach ($categories as $category) {
                    echo '<li><a href="category.php?id=' . $category['id_kategori'] . '">' . htmlspecialchars($category['nama_kategori']) . '</a></li>';
                }
                ?>
            </ul>
        </li>

        <li><a href="about.php">About us</a></li>
        
        
    </ul>
</nav><!-- .navbar -->

      <div class="position-relative">
        <a href="https://www.facebook.com/aiz.ais.5/?locale=id_ID" class="mx-2"><span class="bi-facebook"></span></a>
        
        <a href="https://www.instagram.com/f__kun__/" class="mx-2"><span class="bi-instagram"></span></a>

        <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
        <i class="bi bi-list mobile-nav-toggle"></i>

        <!-- ======= Search Form ======= -->
        <div class="search-form-wrap js-search-form-wrap">
          <form action="search-result.php" method="GET" class="search-form">
            <span class="icon bi-search"></span>
            <input type="text" name="query" placeholder="Search" class="form-control" required>
            <button type="button" class="btn js-search-close"><span class="bi-x"></span></button>
          </form>
        </div><!-- End Search Form -->

      </div>

    </div>

  </header><!-- End Header -->

  <main id="main">
        <section id="posts" class="posts">
            <div class="container" data-aos="fade-up">
                <div class="row g-5">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-lg-4">';
                            echo '<div class="post-entry-1">';
                            echo '<a href="single-post.php?id=' . $row["id_artikel"] . '"><img src="data:image/jpeg;base64,' . base64_encode($row["gambar"]) . '" alt="" class="img-fluid"></a>';
                            echo '<div class="post-meta"><span class="date">' . $row["nama_kategori"] . '</span> <span class="mx-1">&bullet;</span> <span>' . $row["tanggal"] . '</span></div>';
                            echo '<h2><a href="single-post.php?id=' . $row["id_artikel"] . '">' . $row["judul"] . '</a></h2>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No articles found.</p>';
                    }
                    ?>
                </div>
                
                <!-- Pagination Links -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php
                        if ($currentPage > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
                        }

                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<li class="page-item' . ($i == $currentPage ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                        }

                        if ($currentPage < $totalPages) {
                            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </section>
    </main>

    <!-- Include Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
var swiper = new Swiper('.sliderFeaturedPosts', {
    spaceBetween: 30,
    effect: 'fade',
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
});

$(document).ready(function() {
    // Open search form
    $('.js-search-open').on('click', function(e) {
        e.preventDefault();
        $('.js-search-form-wrap').show();
    });

    // Close search form
    $('.js-search-close').on('click', function(e) {
        e.preventDefault();
        $('.js-search-form-wrap').hide();
    });

    // Toggle mobile navigation
    $('.mobile-nav-toggle').on('click', function() {
        // Implement your mobile nav toggle logic here
        // Example: toggling a class that shows/hides the nav
        $('.mobile-nav').toggleClass('open');
    });
});
</script>


  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="footer-content">
      <div class="container">

        <div class="row g-5">
          <div class="col-lg-4">
            <h3 class="footer-heading">About FarisBlog</h3>
            <p>ini adalah blog yang berguna untuk menampung segala artikel yang menarik untuk dibaca</p>
            
          </div>
          <div class="col-6 col-lg-2">
            <h3 class="footer-heading">Navigation</h3>
            <ul class="footer-links list-unstyled">
              <li><a href="index.php"><i class="bi bi-chevron-right"></i> Home</a></li>
              <li><a href="index.php"><i class="bi bi-chevron-right"></i> Blog</a></li>
              <li><a href="category.php"><i class="bi bi-chevron-right"></i> Categories</a></li>
              
             
              <li><a href="about.php"><i class="bi bi-chevron-right"></i> About us</a></li>
            </ul>
          </div>
          <div class="col-6 col-lg-2">
          <h3 class="footer-heading">Categories</h3>
          <ul class="footer-links list-unstyled">
              <?php
              $sql = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo '<li><a href="category.php?id=' . $row["id_kategori"] . '"><i class="bi bi-chevron-right"></i> ' . htmlspecialchars($row["nama_kategori"]) . '</a></li>';
                  }
              } else {
                  echo '<li>No categories available.</li>';
              }
              ?>
          </ul>
</div>

          <!-- recent post -->
          <div class="col-lg-4">
    <h3 class="footer-heading">Recent Posts</h3>

    <ul class="footer-links footer-blog-entry list-unstyled">
        <?php
        $sql = "SELECT artikel.*, kategori.nama_kategori FROM artikel LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori ORDER BY tanggal DESC LIMIT 4";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imageData = base64_encode($row["gambar"]);
                $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                echo '<li>';
                echo '<a href="single-post.php?id=' . $row["id_artikel"] . '" class="d-flex align-items-center">';
                echo '<img src="' . $imageSrc . '" alt="" class="img-fluid me-3">';
                echo '<div>';
                echo '<div class="post-meta d-block"><span class="date">' . htmlspecialchars($row["nama_kategori"]) . '</span> <span class="mx-1">&bullet;</span> <span>' . htmlspecialchars($row["tanggal"]) . '</span></div>';
                echo '<span>' . htmlspecialchars($row["judul"]) . '</span>';
                echo '</div>';
                echo '</a>';
                echo '</li>';
            }
        } else {
            echo '<li>No recent posts available.</li>';
        }
        ?>
    </ul>
</div>
      <!-- akhir recent post -->

    <div class="footer-legal">
      <div class="container">

        <div class="row justify-content-between">
          <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <div class="copyright">
              © Copyright <strong><span>FarisBlog</span></strong>. All Rights Reserved
            </div>

            <div class="credits">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX about form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
              Designed by <a href="#">farisyuda</a>
            </div>

          </div>

          <div class="col-md-6">
            <div class="social-links mb-3 mb-lg-0 text-center text-md-end">
              <a href = "https://www.facebook.com/aiz.ais.5/?locale=id_ID" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/f__kun__/" class="instagram"><i class="bi bi-instagram"></i></a>
              
            </div>

          </div>

        </div>

      </div>
    </div>

  </footer>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>