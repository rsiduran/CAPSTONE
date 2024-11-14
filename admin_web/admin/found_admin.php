<?php

$firebase = include('../config/firebase.php');
include('../config/auth.php');

$pets = $firebase->getDocuments("found");

if (isset($_GET['petid'])) {
    $petid = $_GET['petid'];
    $petDetails = $pets[$petid] ?? null;

    if ($petDetails) {  
        
        $firebase->copyDocumentToHistoryFound($petDetails, $petid);

        $firebase->deleteDocument("found", $petid);

        header("Location: found_admin.php");
        exit();             
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WanderPet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <style>
    /* Sidebar Styles */
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background: linear-gradient(135deg, #4caf50, #2e7d32);
      color: #fff;
      padding-top: 20px;
      overflow-y: auto;
    }

    .sidebar .logo {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar .logo img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #fff;
      padding: 10px;
    }

    .sidebar h4 {
      text-align: center;
      margin-top: 10px;
      font-size: 20px;
    }

    .sidebar a {
      display: block;
      padding: 10px 20px;
      color: #fff;
      text-decoration: none;
      font-size: 16px;
      border-radius: 5px;
      transition: background 0.3s ease;
    }

    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .sidebar .sub-link {
      padding-left: 40px;
      font-size: 14px;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
    }

    .card {
      background: #f8f9fa;
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
 <!-- Sidebar -->
 <div class="sidebar">
    <div class="logo">
      <img src="../assets/images/logo.png" alt="WanderPets Logo">
      <h4>WanderPets</h4>
    </div>
    <a href="admin.php">Dashboard</a>
    <a href="missing_admin.php">Missing</a>
    <a href="found_admin.php">Found</a>
    <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="historyMenu">History</a>
    <div class="collapse" id="historyMenu">
      <a href="historyMissingAdmin.php" class="sub-link">Missing</a>
      <a href="historyFoundAdmin.php" class="sub-link">Found</a>
    </div>
    <a href="#profile">Profile</a>
    <a href="../php/login/logout.php">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid mt-5 pt-3">
      <h1>Found Pets</h1>
      <p>Below is the list of Found Pets currently registered in the system:</p>
      <div class="table-responsive">
        <table class="table table-hover mx-auto" style="width: 90%;">
          <thead class="table-success">
            <tr>
              <th>Name</th>
              <th>Breed</th>
              <th>Type</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($pets)) : ?>
            <?php foreach ($pets as $petid => $pet) : ?>
              <tr>
                <td><?= htmlspecialchars($pet['name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['breed'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['petType'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['postType'] ?? 'N/A') ?></td>
                <td>
                  <a href="profile_found.php?petid=<?= urlencode($petid) ?>" class="btn btn-primary btn-sm">View Profile</a>
                  <a href="found_admin.php?petid=<?= urlencode($petid) ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="5" class="text-center">No records found</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
