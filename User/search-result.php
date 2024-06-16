<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connection.php';

// Get the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    echo 'No search query provided.';
    exit();
}

// Sanitize the search query
$query = $conn->real_escape_string($query);

// Perform the search query
$sql = "SELECT artikel.*, kategori.nama_kategori 
        FROM artikel 
        LEFT JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
        WHERE artikel.judul LIKE '%$query%' OR artikel.isi LIKE '%$query%' 
        ORDER BY artikel.tanggal DESC";
$result = $conn->query($sql);

// Query for all categories for the navigation menu
$categories = [];
$allCategoriesSql = "SELECT * FROM kategori";
$allCategoriesResult = $conn->query($allCategoriesSql);
if ($allCategoriesResult->num_rows > 0) {
    while ($row = $allCategoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>FarisBlog - Search Results</title>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

  <!-- Template Main CSS Files -->
  <link href="assets/css/variables.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <h1>FarisBlog</h1>
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.php">Blog</a></li>
          <li class="dropdown"><a href="category.php"><span>Categories</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
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
    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-9" data-aos="fade-up">
            <h3 class="category-title">Search Results for "<?php echo htmlspecialchars($query); ?>"</h3>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Ensure 'konten' and 'nama_penulis' columns exist and are not NULL
                    $konten = $row['isi'] ?? 'No content available';
                    $nama_penulis = $row['penulis'] ?? 'Unknown Author';
                    $thumbnail = !empty($row['gambar']) ? '<img src="data:image/jpeg;base64,' . base64_encode($row["gambar"]) . '" alt="" class="img-fluid">' : '<img src="assets/img/default-thumbnail.jpg" alt="" class="img-fluid">';

                    // Strip HTML tags from the content and truncate it to 150 characters
                    $konten = strip_tags($konten);
                    $konten = mb_substr($konten, 0, 150, 'UTF-8') . (mb_strlen($konten, 'UTF-8') > 150 ? '...' : '');

                    echo '<div class="d-md-flex post-entry-2 half">';
                    echo '<a href="single-post.php?id=' . $row["id_artikel"] . '" class="me-4 thumbnail">';
                    echo $thumbnail;
                    echo '</a>';
                    echo '<div>';
                    echo '<div class="post-meta"><span class="date">' . htmlspecialchars($row["nama_kategori"]) . '</span> <span class="mx-1">&bullet;</span> <span>' . htmlspecialchars($row["tanggal"]) . '</span></div>';
                    echo '<h3><a href="single-post.php?id=' . $row["id_artikel"] . '">' . htmlspecialchars($row["judul"]) . '</a></h3>';
                    echo '<p>' . htmlspecialchars($konten, ENT_QUOTES, 'UTF-8') . '</p>';
                    echo '<div class="d-flex align-items-center author">';
                    echo '<div class="photo"><i class="bi bi-person-circle" style="font-size: 2rem;"></i></div>';
                    echo '<div class="name">';
                    echo '<h3 class="m-0 p-0">' . htmlspecialchars($nama_penulis) . '</h3>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No articles found for this category.</p>';
            }
            ?>
          </div>

          <div class="col-md-3">
            <!-- ======= Sidebar ======= -->
            <div class="aside-block">

              <ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest" type="button" role="tab" aria-controls="pills-latest" aria-selected="true">Latest</button>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <!-- Latest -->
                <div class="tab-pane fade show active" id="pills-latest" role="tabpanel" aria-labelledby="pills-latest-tab">
                  <?php
                  $latestSql = "SELECT artikel.id_artikel, kategori.nama_kategori, artikel.judul, artikel.tanggal, artikel.penulis 
                                FROM artikel 
                                JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                                ORDER BY artikel.tanggal DESC 
                                LIMIT 6"; 
                  $latestResult = $conn->query($latestSql);

                  if ($latestResult->num_rows > 0) {
                    while ($row = $latestResult->fetch_assoc()) {
                      echo '<div class="post-entry-1 border-bottom">';
                      echo '<div class="post-meta"><span class="date">' . htmlspecialchars($row['nama_kategori']) . '</span> <span class="mx-1">&bullet;</span> <span>' . htmlspecialchars($row['tanggal']) . '</span></div>';
                      echo '<h2 class="mb-2"><a href="single-post.php?id=' . $row['id_artikel'] . '">' . htmlspecialchars($row['judul']) . '</a></h2>';
                      echo '<span class="author mb-3 d-block">' . htmlspecialchars($row['penulis']) . '</span>';
                      echo '</div>';
                    }
                  } else {
                    echo '<p>No recent articles found.</p>';
                  }
                  ?>
                </div> <!-- End Latest -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="footer-content">
      <div class="container">

        <div class="row g-5">
          <div class="col-lg-4">
            <h3 class="footer-heading">About FarisBlog</h3>
            <p>ini adalah blog yang berguna untuk menampung segala artikel yang menarik untuk dibaca.</p>
            
          </div>
          <div class="col-6 col-lg-2">
            <h3 class="footer-heading">Navigations</h3>
            <ul class="footer-links list-unstyled">
              <li><a href="index.php"><i class="bi bi-chevron-right"></i> Home</a></li>
              <li><a href="category.php"><i class="bi bi-chevron-right"></i> Categories</a></li>
              
              <li><a href="about.php"><i class="bi bi-chevron-right"></i> About</a></li>
            </ul>
          </div>
          <div class="col-6 col-lg-2">
            <h3 class="footer-heading">Categories</h3>
            <ul class="footer-links list-unstyled">
              <?php
              foreach ($categories as $category) {
                echo '<li><a href="category.php?id=' . $category['id_kategori'] . '"><i class="bi bi-chevron-right"></i> ' . htmlspecialchars($category['nama_kategori']) . '</a></li>';
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
        </div>
      </div>
    </div>

    <div class="footer-legal">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <div class="copyright">
              &copy; 2023 <strong><span>FarisBlog</span></strong>. All Rights Reserved.
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

  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>


  <script>
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
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS Files -->
  <script src="assets/js/main.js"></script>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
