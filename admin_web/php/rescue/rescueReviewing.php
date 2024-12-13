<?php
// Include Firebase service instance
$firebase = include('../../config/firebase.php');
include('../../config/auth.php');


// Fetch missing pets data from Firebase
$pets = $firebase->getDocuments("rescue");

// Filter the pets to only include those with a reportStatus of "pending"
$pendingPets = array_filter($pets, function($pet) {
    return isset($pet['reportStatus']) && $pet['reportStatus'] === 'REVIEWING';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
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
      <img src="../../assets/images/logo.png" alt="WanderPets Logo">
      <h4>WanderPets</h4>
    </div>
    <a href="../../index.php">Dashboard</a>
    <a href="#inquiry">Inquiry</a>
    <a href="../users.php">Users</a>
    <a href="../postedPets.php">Posted Pets</a>
    <a href="../missing.php">Missing</a>
    <a href="../wandering.php">Wandering</a>
    <a href="../found.php">Found</a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="../adoptionList.php" class="sub-link">Pet Adoption List</a>
      <a href="../adoptedPets.php" class="sub-link">Adopted Pets</a>
      <a href="../addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
      Adoption Application
    </a>
    <div class="collapse" id="applicationMenu">
      <a href="../application/applicationPending.php" class="sub-link">Pending</a>
      <a href="../application/applicationReviewing.php" class="sub-link">Reviewing</a>
      <a href="../application/applicationApproved.php" class="sub-link">Approved</a>
      <a href="../application/applicationCompleted.php" class="sub-link">Completed</a>
      <a href="../application/applicationRejected.php" class="sub-link">Rejected</a>
    </div>
    <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
      Rescue
    </a>
    <div class="collapse" id="rescueMenu">
      <a href="../rescue/rescuePending.php" class="sub-link">Pending</a>
      <a href="../rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
      <a href="../rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
      <a href="../rescue/rescueRescued.php" class="sub-link">Rescued</a>
      <a href="../rescue/rescueDeclined.php" class="sub-link">Declined</a>
    </div>
    <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="historyMenu">
      History
    </a>
    <div class="collapse" id="historyMenu">
      <a href="../../history/missing_history.php" class="sub-link">Missing</a>
      <a href="../../history/wandering_history.php" class="sub-link">Wandering</a>
      <a href="#adopted-history" class="sub-link">Adopted</a>
      <a href="../../history/found_history.php" class="sub-link">Found</a>
    </div>
    <!-- Profile and Logout -->
    <div class="profile-section">
      <a href="#profile">Profile</a>
      <a href="../login/logout.php">Logout</a>
    </div>
  </div>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid mt-5 pt-3">
      <h1>Reviewing Rescue Reports</h1>
      <p>Below is the list of Reviewing Rescue Reports currently registered in the system:</p>
      <div class="table-responsive">
        <table class="table table-hover mx-auto" style="width: 90%;">
          <thead class="table-success">
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Phone Number</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($pendingPets)) : ?>
            <?php foreach ($pendingPets as $petid => $pet) : ?>
              <tr>
                <td><?= htmlspecialchars($pet['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($pet['lastName'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['petType'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['phoneNumber'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['reportStatus'] ?? 'N/A') ?></td>
                <td>
                  <a href="viewProfile/view_profileReviewing.php?petid=<?= urlencode($petid) ?>" class="btn btn-primary btn-sm">View Profile</a>
                </td>
            </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="5" class="text-center">No Reviewing Rescue applications found</td>
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
