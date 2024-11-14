<?php

$firebase = include('../config/firebase.php');
include('../config/auth.php');

$missingHistory = $firebase->getDocuments("missingHistory");

if (isset($_GET['petid'])) {
    $petid = $_GET['petid'];
    $petDetails = $firebase->getDocuments("missing")[$petid] ?? null;

    if ($petDetails) {

        $firebase->copyDocumentToHistoryMissing($petDetails, $petid);

        $firebase->deleteDocument("missing", $petid);

        header("Location: missing.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Pet Adoption</title>
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

    /* Profile and Logout */
    .sidebar .profile-section {
      margin-top: auto;
      padding: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
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
    <a href="../index.php">Dashboard</a>
    <a href="#inquiry">Inquiry</a>
    <a href="../php/missing.php">Missing</a>
    <a href="../php/wandering.php">Wandering</a>
    <a href="../php/found.php">Found</a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="#petAdoptionList" class="sub-link">Pet Adoption List</a>
      <a href="#adoptedPets" class="sub-link">Adopted Pets</a>
      <a href="../php/addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
      Adoption Application
    </a>
    <div class="collapse" id="applicationMenu">
      <a href="../php/application/applicationPending.php" class="sub-link">Pending</a>
      <a href="../php/application/applicationReviewing.php" class="sub-link">Reviewing</a>
      <a href="../php/application/applicationApproved.php" class="sub-link">Approved</a>
      <a href="../php/application/applicationCompleted.php" class="sub-link">Completed</a>
      <a href="../php/application/applicationRejected.php" class="sub-link">Rejected</a>
    </div>
    <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
      Rescue
    </a>
    <div class="collapse" id="rescueMenu">
      <a href="../php/rescue/rescuePending.php" class="sub-link">Pending</a>
      <a href="../php/rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
      <a href="../php/rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
      <a href="../php/rescue/rescueRescued.php" class="sub-link">Rescued</a>
      <a href="../php/rescue/rescueDeclined.php" class="sub-link">Declined</a>
    </div>
    <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="historyMenu">
      History
    </a>
    <div class="collapse" id="historyMenu">
      <a href="../history/missing_history.php" class="sub-link">Missing</a>
      <a href="../history/wandering_history.php" class="sub-link">Wandering</a>
      <a href="#adopted-history" class="sub-link">Adopted</a>
      <a href="../history/found_history.php" class="sub-link">Found</a>
    </div>
    <!-- Profile and Logout -->
    <div class="profile-section">
      <a href="#profile">Profile</a>
      <a href="../php/login/logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid mt-5 pt-3">
      <h1>Missing Pets History</h1>
      <p>Below is the list of missing pets history currently registered in the system:</p>
      <div class="table-responsive">
        <table class="table table-hover mx-auto" style="width: 90%;">
          <thead class="table-success">
            <tr>
              <th>Name</th>
              <th>Breed</th>
              <th>Type</th>
              <th>Status</th>
              <th>Removed At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($missingHistory)) : ?>
            <?php foreach ($missingHistory as $historyId => $history) : ?>
              <tr>
                <td><?= htmlspecialchars($history['name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($history['breed'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($history['petType'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($history['postType'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($history['removedAt'] ?? 'N/A') ?></td>
                <td>
                  <a href="view_detailsMissing.php?petid=<?= urlencode($historyId) ?>" class="btn btn-primary btn-sm">View Details</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="6" class="text-center">No records found in history</td>
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
