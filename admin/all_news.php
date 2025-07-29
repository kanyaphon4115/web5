<?php
session_start();
include('../db2/connection.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM news WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        echo "ไม่พบข่าวที่ต้องการ";
        exit();
    }
} else {
    echo "ไม่พบข่าว";
    exit();
}
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($row['title']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="blog.css" />
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      user-select: none;
    }
    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
</head>
<script src="theme-toggle.js"></script>
<body>
  <div class="container">

    <!-- Header -->
    <header class="blog-header py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <!-- ช่องว่างหรือโลโก้ -->
        </div>
        <div class="col-8 d-flex justify-content-end align-items-center">
          <form class="d-flex me-2 w-75" method="GET" action="homepage_news.php" role="search">
            <div class="input-group input-group-sm w-100">
              <input
                type="search"
                name="q"
                class="form-control rounded-start"
                placeholder="ค้นหาข่าว..."
                aria-label="Search"
                aria-describedby="button-search"
              >
              <button class="btn btn-outline-success" type="submit" id="button-search" aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.442.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                </svg>
              </button>
            </div>
          </form>
          <a class="btn btn-sm btn-outline-secondary ms-2" href="index.php">Logout</a>
        </div>
      </div>
    </header>

    <!-- Navigation -->
    <div class="nav-scroller py-1 mb-2">
      <nav class="nav d-flex justify-content-between">
        <a class="p-2 link-secondary" href="homepage_news.php">Home</a>
        <a class="p-2 link-secondary" href="category_news.php?category=Business">Business</a>
        <a class="p-2 link-secondary" href="category_news.php?category=Sport">Sport</a>
        <a class="p-2 link-secondary" href="category_news.php?category=Technology">Technology</a>
        <a class="p-2 link-secondary" href="category_news.php?category=Entertainment">Entertainment</a>
        <a class="p-2 link-secondary" href="category_news.php?category=Crime">Crime</a>
        <a class="p-2 link-secondary" href="contact.php">Contact us</a>
      </nav>
    </div>

    <!-- Content -->
    <main class="container">
      <article class="blog-post">
        <h1 class="mb-3"><?php echo htmlspecialchars($row['title']); ?></h1>
        <p class="text-muted mb-4"><?php echo htmlspecialchars($row['date']); ?> | หมวดหมู่: <?php echo htmlspecialchars($row['category']); ?></p>

        <?php if (!empty($row['thumbnail'])): ?>
          <img src="../images/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="" class="img-fluid mb-4" style="max-height: 450px; object-fit: cover;" />
        <?php endif; ?>

        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

        <a href="homepage_news.php" class="btn btn-secondary mt-4">กลับหน้าแรก</a>
      </article>
    </main>

    <!-- Footer -->
    <footer class="blog-footer text-center py-3 mt-5">
      <p><a href="#">Back to top</a></p>
    </footer>

  </div>
</body>
</html>
