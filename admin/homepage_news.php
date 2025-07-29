<?php
session_start();
include("../db2/connection.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Host Programming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="blog.css" />
    
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
  </head>
  <body>
<script src="theme-toggle.js"></script>
</body>
</html>
    <div class="container">
      <!-- Header -->
      <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
          <div class="col-4 pt-1">
            <!-- โลโก้ หรือว่างไว้ -->
          </div>
          <div class="col-8 d-flex justify-content-end align-items-center">
            <!-- ค้นหา -->
            <form class="d-flex me-2 w-75" method="GET" action="" role="search">
              <div class="input-group input-group-sm w-100">
                <input
                  type="search"
                  name="q"
                  class="form-control rounded-start"
                  placeholder="ค้นหาข่าว..."
                  aria-label="Search"
                  aria-describedby="button-search"
                  value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                >
                <button class="btn btn-outline-success" type="submit" id="button-search" aria-label="Search">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.442.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                  </svg>
                </button>
              </div>
            </form>
             <button id="toggle-theme-btn" class="btn btn-outline-secondary btn-sm me-2">mode</button>

<div class="dropdown ms-2">
  <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center"
      type="button"
      id="dropdownUserButton"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      style="max-width: 250px; white-space: normal; text-overflow: ellipsis; overflow: hidden;">
    user
  </button>
  <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUserButton" style="max-width: 250px; overflow-wrap: break-word;">
    <li class="px-3 py-2 text-muted" style="word-break: break-word;">
      <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>
    </li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="contact.php">Contact us</a></li>
    <li><a class="dropdown-item" href="account_settings.php">Settings</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="index.php">Logout</a></li>
  </ul>
</div>

          </div>
        </div>
      </header>

      <!-- เมนูหลัก -->
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

      <!-- Carousel เฉพาะตอนที่ไม่ได้ค้นหา -->
      <?php if (!isset($_GET['q']) || empty(trim($_GET['q']))) : ?>
      <div id="newsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
          $sql = "SELECT id, title, thumbnail FROM news ORDER BY RAND() LIMIT 4";
          $result = mysqli_query($conn, $sql);
          $active = true;
          while ($row = mysqli_fetch_assoc($result)) {
          ?>
            <div class="carousel-item <?php if ($active) { echo 'active'; $active = false; } ?>">
              <img src="../images/<?php echo htmlspecialchars($row['thumbnail']); ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="ข่าวเด่น">
            </div>
          <?php } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">ก่อนหน้า</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">ถัดไป</span>
        </button>
      </div>
      <?php endif; ?>

      <!-- Main Content -->
      <div class="row g-5">
        <div class="col-md-8">
          <?php
          if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
              $searchTerm = mysqli_real_escape_string($conn, $_GET['q']);
              
              $query = mysqli_query($conn, "SELECT * FROM news WHERE title LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%' ORDER BY date DESC");
              if (mysqli_num_rows($query) == 0) {
                  echo "<p>ไม่พบผลลัพธ์</p>";
              }
          } else {
              echo '<h1 class="pb-4 mb-4 fst-italic border-bottom">ข่าวแนะนำ</h1>';
              $query = mysqli_query($conn, "SELECT * FROM news ORDER BY RAND() LIMIT 5");
          }

          while ($row = mysqli_fetch_assoc($query)) {
              $fullText = $row['description'];
              $halfText = mb_substr($fullText, 0, 100, "UTF-8");
          ?>
            <article class="blog-post mb-4">
              <h2 class="blog-post-title"><?php echo htmlspecialchars($row['title']); ?></h2>
              <p class="blog-post-meta"><?php echo htmlspecialchars($row['date']); ?> | หมวดหมู่: <?php echo htmlspecialchars($row['category']); ?></p>
              <?php if (!empty($row['thumbnail'])) { ?>
                <img src="../images/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="รูปภาพประกอบข่าว" class="img-fluid mb-3" />
              <?php } ?>
              <p><?php echo nl2br(htmlspecialchars($halfText)); ?>...</p>
              <a href="all_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">อ่านเพิ่มเติม</a>
              <hr />
            </article>
          <?php } ?>
        </div>

        <!-- ข่าวอื่น ๆ -->
        <div class="col-md-4">
          <div class="position-sticky" style="top: 2rem;">
            <div class="p-4">
              <h4 class="fst-italic">ข่าวอื่น ๆ</h4>
              <div class="list-unstyled mb-0">
                <?php
                $sql = "SELECT id, title, thumbnail FROM news ORDER BY RAND() LIMIT 7";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="media mb-3 d-flex">';
                    echo '  <img src="../images/' . htmlspecialchars($row['thumbnail']) . '" class="mr-3" alt="รูปข่าว" style="width: 64px; height: 64px; object-fit: cover;">';
                    echo '  <div class="media-body ms-2">';
                    echo '    <h6 class="mt-0"><a href="all_news.php?id=' . $row['id'] . '" style="text-decoration: none;">' . htmlspecialchars($row['title']) . '</a></h6>';
                    echo '  </div>';
                    echo '</div>';
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="blog-footer text-center py-3">
      <p><a href="#">Back to top</a></p>
    </footer>
    
</html>
