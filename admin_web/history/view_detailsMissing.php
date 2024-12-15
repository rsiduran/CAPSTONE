<?php

$firebase = include('../config/firebase.php');
include('../config/auth.php');

$petid = $_GET['petid'] ?? null;

$petDetails = $firebase->getDocuments("missingHistory")[$petid] ?? null;

if (!$petDetails) {
    die("Pet not found.");
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
  <a href="../php/missing.php">Missing</a>
  <a href="../php/wandering.php">Wandering</a>
  <a href="../php/found.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
    <a href="adoptionList.php" class="sub-link">Pet Adoption List</a>
    <a href="adoptedPets.php" class="sub-link">Adopted Pets</a>
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
<!-- Main Content -->
<div class="main-content">
    <div class="container my-5">
      <div class="card profile-card shadow-lg p-4">
        <!-- Header -->
        <div class="text-center profile-header mb-4">
          <h3>Pet Profile</h3>
        </div>

        <!-- Pet and Owner Information -->
        <div class="row g-4">
          <!-- Pet Section -->
          <div class="col-md-6 text-center">
            <img src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-pet.jpg') ?>" 
                alt="<?= htmlspecialchars($petDetails['name'] ?? 'Pet Image') ?>" 
                class="profile-image rounded-circle mb-3">
            <h4 class="fw-bold"><?= htmlspecialchars($petDetails['name'] ?? 'N/A') ?></h4>
            <button class="btn btn-danger btn-disabled px-4 fw-bold mb-3" disabled>
              <?= htmlspecialchars(strtoupper($petDetails['postType'] ?? 'Unknown')) ?>
            </button>
            <p>
    <strong>Posted Date:</strong> 
    <?php 
    if (isset($petDetails['timestamp']) && !empty($petDetails['timestamp'])) {
        try {
            // Parse the date into a DateTime object
            $rescuedDate = new DateTime($petDetails['timestamp']);
            // Format the date as 'Month day, Year at H:i:s A'
            echo $rescuedDate->format('F j, Y \a\t g:i:s A');
        } catch (Exception $e) {
            // Handle invalid date formats gracefully
            echo 'Invalid Date';
        }
    } else {
        echo 'N/A'; // Display N/A if the date is not set
    }
    ?>
</p>
            <div class="text-start details-section">
              <p><span class="text-highlight">Breed:</span> <?= htmlspecialchars($petDetails['breed'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Age:</span> <?= htmlspecialchars($petDetails['age'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Gender:</span> <?= htmlspecialchars($petDetails['gender'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Size:</span> <?= htmlspecialchars($petDetails['size'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Pet Type:</span> <?= htmlspecialchars($petDetails['petType'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">City:</span> <?= htmlspecialchars($petDetails['city'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Street Number:</span> <?= htmlspecialchars($petDetails['streetNumber'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Address:</span> <?= htmlspecialchars($petDetails['address'] ?? 'N/A') ?></p>
            </div>
          </div>

          <!-- Owner Section -->
          <div class="col-md-6">
            <div class="text-center mb-3">
              <img src="<?= htmlspecialchars($petDetails['profilePicture'] ?? 'default-owner.jpg') ?>" 
                  alt="<?= htmlspecialchars($petDetails['firstName'] . ' ' . $petDetails['lastName'] ?? 'Owner Image') ?>" 
                  class="owner-image rounded-circle">
            </div>
            <div class="details-section">
              <h5 class="text-center mb-3 text-highlight">Owner Information</h5>
              <p><span class="text-highlight">Name:</span> <?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Email:</span> <?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?></p>
              <p><span class="text-highlight">Phone Number:</span> <?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?></p>
            </div>
          </div>
        </div>

        <hr class="text-white my-4">

        <!-- Additional Details -->
        <div class="row g-4">
          <div class="col-md-6">
            <div class="details-section">
              <strong>Note</strong>
              <p><?= htmlspecialchars($petDetails['note'] ?? 'N/A') ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="details-section">
              <strong>Description</strong>
              <p><?= htmlspecialchars($petDetails['description'] ?? 'N/A') ?></p>
            </div>
          </div>
        </div>

        <!-- Additional Photos -->
        <?php if (!empty($petDetails['additionalPhotos']) && is_array($petDetails['additionalPhotos'])): ?>
        <div class="mt-4">
          <h5 class="">Additional Photos</h5>
          <div class="row g-3 additional-photos">
            <?php foreach ($petDetails['additionalPhotos'] as $photo): ?>
            <div class="col-md-4">
              <img src="<?= htmlspecialchars($photo) ?>" 
                  alt="Additional Photo" 
                  class="img-fluid rounded">
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <p class="mt-4">No additional photos available.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
