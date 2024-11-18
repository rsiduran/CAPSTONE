<?php

$firebase = include('../config/firebase.php');
include('../config/auth.php');

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

    /* Profile and Logout */
    .sidebar .profile-section {
      margin-top: auto;
      padding: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* main content */
    .main-content {
      margin-left: 250px;
    }
 
    .profile-card {
      background: linear-gradient(135deg, #38a169, #2d6a4f);
      border-radius: 15px;
      color: white;
    }

    .profile-header h3 {
      font-weight: bold;
    }

    .profile-image {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border: 3px solid #fff;
      box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

    .owner-image {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border: 3px solid #fff;
      box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }

    .details-section {
      background: #ffffff;
      color: #333;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .additional-photos img {
      width: 100%;
      height: auto;
      border: 2px solid #ccc;
      border-radius: 10px;
      object-fit: cover;
    }

    .text-highlight {
      font-weight: bold;
      color: #2d6a4f;
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
            <p class="mb-4">Posted Date: <?= htmlspecialchars(
                is_numeric($petDetails['timestamp']) && $petDetails['timestamp'] > 0 
                ? date('Y-m-d H:i:s', (int)$petDetails['timestamp']) 
                : date('Y-m-d H:i:s', time())
            ) ?></p>
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
                  class="img-fluid rounded" style="height: 500px; object-fit: cover;">
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
