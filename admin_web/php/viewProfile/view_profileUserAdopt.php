<?php
$firebase = include('../../config/firebase.php');
include('../../config/auth.php');

$userEmail = $_GET['email'] ?? null;

$petDetails = null;
if ($userEmail) {
    
    $allPets = $firebase->getDocuments("adoptionApplication");
    foreach ($allPets as $pet) {
        if (isset($pet['email']) && $pet['email'] === $userEmail) {
            $petDetails = $pet;
            break;
        }
    }

    if (!$petDetails) {
        die("Pet profile not found.");
    }
} else {
    die("Email parameter is missing.");
}

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
      <img src="../../assets/images/logo.png" alt="WanderPets Logo">
      <h4>Supremo Furbabies</h4>
    </div>
    <a href="../../index.php">Dashboard</a>
    <a href="#inquiry">Inquiry</a>
    <a href="../../php/users.php">Users</a>
    <a href="../../php/missing.php">Missing</a>
    <a href="../../php/wandering.php">Wandering</a>
    <a href="../../php/found.php">Found</a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="../../php/addPetAdoption.php" class="sub-link">Pet Adoption List</a>
      <a href="../../php/adoptedPets.php" class="sub-link">Adopted Pets</a>
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
<div class="main-content">
<div class="container my-5">
  
      <div class="card shadow-lg p-4">
      <!-- Header -->
      <div class="text-center mb-4">
        <h3 class="fw-bold">Pet Profile</h3>
      </div>

      <!-- Main Information -->
      <div class="row g-4">
      <!-- Pet Section -->
        <div class="col-md-6">
          <div class="form-group text-center mb-3 my-2">
          
          <img
            src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-pet.jpg') ?>"
            alt="<?= htmlspecialchars($petDetails['name'] ?? 'Pet Image') ?>"
            class="rounded-circle mb-3"
            style="width: 120px; height: 120px"
          />
          <h4 class="fw-bold mb-2">
          <?= htmlspecialchars($petDetails['name'] ?? 'Pet Image') ?>
                  </h4>
                </div>

                <div class="form-group my-2">
                  <label for="breed"><strong>Breed</strong></label>
                  <input
                    type="text"
                    id="breed"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['breed'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="age"><strong>Age</strong></label>
                  <input
                    type="text"
                    id="age"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['age'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="gender"><strong>Gender</strong></label>
                  <input
                    type="text"
                    id="gender"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['gender'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="size"><strong>Size</strong></label>
                  <input
                    type="text"
                    id="size"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['size'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="petType"><strong>Pet Type</strong></label>
                  <input
                    type="text"
                    id="petType"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['petType'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
              </div>

              <!-- Owner Section -->
              <div class="col-md-6">
                <div class="form-group text-center mb-3 my-2">
                  <img
                    src="<?= htmlspecialchars($petDetails['profilePicture'] ?? 'default-owner.jpg') ?>"
                    alt="<?= htmlspecialchars($petDetails['firstName'] . ' ' . $petDetails['lastName'] ?? 'Owner Image') ?>"
                    class="rounded-circle"
                    style="width: 120px; height: 120px"
                  />
                  <h4 class="fw-bold my-3">
                    Owner Information
                  </h4>
                </div>
                <div class="form-group my-2">
                  <label for="ownerName"><strong>Owner Name</strong></label>
                  <input
                    type="text"
                    id="ownerName"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="ownerEmail"><strong>Email</strong></label>
                  <input
                    type="email"
                    id="ownerEmail"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="ownerPhone"><strong>Phone Number</strong></label>
                  <input
                    type="text"
                    id="ownerPhone"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="description"><strong>Description</strong></label>
                  <textarea
                    id="description"
                    class="form-control"
                    rows="3"
                    disabled
                  >
                    <?= htmlspecialchars($petDetails['description'] ?? 'N/A') ?></textarea
                  >
                </div>
              </div>
            </div>

            <!-- Divider -->
            <hr class="my-4" />

            <!-- Additional Information -->

            <!-- -->
          </div>
    </div>
  </div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
