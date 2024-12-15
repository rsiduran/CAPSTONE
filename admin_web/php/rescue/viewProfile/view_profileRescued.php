<?php

$firebase = include('../../../config/firebase.php');
include('../../../config/auth.php');

$petid = $_GET['petid'] ?? null;

$petDetails = $firebase->getDocuments("rescue")[$petid] ?? null;

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

<div class="sidebar">
<div class="logo">
      <img src="../../../assets/images/logo.png" alt="WanderPets Logo">
      <h4>Supremo Furbabies</h4>
    </div>
  <a href="../../../index.php">Dashboard</a>
  <a href="#inquiry.php">Inquiry</a>
  <a href="../../../users.php">Users</a>
  <a href="../../missing.php">Missing</a>
  <a href="../../wandering.php">Wandering</a>
  <a href="../../found.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
      <a href="../../../php/adoptionList.php" class="sub-link">Pet Adoption List</a>
      <a href="../../../php/adoptedPets.php" class="sub-link">Adopted Pets</a>
      <a href="../../../php/addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
  <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption Application
  </a>
  <div class="collapse" id="applicationMenu">
    <a href="../../applicationPending.php" class="sub-link">Pending</a>
    <a href="../../applicationReviewing.php" class="sub-link">Reviewing</a>
    <a href="../../applicationApproved.php" class="sub-link">Approved</a>
    <a href="../../applicationCompleted.php" class="sub-link">Completed</a>
    <a href="../../applicationRejected.php" class="sub-link">Rejected</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="../../../php/rescue/rescuePending.php" class="sub-link">Pending</a>
    <a href="../../../php/rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
    <a href="../../../php/rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
    <a href="../../../php/rescue/rescueRescued.php" class="sub-link">Rescued</a>
    <a href="../../../php/rescue/rescueDeclined.php" class="sub-link">Declined</a>
  </div>
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="../../../history/missing_history.php" class="sub-link">Missing</a>
    <a href="../../../history/wandering_history.php" class="sub-link">Wandering</a>
    <a href="#history/wandering_history" class="sub-link">Adopted</a>
    <a href="../../../history/found_history.php" class="sub-link">Found</a>
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
          <a class="nav-link" href="../../login/logout.php">Logout</a>
        </li>
      </ul> 
    </div>
  </div>
</nav>

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
          <p><span class="fw-bold">Transaction Number:</span> <?= htmlspecialchars($petDetails['transactionNumber'] ?? 'N/A') ?></p>
          <img
            src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-pet.jpg') ?>"
            alt="<?= htmlspecialchars($petDetails['name'] ?? 'Pet Image') ?>"
            class="rounded-circle mb-3"
            style="width: 120px; height: 120px"
          />
          <h4 class="fw-bold mb-2">
          <button class="btn btn-danger btn-disabled px-4 fw-bold mb-3" disabled>
          <?= htmlspecialchars(strtoupper($petDetails['reportStatus'] ?? 'Unknown')) ?>
              </button>
                  </h4>
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
                <div class="form-group my-2">
                  <label for="city"><strong>City</strong></label>
                  <input
                    type="text"
                    id="city"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['city'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="streetNumber"><strong>Street Number</strong></label>
                  <input
                    type="text"
                    id="streetNumber"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['streetNumber'] ?? 'N/A') ?>"
                    disabled
                  />
                </div>
                <div class="form-group my-2">
                  <label for="address"><strong>Address</strong></label>
                  <input
                    type="text"
                    id="address"
                    class="form-control"
                    value="<?= htmlspecialchars($petDetails['address'] ?? 'N/A') ?>"
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
                    <?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?>
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
                  <label for="note"><strong>Note</strong></label>
                  <textarea id="note" class="form-control" rows="3" disabled>
                    <?= htmlspecialchars($petDetails['note'] ?? 'N/A') ?></textarea
                  >
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

            <!-- Additional Photos -->
            <?php if (!empty($petDetails['additionalPhotos']) && is_array($petDetails['additionalPhotos'])): ?>
            <div class="mt-4">
              <h4 class="text-center mb-2">Additional Photos</h4>
              <div
                id="photoCarousel"
                class="carousel slide"
                data-bs-ride="carousel"
                style="max-width: 500px; margin: 0 auto"
              >
                <div class="carousel-inner">
                  <?php foreach ($petDetails['additionalPhotos'] as $index =>
                  $photo): ?>
                  <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img
                      src="<?= htmlspecialchars($photo) ?>"
                      alt="Additional Photo"
                      class="d-block w-100 rounded"
                    />
                  </div>
                  <?php endforeach; ?>
                </div>
                <button
                  class="carousel-control-prev"
                  type="button"
                  data-bs-target="#photoCarousel"
                  data-bs-slide="prev"
                >
                  <span
                    class="carousel-control-prev-icon"
                    aria-hidden="true"
                  ></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button
                  class="carousel-control-next"
                  type="button"
                  data-bs-target="#photoCarousel"
                  data-bs-slide="next"
                >
                  <span
                    class="carousel-control-next-icon"
                    aria-hidden="true"
                  ></span>
                  <span class="visually-hidden">Next</span>
                </button>
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
