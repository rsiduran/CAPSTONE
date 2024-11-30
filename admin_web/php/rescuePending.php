<?php
// Include Firebase service instance
$firebase = include('../config/firebase.php');

// Fetch missing pets data from Firebase
$pets = $firebase->getDocuments("rescue");


// Filter the pets to only include those with a reportStatus of "pending"
$pendingPets = array_filter($pets, function($pet) {
    return isset($pet['reportStatus']) && $pet['reportStatus'] === 'PENDING';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="sidebar">
  <a href="../index.php">Dashboard</a>
  <a href="#inquiry">Inquiry</a>
  <a href="missing.php">Missing</a>
  <a href="wandering.php">Wandering</a>
  <a href="found.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
    <a href="#petAdoptionList" class="sub-link">Pet Adoption List</a>
    <a href="#adoptedPets" class="sub-link">Adopted Pets</a>
    <a href="addPetAdoption.php" class="sub-link">Add Pet</a>
  </div>
  <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption Application
  </a>
  <div class="collapse" id="applicationMenu">
    <a href="applicationPending.php" class="sub-link">Pending</a>
    <a href="applicationReviewing.php" class="sub-link">Reviewing</a>
    <a href="applicationApproved.php" class="sub-link">Approved</a>
    <a href="applicationCompleted.php" class="sub-link">Completed</a>
    <a href="applicationRejected.php" class="sub-link">Rejected</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="rescuePending.php" class="sub-link">Pending</a>
    <a href="rescueReviewing.php" class="sub-link">Reviewing</a>
    <a href="rescueOngoing.php" class="sub-link">Ongoing</a>
    <a href="rescueRescued.php" class="sub-link">Rescued</a>
    <a href="rescueDeclined.php" class="sub-link">Declined</a>
  </div>
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="../history/missing_history.php" class="sub-link">Missing</a>
    <a href="../history/wandering_history.php" class="sub-link">Wandering</a>
    <a href="#adopted-history" class="sub-link">Adopted</a>
    <a href="../history/found_history.php" class="sub-link">Found</a>
  </div>
</div>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light top-navbar">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#profile">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#logout">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h2 class="text-center">Pending Rescue Reports</h2>
  <div class="table-responsive">
    <table class="table table-striped mx-auto" style="width: 90%;">
      <thead>
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
        <?php foreach ($pendingPets as $petid => $pet) :?>
            <tr>
                <td><?= htmlspecialchars($pet['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($pet['lastName'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['petType'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['phoneNumber'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['reportStatus'] ?? 'N/A') ?></td>
                <td>
                    <a href="view_profilePending.php?petid=<?= urlencode($petid) ?>" class="btn btn-primary btn-sm">View Profile</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="5" class="text-center">No pending reports found</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
