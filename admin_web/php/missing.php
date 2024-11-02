<?php
// Include Firebase service instance
$firebase = include('../config/firebase.php');

// Fetch missing pets data from Firebase
$pets = $firebase->getDocuments("missing");

// Check if a pet needs to be deleted
if (isset($_GET['petid'])) {
    $petid = $_GET['petid'];
    $petDetails = $pets[$petid] ?? null;

    if ($petDetails) {  
        // Copy to missingHistory
        $firebase->copyDocumentToHistoryMissing($petDetails, $petid);

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
  <a href="missing.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
    <a href="#adoption-application" class="sub-link">Adoption Application</a>
    <a href="#pet-adoption" class="sub-link">Pet Adoption</a>
    <a href="#adopted-pets" class="sub-link">Adopted Pets</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="#rescued-pets" class="sub-link">Rescued Pets</a>
  </div>
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="../history/missing_history.php" class="sub-link">Missing</a>
    <a href="../history/wandering_history.php" class="sub-link">Wandering</a>
    <a href="#adopted-pets" class="sub-link">Adopted</a>
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
  <h2 class="text-center">Missing Pets</h2>
  <div class="table-responsive">
    <table class="table table-striped mx-auto" style="width: 90%;">
      <thead>
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
        <?php foreach ($pets as $petid => $pet) :?>
            <tr>
                <td><?= htmlspecialchars($pet['name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['breed'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['petType'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($pet['postType'] ?? 'N/A') ?></td>
                <td>
                    <a href="view_profileMissing.php?petid=<?= urlencode($petid) ?>" class="btn btn-primary btn-sm">View Profile</a>
                    <a href="missing.php?petid=<?= urlencode($petid) ?>" class="btn btn-danger btn-sm">Delete</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
