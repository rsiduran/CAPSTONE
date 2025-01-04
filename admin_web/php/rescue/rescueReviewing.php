<?php
// Include Firebase service instance
$firebase = include('../../config/firebase.php');
include('../../config/auth.php');


// Fetch missing pets data from Firebase
$pets = $firebase->getDocuments("rescue");

include('../../config/counts.php');

$usersCount = $firebase->getCollectionCount('users');

// Sort the pets by timestamp, keeping their keys intact
uasort($pets, function ($a, $b) {
  $timeA = isset($a['timestamp']) && $a['timestamp'] !== 'N/A' && !empty($a['timestamp']) ? new DateTime($a['timestamp']) : null;
  $timeB = isset($b['timestamp']) && $b['timestamp'] !== 'N/A' && !empty($b['timestamp']) ? new DateTime($b['timestamp']) : null;

  if ($timeA && $timeB) {
      return $timeB <=> $timeA;
  }
  return 0;
});

$searchQuery = $_GET['search'] ?? null;
if ($searchQuery) {
    $pets = array_filter($pets, function ($pet) use ($searchQuery) {
        $searchQuery = strtolower($searchQuery);
        return (
            (isset($pet['firstName']) && stripos($pet['firstName'], $searchQuery) !== false) ||
            (isset($pet['lastName']) && stripos($pet['lastName'], $searchQuery) !== false) ||
            (isset($pet['email']) && stripos($pet['email'], $searchQuery) !== false) ||
            (isset($pet['phoneNumber']) && stripos($pet['phoneNumber'], $searchQuery) !== false) ||
            (isset($pet['reportStatus']) && stripos($pet['reportStatus'], $searchQuery) !== false) ||
            (isset($pet['timestamp']) && stripos($pet['timestamp'], $searchQuery) !== false)
        );
    });
}

$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'timestamp';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'asc' ? SORT_ASC : SORT_DESC;

