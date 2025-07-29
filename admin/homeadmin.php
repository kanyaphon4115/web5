<?php 
session_start();
$page = "homeadmin";

if (!isset($_SESSION["email"])) {
    header("location: admin.php");
    exit();
}

include("../include/header.php");
include("../db2/connection.php");

// ดึงวันที่ข่าวทั้งหมด (ไม่ซ้ำ)
$sql_dates = "SELECT DISTINCT DATE(date) as news_date FROM news ORDER BY news_date DESC";
$result_dates = mysqli_query($conn, $sql_dates);

$allDates = [];
while ($row = mysqli_fetch_assoc($result_dates)) {
    $allDates[] = $row['news_date'];
}

$selectedDate = $_GET['date'] ?? null;

if ($selectedDate) {
    $selectedDate = mysqli_real_escape_string($conn, $selectedDate);

    // กราฟ: จำนวนข่าวแยกตามหมวดหมู่ของวันที่เลือก
    $sql_graph = "SELECT category, COUNT(*) as news_count
                  FROM news 
                  WHERE DATE(date) = '$selectedDate'
                  GROUP BY category";

    $result_graph = mysqli_query($conn, $sql_graph);

    $categories = [];
    $counts = [];
    while ($row = mysqli_fetch_assoc($result_graph)) {
        $categories[] = $row['category'];
        $counts[] = (int)$row['news_count'];
    }

    // ข่าวในวันที่เลือก
    $sql_news = "SELECT id, title, category, date FROM news WHERE DATE(date) = '$selectedDate' ORDER BY date DESC";
    $result_news = mysqli_query($conn, $sql_news);

} else {
    // กราฟ: จำนวนข่าวรวมทั้งหมดแยกตามวัน
    $sql_graph = "SELECT DATE(date) as news_date, COUNT(*) as news_count 
                  FROM news 
                  GROUP BY DATE(date) 
                  ORDER BY DATE(date) ASC";

    $result_graph = mysqli_query($conn, $sql_graph);

    $categories = [];
    $counts = [];
    while ($row = mysqli_fetch_assoc($result_graph)) {
        $categories[] = $row['news_date'];
        $counts[] = (int)$row['news_count'];
    }

    // ข่าวทั้งหมด
    $sql_news = "SELECT id, title, category, date FROM news ORDER BY date DESC";
    $result_news = mysqli_query($conn, $sql_news);
}
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>

  <!-- เลือกวันที่ -->
  <form method="GET" class="mb-3">
    <label for="dateSelect" class="form-label">เลือกวันที่จะแสดงข่าว:</label>
    <select name="date" id="dateSelect" class="form-select" onchange="this.form.submit()">
      <option value="">-- แสดงข่าวทั้งหมด --</option>
      <?php foreach ($allDates as $date): ?>
        <option value="<?= htmlspecialchars($date) ?>" <?= ($selectedDate == $date) ? "selected" : "" ?>>
          <?= htmlspecialchars($date) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <!-- กราฟ -->
  <canvas id="myChart" width="900" height="400"></canvas>
  <?php
if (!$selectedDate) {
    // ดึงจำนวนข่าวรวมในแต่ละหมวดหมู่
    $sql_total_by_category = "SELECT category, COUNT(*) as total FROM news GROUP BY category";
    $result_total_by_category = mysqli_query($conn, $sql_total_by_category);

    $totalNews = 0;
    $categoryData = [];

    while ($row = mysqli_fetch_assoc($result_total_by_category)) {
        $category = $row['category'];
        $count = (int)$row['total'];
        $totalNews += $count;
        $categoryData[] = ['category' => $category, 'count' => $count];
    }
?>
  <h3 class="mt-5"> Total news by category</h3>
  <?php foreach ($categoryData as $item): 
      $percent = round(($item['count'] / $totalNews) * 100);
      $category = htmlspecialchars($item['category']);

      // ตั้งค่าสีแต่ละหมวด
      switch (strtolower($category)) {
          case 'business': $barColor = 'bg-success'; break;
          case 'sport': $barColor = 'bg-warning'; break;
          case 'technology': $barColor = 'bg-primary'; break;
          case 'entertainment': $barColor = 'bg-danger'; break;
          case 'crime': $barColor = 'bg-dark'; break;
          default: $barColor = 'bg-secondary';
      }
  ?>
    <div class="mb-2">
      <div class="d-flex justify-content-between">
        <strong><?= $category ?></strong>
        <span><?= $percent ?>%</span>
      </div>
      <div class="progress">
        <div class="progress-bar <?= $barColor ?>" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>
  <?php endforeach; ?>
<?php } ?>


  <!-- ตารางข่าว -->
  <h2 class="mt-5"><?= $selectedDate ? "รายการข่าววันที่ ".htmlspecialchars($selectedDate) : "รายการข่าวทั้งหมด" ?></h2>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>หัวข้อข่าว</th>
          <th>หมวดหมู่</th>
          <th>วันที่</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        if ($result_news && mysqli_num_rows($result_news) > 0) {
          while ($news = mysqli_fetch_assoc($result_news)) {
              echo "<tr>";
              echo "<td>" . $i++ . "</td>";
              echo "<td>" . htmlspecialchars($news['title']) . "</td>";
              echo "<td>" . htmlspecialchars($news['category']) . "</td>";
              echo "<td>" . htmlspecialchars($news['date']) . "</td>";
              echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='4' class='text-center'>ไม่มีข่าวในระบบ</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</main>

<!-- โหลด Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const labels = <?= json_encode($categories); ?>;
  const dataCounts = <?= json_encode($counts); ?>;

  const ctx = document.getElementById('myChart').getContext('2d');

  const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels.length ? labels : ['ไม่มีข้อมูล'],
      datasets: [{
        label: 'จำนวนข่าว<?= $selectedDate ? " วันที่ ".htmlspecialchars($selectedDate) : "แต่ละวัน" ?>',
        data: dataCounts.length ? dataCounts : [0],
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            precision: 0
          },
          title: {
            display: true,
            text: 'จำนวนข่าว'
          }
        },
        x: {
          title: {
            display: true,
            text: '<?= $selectedDate ? "หมวดหมู่ข่าว" : "วันที่" ?>'
          }
        }
      }
    }
  });
</script>
<?php include("../include/footer.php"); ?>
