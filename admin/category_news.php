<?php
session_start();
include("../db2/connection.php");

// รับ category จาก URL และ sanitize
$category_raw = $_GET['category'] ?? '';
$category = mysqli_real_escape_string($conn, $category_raw);

// รับ search query ถ้ามี
$searchTermRaw = $_GET['q'] ?? '';
$searchTerm = mysqli_real_escape_string($conn, $searchTermRaw);

// กำหนด title เพื่อนำไปแสดงในหน้า
$pageTitle = !empty($category) ? "ข่าวหมวดหมู่: " . htmlspecialchars($category) : "ข่าวหมวดหมู่";

// สร้าง WHERE condition สำหรับ query
$whereClauses = [];
if ($category != '') {
    $whereClauses[] = "category = '$category'";
}
if ($searchTerm != '') {
    $likeTerm = "%$searchTerm%";
    $whereClauses[] = "(title LIKE '$likeTerm' OR description LIKE '$likeTerm')";
}
$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

// ดึงข้อมูลสำหรับ carousel, recommended, และ other ข่าว
$sqlCarousel = "SELECT id, title, thumbnail FROM news $whereSQL ORDER BY RAND() LIMIT 4";
$sqlRecommended = "SELECT * FROM news $whereSQL ORDER BY date DESC";
$sqlOther = "SELECT id, title, thumbnail FROM news $whereSQL ORDER BY RAND() LIMIT 7";

$carouselResult = mysqli_query($conn, $sqlCarousel);
$recommendedResult = mysqli_query($conn, $sqlRecommended);
$otherResult = mysqli_query($conn, $sqlOther);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $pageTitle; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="blog.css" />
  <style>
    /* สไตล์สำหรับเมนู active */
    .nav .active {
      color: #198754 !important; /* สีเขียว bootstrap */
      font-weight: bold;
    }
  </style>
</head>
<body>
<!-- หน้าอื่น ๆ -->
<script src="theme-toggle.js"></script>

<div class="container">
  <header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 text-center"></div>
      <div class="col-8 d-flex justify-content-end align-items-center">
        <form class="d-flex me-2 w-75" method="GET" action="" role="search">
          <?php if ($category != ''): ?>
            <!-- เก็บ category ไว้ใน GET เวลาค้นหา เพื่อคงอยู่ในหมวด -->
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
          <?php endif; ?>
          <div class="input-group input-group-sm w-100">
            <input
              type="search"
              name="q"
              class="form-control rounded-start"
              placeholder="ค้นหาข่าว..."
              aria-label="Search"
              aria-describedby="button-search"
              value="<?php echo htmlspecialchars($searchTermRaw); ?>"
            >
            <button class="btn btn-outline-success" type="submit" id="button-search" aria-label="Search">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.442.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
              </svg>
            </button>
          </div>
        </form>
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
    <li><a class="dropdown-item" href="#">Settings</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="index.php">Logout</a></li>
  </ul>
</div>

      </div>
    </div>
  </header>

  <div class="nav-scroller py-1 mb-2">
    <nav class="nav d-flex justify-content-between">
      <a class="p-2 link-secondary <?php if($category == '') echo 'active'; ?>" href="homepage_news.php">Home</a>
      <a class="p-2 link-secondary <?php if($category == 'Business') echo 'active'; ?>" href="category_news.php?category=Business">Business</a>
      <a class="p-2 link-secondary <?php if($category == 'Sport') echo 'active'; ?>" href="category_news.php?category=Sport">Sport</a>
      <a class="p-2 link-secondary <?php if($category == 'Technology') echo 'active'; ?>" href="category_news.php?category=Technology">Technology</a>
      <a class="p-2 link-secondary <?php if($category == 'Entertainment') echo 'active'; ?>" href="category_news.php?category=Entertainment">Entertainment</a>
      <a class="p-2 link-secondary <?php if($category == 'Crime') echo 'active'; ?>" href="category_news.php?category=Crime">Crime</a>
      <a class="p-2 link-secondary" href="contact.php">Contact us</a>
    </nav>
  </div>

  <main class="container">
    <h1 class="pb-4 mb-4 fst-italic border-bottom"><?php echo $pageTitle; ?></h1>

    <!-- Carousel -->
    <div id="newsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $active = true;
        while ($row = mysqli_fetch_assoc($carouselResult)) {
          ?>
          <div class="carousel-item <?php if ($active) { echo 'active'; $active = false; } ?>">
            <img src="../images/<?php echo htmlspecialchars($row['thumbnail']); ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="<?php echo htmlspecialchars($row['title']); ?>">
          </div>
          <?php
        }
        ?>
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

    <div class="row g-5">
      <div class="col-md-8">
        <h2 class="pb-4 mb-4 fst-italic border-bottom">
         
        </h2>
        <?php
        $count = 0;
        while ($row = mysqli_fetch_assoc($recommendedResult)) {
          $count++;
          $fullText = $row['description'];
          $halfText = mb_substr($fullText, 0, 100, "UTF-8"); // ตัดข้อความ 100 ตัวอักษร
          ?>
          <article class="blog-post mb-4">
            <h3 class="blog-post-title"><?php echo htmlspecialchars($row['title']); ?></h3>
            <p class="blog-post-meta"><?php echo htmlspecialchars($row['date']); ?> | หมวดหมู่: <?php echo htmlspecialchars($row['category']); ?></p>

            <?php if (!empty($row['thumbnail'])) { ?>
              <img src="../images/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="รูปภาพประกอบข่าว" class="img-fluid mb-3" />
            <?php } ?>

            <p><?php echo nl2br(htmlspecialchars($halfText)); ?>...</p>
            <a href="all_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">อ่านเพิ่มเติม</a>

            <hr />
          </article>
          <?php
        }
        if ($count == 0 && $searchTerm != '') {
            echo '<p>ไม่พบผลลัพธ์การค้นหา</p>';
        }
        ?> 
      </div>

      <div class="col-md-4">
          <div class="p-4">
            <h4 class="fst-italic">ข่าวอื่น ๆ</h4>
            <div class="list-unstyled mb-0">
              <?php
              while ($row = mysqli_fetch_assoc($otherResult)) {
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
  <footer class="blog-footer text-center py-3">
    <p><a href="#">Back to top</a></p>
  </footer>
</div>
</body>
</html>