if (!empty($pets)) {
  uasort($pets, function ($a, $b) use ($sortField, $sortOrder) {
      $valA = $a[$sortField] ?? '';
      $valB = $b[$sortField] ?? '';
      
      if ($sortField === 'timestamp') {
          $valA = strtotime($valA) ?: 0;
          $valB = strtotime($valB) ?: 0;
      } else {
          $valA = strtolower($valA); 
          $valB = strtolower($valB);
      }
      
      return $sortOrder === SORT_ASC ? $valA <=> $valB : $valB <=> $valA;
  });
}

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
    .badge {
    font-size: 12px;
    margin-left: 5px;
    padding: 5px 10px;
    border-radius: 12px;
    background-color: #dc3545; /* Bootstrap danger color */
    color: #fff;
    }
    .badge1 {
    font-size: 14px;
    font-weight: "bold";
    color: #fff;
    }
    .table-search-bar {
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
    }
    .sort-dropdown {
      display: inline-block;
      color: black;
      text-decoration: none;
      font-size: 14px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
      <img src="../../assets/images/logo.png" alt="WanderPets Logo">
      <h4>Supremo Furbabies</h4>
    </div>
    <a href="../../index.php">Dashboard</a>
    <a href="#inquiry">Inquiry</a>
    <a href="../users.php"><span class="badge1 "><?php echo $usersCount ?></span> Users</a>
    <a href="../missing.php">Missing <span class="badge bg-danger"><?= $unviewedCounts['missing'] ?? 0 ?></span></a>
    <a href="../wandering.php">Wandering <span class="badge bg-danger"><?= $unviewedCounts['wandering'] ?? 0 ?></span></a>
    <a href="../found.php">Found <span class="badge bg-danger"><?= $unviewedCounts['found'] ?? 0 ?></span></a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="../adoptionList.php" class="sub-link">Pet Adoption List <span class="badge bg-danger"><?= $unviewedCounts['adoption'] ?? 0 ?></span></a>
      <a href="../adoptedPets.php" class="sub-link">Adopted Pets</a>
      <a href="../addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
      Adoption Application
    </a>
    <div class="collapse" id="applicationMenu">
      <a href="../application/applicationPending.php" class="sub-link">Pending <span class="badge bg-danger"><?= $adoptionCounts['PENDING'] ?? 0 ?></span></a>
      <a href="../application/applicationReviewing.php" class="sub-link">Reviewing <span class="badge bg-danger"><?= $adoptionCounts['REVIEWING'] ?? 0 ?></span></a>
      <a href="../application/applicationApproved.php" class="sub-link">Approved <span class="badge bg-danger"><?= $adoptionCounts['APPROVED'] ?? 0 ?></span></a>
      <a href="../application/applicationCompleted.php" class="sub-link">Completed <span class="badge bg-danger"><?= $adoptionCounts['COMPLETED'] ?? 0 ?></span></a>
      <a href="../application/applicationRejected.php" class="sub-link">Rejected <span class="badge bg-danger"><?= $adoptionCounts['REJECTED'] ?? 0 ?></span></a>
    </div>
    <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
      Rescue
    </a>
    <div class="collapse" id="rescueMenu">
      <a href="../rescue/rescuePending.php" class="sub-link">Pending <span class="badge bg-danger"><?= $rescueCounts['PENDING'] ?? 0 ?></span></a>
      <a href="../rescue/rescueReviewing.php" class="sub-link">Reviewing <span class="badge bg-danger"><?= $rescueCounts['REVIEWING'] ?? 0 ?></span></a>
      <a href="../rescue/rescueOngoing.php" class="sub-link">Ongoing <span class="badge bg-danger"><?= $rescueCounts['ONGOING'] ?? 0 ?></span></a>
      <a href="../rescue/rescueRescued.php" class="sub-link">Rescued <span class="badge bg-danger"><?= $rescueCounts['RESCUED'] ?? 0 ?></span></a>
      <a href="../rescue/rescueDeclined.php" class="sub-link">Declined <span class="badge bg-danger"><?= $rescueCounts['DECLINED'] ?? 0 ?></span></a>
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

      <form method="GET" class="mb-4">
      <div class="input-group" style="width: 50%; margin: 0 auto;">
          <input type="text" class="form-control" name="search" placeholder="Search by ..."
                value="<?= htmlspecialchars($searchQuery ?? '') ?>">
          <button type="submit" class="btn btn-success">Search</button>
          <a href="rescueRescued.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>
    
      <div class="table-responsive">
        <table class="table table-hover mx-auto" style="width: 90%;">
          <thead class="table-success">
            <tr>
            <th>Name 
            <a href="?sortField=firstName&sortOrder=asc" class="sort-dropdown">↑</a>
            <a href="?sortField=firstName&sortOrder=desc" class="sort-dropdown">↓</a>
            </th>
              <th>Email
              <a href="?sortField=email&sortOrder=asc" class="sort-dropdown">↑</a>
              <a href="?sortField=email&sortOrder=desc" class="sort-dropdown">↓</a>
              </th>
              <th>Phone Number</th>
              <th>Status
              <a href="?sortField=applicationStatus&sortOrder=asc" class="sort-dropdown">↑</a>
              <a href="?sortField=applicationStatus&sortOrder=desc" class="sort-dropdown">↓</a>
              </th>
              <th>Timestamp
              <a href="?sortField=timestamp&sortOrder=asc" class="sort-dropdown">↑</a>
              <a href="?sortField=timestamp&sortOrder=desc" class="sort-dropdown">↓</a>
              </th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($pendingPets)) : ?>
    <?php foreach ($pendingPets as $petid => $pet) : ?>
        <tr>
            <td>
                <?= htmlspecialchars($pet['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($pet['lastName'] ?? 'N/A') ?>
                <?php if (isset($pet['viewed']) && $pet['viewed'] === 'NO') : ?>
                    <span class="badge bg-danger">Unviewed</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($pet['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($pet['phoneNumber'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($pet['reportStatus'] ?? 'N/A') ?></td>
                <td>
                <?php 
                  // Display timestamp in formatted style
                  if (isset($pet['timestamp']) && $pet['timestamp'] !== 'N/A' && !empty($pet['timestamp'])) {
                      try {
                          $time = new DateTime($pet['timestamp']); 
                          echo $time->format('F j, Y / g:i A');
                      } catch (Exception $e) {
                          echo 'Invalid Date';
                      }
                  } else {
                      echo 'N/A';
                  }
              ?>
                </td>
                <td>
                    <a href="viewProfile/markAsViewedReviewing.php?petid=<?= urlencode($petid) ?>&collection=rescue" class="btn btn-primary btn-sm">View Profile</a>
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
