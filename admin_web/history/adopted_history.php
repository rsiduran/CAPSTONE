<?php
// Include Firebase service instance
$firebase = include('../config/firebase.php');
include('../config/auth.php');

// Fetch missing history data from Firebase
$missingHistory = $firebase->getDocuments("missingHistory");

// Check if a pet needs to be deleted
if (isset($_GET['petid'])) {
    $petid = $_GET['petid'];
    $petDetails = $firebase->getDocuments("missing")[$petid] ?? null;

    if ($petDetails) {
        // Copy to missingHistory
        $firebase->copyDocumentToHistory($petDetails, $petid);

        // Delete from missing
        $firebase->deleteDocument("missing", $petid);

        // Redirect after deletion
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
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="sidebar">
  <a href="../index.php">Dashboard</a>
  <a href="#inquiry">Inquiry</a>
  <a href="../php/users.php">Users</a>
  <a href="../php/postedPets.php">Posted Pets</a>
  <a href="../php/missing.php">Missing</a>
  <a href="../php/wandering.php">Wandering</a>
  <a href="../php/found.php">Found</a>
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
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="missing_history.php" class="sub-link">Missing</a>
    <a href="wandering_history.php" class="sub-link">Wandering</a>  
    <a href="#adopted-history" class="sub-link">Adopted</a>
    <a href="found_history.php" class="sub-link">Found</a>
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
          <a class="nav-link" href="../php/login/logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h2 class="text-center">Missing History</h2>
  <div class="table-responsive">
    <table class="table table-striped mx-auto" style="width: 90%;">
      <thead>
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
                    <a href="view_profile.php?petid=<?= urlencode($historyId) ?>" class="btn btn-primary btn-sm">View Profile</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
