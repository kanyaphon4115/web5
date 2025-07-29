<?php  
session_start();
$page = "news";

// ตรวจสอบว่าล็อกอินอยู่หรือไม่
if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
include("../db2/connection.php");

// จำนวนข่าวต่อหน้า
$newsPerPage = 5;

// อ่านหมายเลขหน้าจาก query string
$pageNumber = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
if ($pageNumber < 1) $pageNumber = 1;

// คำนวณ offset
$offset = ($pageNumber - 1) * $newsPerPage;

// ดึงข่าวตามหน้า
$query = mysqli_query($conn, "SELECT * FROM news LIMIT $offset, $newsPerPage");

// ดึงจำนวนข่าวทั้งหมดเพื่อคำนวณจำนวนหน้า
$totalNews = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM news"));
$totalPages = ceil($totalNews / $newsPerPage);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div style="margin-left:17%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="homeadmin.php">Home</a></li>
        <li class="breadcrumb-item">News</li>
    </ul>
</div>

<div style="width: 70%; margin-left:25%; margin-top: 10%;">
  <div class="text-right mb-2">
    <a href="addnews.php" class="btn btn-primary">Add News</a>
  </div>

  <table class="table table-bordered">
    <tr>
      <th>Id</th>
      <th>Title</th>
      <th>Description</th>
      <th>Date</th>
      <th>Category</th>
      <th>Thumbnail</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    <?php while ($row = mysqli_fetch_array($query)) { ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['title']); ?></td>
          <td><?php echo htmlspecialchars($row['description']); ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo htmlspecialchars($row['category']); ?></td>
          <td>
            <?php if (!empty($row['thumbnail'])) { ?>
              <img src="../images/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="Thumbnail" style="width:100px;">
            <?php } else { echo 'No image'; } ?>
          </td>
          <td>
            <a href="news_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-pencil-fill"></i>
            </a>
          </td>
          <td>
            <a href="news_delete.php?del=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirm the deletion of this news?')">
              <i class="bi bi-trash-fill"></i>
            </a>
          </td>
        </tr>
    <?php } ?>
  </table>

  <!-- pagination แบบแสดงแค่ 3 หน้า และมีปุ่ม Previous / Next -->
  <ul class="pagination justify-content-center">
    <!-- ปุ่ม Previous -->
    <?php if ($pageNumber > 1): ?>
      <li class="page-item">
        <a class="page-link" href="news.php?page=<?php echo $pageNumber - 1; ?>">Previous</a>
      </li>
    <?php endif; ?>

    <?php
      $start = max(1, $pageNumber - 1);
      $end = min($totalPages, $start + 2);

      // ปรับกรณีที่อยู่ท้ายๆ ให้เริ่มย้อนกลับ
      if ($end - $start < 2) {
          $start = max(1, $end - 2);
      }

      for ($i = $start; $i <= $end; $i++): 
        $active = ($i == $pageNumber) ? 'active' : '';
    ?>
      <li class="page-item <?php echo $active; ?>">
        <a class="page-link" href="news.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
      </li>
    <?php endfor; ?>

    <!-- ปุ่ม Next -->
    <?php if ($pageNumber < $totalPages): ?>
      <li class="page-item">
        <a class="page-link" href="news.php?page=<?php echo $pageNumber + 1; ?>">Next</a>
      </li>
    <?php endif; ?>
  </ul>
</div>
<?php include("../include/footer.php"); ?>
