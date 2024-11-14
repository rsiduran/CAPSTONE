<?php

$firebase = include('../../config/firebase.php');
include('../../config/auth.php');

$petid = $_GET['petid'] ?? null;

$petDetails = $firebase->getDocuments("missing")[$petid] ?? null;

if (!$petDetails) {
    die("Pet not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Profile - <?= htmlspecialchars($petDetails['name'] ?? 'Pet Profile') ?></title>
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
    <a href="../../php/missing.php">Missing</a>
    <a href="../../php/wandering.php">Wandering</a>
    <a href="../../php/found.php">Found</a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="#petAdoptionList" class="sub-link">Pet Adoption List</a>
      <a href="#adoptedPets" class="sub-link">Adopted Pets</a>
      <a href="../../php/addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
      Adoption Application
    </a>
    <div class="collapse" id="applicationMenu">
      <a href="../../php/application/applicationPending.php" class="sub-link">Pending</a>
      <a href="../../php/application/applicationReviewing.php" class="sub-link">Reviewing</a>
      <a href="../../php/application/applicationApproved.php" class="sub-link">Approved</a>
      <a href="../../php/application/applicationCompleted.php" class="sub-link">Completed</a>
      <a href="../../php/application/applicationRejected.php" class="sub-link">Rejected</a>
    </div>
    <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
      Rescue
    </a>
    <div class="collapse" id="rescueMenu">
      <a href="../../php/rescue/rescuePending.php" class="sub-link">Pending</a>
      <a href="../../php/rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
      <a href="../../php/rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
      <a href="../../php/rescue/rescueRescued.php" class="sub-link">Rescued</a>
      <a href="../../php/rescue/rescueDeclined.php" class="sub-link">Declined</a>
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
      <a href="../../php/login/logout.php">Logout</a>
    </div>
  </div>

<!-- Main Content -->
<div class="container my-5">
  <div class="card shadow p-4" style="background-color: #2d8a5f; border-radius: 15px;">

    <!-- Header -->
    <div class="text-center mb-4">
      <h3 class="fw-bold text-white">Pet Profile</h3>
    </div>

    <!-- Pet and Owner Information Section -->
    <div class="row">

      <!-- Pet Section -->
      <div class="col-md-6 text-center mb-4">
        <img src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-pet.jpg') ?>" 
             alt="<?= htmlspecialchars($petDetails['name'] ?? 'Pet Image') ?>" 
             class="img-fluid rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
        <h4 class="fw-bold text-white"><?= htmlspecialchars($petDetails['name'] ?? 'N/A') ?></h4>
        <button class="btn btn-danger px-4 fw-bold mb-2" disabled>
          <?= htmlspecialchars(strtoupper($petDetails['postType'] ?? 'Lost')) ?>
        </button>
        <p class="text-muted">Posted Date: <?= htmlspecialchars(
            is_numeric($petDetails['timestamp']) && $petDetails['timestamp'] > 0 
            ? date('Y-m-d H:i:s', (int)$petDetails['timestamp']) 
            : date('Y-m-d H:i:s', time())
        ) ?></p>

        <div class="text-start mt-3 text-white">
          <p><strong>Breed:</strong> <?= htmlspecialchars($petDetails['breed'] ?? 'N/A') ?></p>
          <p><strong>Age:</strong> <?= htmlspecialchars($petDetails['age'] ?? 'N/A') ?></p>
          <p><strong>Gender:</strong> <?= htmlspecialchars($petDetails['gender'] ?? 'N/A') ?></p>
          <p><strong>Size:</strong> <?= htmlspecialchars($petDetails['size'] ?? 'N/A') ?></p>
          <p><strong>Pet Type:</strong> <?= htmlspecialchars($petDetails['petType'] ?? 'N/A') ?></p>
          <p><strong>City:</strong> <?= htmlspecialchars($petDetails['city'] ?? 'N/A') ?></p>
          <p><strong>Street Number:</strong> <?= htmlspecialchars($petDetails['streetNumber'] ?? 'N/A') ?></p>
          <p><strong>Address:</strong> <?= htmlspecialchars($petDetails['address'] ?? 'N/A') ?></p>
        </div>
      </div>

      <!-- Owner Section -->
      <div class="col-md-6 text-white">
        <h5 class="fw-bold text-center mb-3">Owner Information</h5>
        <div class="text-center mb-3">
          <img src="<?= htmlspecialchars($petDetails['profilePicture'] ?? 'default-owner.jpg') ?>" 
               alt="<?= htmlspecialchars($petDetails['firstName'] . ' ' . $petDetails['lastName'] ?? 'Owner Image') ?>" 
               class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        </div>
        <div class="text-start mb-4">
          <p><strong>Name:</strong> <?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?></p>
          <p><strong>Phone Number:</strong> <?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?></p>
        </div>

        <hr class="text-white">

        <h6 class="fw-bold text-center mt-4">Additional Details</h6>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="border p-3 rounded bg-white text-dark">
              <strong>Note</strong>
              <p><?= htmlspecialchars($petDetails['note'] ?? 'N/A') ?></p>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="border p-3 rounded bg-white text-dark">
              <strong>Description</strong>
              <p><?= htmlspecialchars($petDetails['description'] ?? 'N/A') ?></p>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- End of Row -->
  </div> <!-- End of Card -->
</div> <!-- End of Container -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
