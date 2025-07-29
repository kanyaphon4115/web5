<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Admin</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="admin.php">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if($page=='homeadmin'){echo 'active';}?>" href="homeadmin.php">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page=='news'){echo 'active';}?>" href="news.php">
                  <span data-feather="shopping-cart"></span>
                  News
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page=='category'){echo 'active';}?>" href="categories.php">
                  <span data-feather="file"></span>
                  Categories
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link  <?php if($page=='admin'){echo 'active';}?>" href="profile.php">
                  <span data-feather="users"></span>
                  Admin
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if($page=='user'){echo 'active';}?>" href="list_users.php">
                  <span data-feather="bar-chart-2"></span>
                  User
                </a>
              </li>
            </ul>
            </ul>
          </div>
        </nav>
