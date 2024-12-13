<?php

$firebase = include('../../../config/firebase.php');
include('../../../config/auth.php');

$petid = $_GET['petid'] ?? null;

$petDetails = $firebase->getDocuments("adoptionApplication")[$petid] ?? null;

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

    /* Main content */
    .main-content {
      margin-left: 250px;
      padding: 20px;
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

    /* Form Styling */
    .form-container {
      background-color: #f9f9f9;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 0 auto;
    }

    .form-container h2 {
      color: #2e7d32;
      font-weight: bold;
    }

    .form-label {
      color: #555;
    }

    .form-control, .form-select {
      border-radius: 5px;
      border: 1px solid #ced4da;
    }

    .form-control:focus, .form-select:focus {
      border-color: #4caf50;
      box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }

    .btn-primary {
      background-color: #4caf50;
      border-color: #4caf50;
      transition: background 0.3s;
    }

    .btn-primary:hover {
      background-color: #3e8e41;
      border-color: #3e8e41;
    }
  
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="../../../assets/images/logo.png" alt="WanderPets Logo">
      <h4>WanderPets</h4>
    </div>
    <a href="../../../index.php">Dashboard</a>
    <a href="#inquiry">Inquiry</a>
    <a href="../../../users.php">Users</a>
    <a href="../../../postedPets.php">Posted Pets</a>
    <a href="../../../php/missing.php">Missing</a>
    <a href="../../../php/wandering.php">Wandering</a>
    <a href="../../../php/found.php">Found</a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="../../../php/adoptionList.php" class="sub-link">Pet Adoption List</a>
      <a href="../../../php/adoptedPets.php" class="sub-link">Adopted Pets</a>
      <a href="../../../php/addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
      Adoption Application
    </a>
    <div class="collapse" id="applicationMenu">
      <a href="../../../php/application/applicationPending.php" class="sub-link">Pending</a>
      <a href="../../../php/application/applicationReviewing.php" class="sub-link">Reviewing</a>
      <a href="../../../php/application/applicationApproved.php" class="sub-link">Approved</a>
      <a href="../../../php/application/applicationCompleted.php" class="sub-link">Completed</a>
      <a href="../../../php/application/applicationRejected.php" class="sub-link">Rejected</a>
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
    <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="historyMenu">
      History
    </a>
    <div class="collapse" id="historyMenu">
      <a href="../../../history/missing_history.php" class="sub-link">Missing</a>
      <a href="../../../history/wandering_history.php" class="sub-link">Wandering</a>
      <a href="#adopted-history" class="sub-link">Adopted</a>
      <a href="../../../history/found_history.php" class="sub-link">Found</a>
    </div>
    <!-- Profile and Logout -->
    <div class="profile-section">
      <a href="#profile">Profile</a>
      <a href="../../../php/login/logout.php">Logout</a>
    </div>    
  </div>


  <!-- Main Content -->
 <div class="main-content">
  <div class="container mt-4 p-5">
    <h1 class="text-center text-primary mb-4">Pet Application Form</h1>
    
    <form action="../updateApplication/update_applicationPending.php" method="POST">
      <input type="hidden" name="petid" value="<?= htmlspecialchars($petid) ?>">
      <input type="hidden" name="currentStatus" value="<?= htmlspecialchars($petDetails['applicationStatus'] ?? 'N/A') ?>">
      <button type="submit" class="btn btn-warning btn-sm mb-4">REVIEWING</button>

      <div class="row">
        <div class="col-md-6">
          <div class="card p-3 mb-4 shadow-sm">
            <h3 class="text-info">Person Information</h3>
            <p><strong>Transaction Number:</strong> <?= htmlspecialchars($petDetails['transactionNumber'] ?? 'N/A') ?></p>
            <p><strong>Application Status:</strong> <span class="status"><?= htmlspecialchars($petDetails['applicationStatus'] ?? 'N/A') ?></span></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?></p>
            <p><strong>Pronouns:</strong> <?= htmlspecialchars($petDetails['pronouns'] ?? 'N/A') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?></p>
            <p><strong>Birthdate:</strong> <?= htmlspecialchars($petDetails['birthdate'] ?? 'N/A') ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($petDetails['status'] ?? 'N/A') ?></p>
            <p><strong>Occupation:</strong> <?= htmlspecialchars($petDetails['occupation'] ?? 'N/A') ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($petDetails['company'] ?? 'N/A') ?></p>
            <p><strong>Work Hours:</strong> <?= htmlspecialchars($petDetails['workHours'] ?? 'N/A') ?></p>
            <p><strong>Salary Range:</strong> <?= htmlspecialchars($petDetails['salaryRange'] ?? 'N/A') ?></p>
            <p><strong>Allergic:</strong> <?= htmlspecialchars($petDetails['allergic'] ?? 'N/A') ?></p>
            <p><strong>Financial Responsibilities:</strong> <?= htmlspecialchars($petDetails['responsibleFinancial'] ?? 'N/A') ?></p>
            <p><strong>Grooming Responsibilities:</strong> <?= htmlspecialchars($petDetails['responsibleGrooming'] ?? 'N/A') ?></p>
            <p><strong>Meet Ups?:</strong> <?= htmlspecialchars($petDetails['meet'] ?? 'N/A') ?></p>
            <p><strong>Move?</strong> <?= htmlspecialchars($petDetails['move'] ?? 'N/A') ?></p>
            <p><strong>Ideal Pet:</strong> <?= htmlspecialchars($petDetails['idealPet'] ?? 'N/A') ?></p>
            <p><strong>Other Pets?:</strong> <?= htmlspecialchars($petDetails['otherPets'] ?? 'N/A') ?></p>
            <p><strong>Past Pets?:</strong> <?= htmlspecialchars($petDetails['pastPets'] ?? 'N/A') ?></p>
            <p><strong>Previously Adopted:</strong> <?= htmlspecialchars($petDetails['previouslyAdopted'] ?? 'N/A') ?></p>

            <h3 class="text-info">House Information</h3>
            <p><strong>Address:</strong> <?= htmlspecialchars($petDetails['address'] ?? 'N/A') ?></p>
            <p><strong>Rent?:</strong> <?= htmlspecialchars($petDetails['rent'] ?? 'N/A') ?></p>
            <p><strong>Building Type:</strong> <?= htmlspecialchars($petDetails['buildingType'] ?? 'N/A') ?></p>
            <p><strong>Live Alone?:</strong> <?= htmlspecialchars($petDetails['liveAlone'] ?? 'N/A') ?></p>
            <p><strong>Looking After?:</strong> <?= htmlspecialchars($petDetails['lookAfter'] ?? 'N/A') ?></p>
            <p><strong>Introduce Surroundings:</strong> <?= htmlspecialchars($petDetails['introduceSurroundings'] ?? 'N/A') ?></p>

            <p><strong>Posted Date:</strong> 
              <?= htmlspecialchars(
                is_numeric($petDetails['postedTimestamp']) && $petDetails['postedTimestamp'] > 0 
                ? date('Y-m-d H:i:s', (int)$petDetails['postedTimestamp']) 
                : date('Y-m-d H:i:s', time())
              ) ?>
            </p>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card p-3 mb-4 shadow-sm">
            <h3 class="text-info">Profile Pictures</h3>
            <div class="row">
              <div class="col-md-6 mb-3">
                <h4>Profile Picture</h4>
                <img src="<?= htmlspecialchars($petDetails['profilePicture'] ?? 'default-profile.jpg') ?>" alt="Profile Picture" class="img-fluid rounded" style="width: 100%; object-fit: cover; border-radius: 10px;">
              </div>
              <div class="col-md-6 mb-3">
                <h4>Valid ID</h4>
                <img src="<?= htmlspecialchars($petDetails['validID'] ?? 'default-id.jpg') ?>" alt="Valid ID" class="img-fluid rounded" style="width: 100%; object-fit: cover; border-radius: 10px;">
              </div>
            </div>

            <h4 class="text-info">House Photos</h4>
            <div class="row">
              <?php if (!empty($petDetails['homePhotos']) && is_array($petDetails['homePhotos'])): ?>
                <?php foreach ($petDetails['homePhotos'] as $photo): ?>
                  <div class="col-md-4 mb-3">
                    <img src="<?= htmlspecialchars($photo) ?>" alt="House Photo" class="img-fluid rounded" style="border: 2px solid #ccc; object-fit: cover;">
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No house photos available.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <h3 class="text-info">Contact Person Information</h3>
          <p><strong>Name:</strong> <?= htmlspecialchars($petDetails['contactFirstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['contactLastName'] ?? 'N/A') ?></p>
          <p><strong>Contact Number:</strong> <?= htmlspecialchars($petDetails['contactPhone'] ?? 'N/A') ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($petDetails['contactEmail'] ?? 'N/A') ?></p>
          <p><strong>Relationship:</strong> <?= htmlspecialchars($petDetails['contactRelationship'] ?? 'N/A') ?></p>
        </div>
      </div>

      <div class="row">
        <h2 class="text-center text-primary mb-4">Pet Records</h2>
        <div class="col-md-6">
          <h3 class="text-info">Medical Record</h3>
          <a href="<?= htmlspecialchars($petDetails['medical'] ?? 'default-medical.jpg') ?>" data-toggle="modal" data-target="#medicalModal">
            <img src="<?= htmlspecialchars($petDetails['medical'] ?? 'default-medical.jpg') ?>" alt="Medical Record" class="img-fluid rounded" style="width: 150px; height: 200px; object-fit: cover; border-radius: 10px;">
          </a>
        </div>
        <div class="col-md-6">
          <h3 class="text-info">Vaccination</h3>
          <a href="<?= htmlspecialchars($petDetails['vaccination'] ?? 'default-vaccination.jpg') ?>" data-toggle="modal" data-target="#vaccinationModal">
            <img src="<?= htmlspecialchars($petDetails['vaccination'] ?? 'default-vaccination.jpg') ?>" alt="Vaccination Record" class="img-fluid rounded" style="width: 150px; height: 200px; object-fit: cover; border-radius: 10px;">
          </a>
        </div>
        <div class="col-md-6">
          <h3 class="text-info">Spay/Neuter</h3>
          <a href="<?= htmlspecialchars($petDetails['spay'] ?? 'default-spay-neuter.jpg') ?>" data-toggle="modal" data-target="#spayNeuterModal">
            <img src="<?= htmlspecialchars($petDetails['spay'] ?? 'default-spay-neuter.jpg') ?>" alt="Spay/Neuter Record" class="img-fluid rounded" style="width: 150px; height: 200px; object-fit: cover; border-radius: 10px;">
          </a>
        </div>
      </div>
    </form>
  </div>
 </div>



<!-- Modals for Full Image View -->
<!-- Medical Record Modal -->
<div class="modal fade" id="medicalModal" tabindex="-1" aria-labelledby="medicalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="medicalModalLabel">Medical Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="<?= htmlspecialchars($petDetails['medicalRecord'] ?? 'default-medical.jpg') ?>" 
             alt="Medical Record" 
             class="img-fluid">
      </div>
    </div>
  </div>
</div>

<!-- Vaccination Modal -->
<div class="modal fade" id="vaccinationModal" tabindex="-1" aria-labelledby="vaccinationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vaccinationModalLabel">Vaccination Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="<?= htmlspecialchars($petDetails['vaccination'] ?? 'default-vaccination.jpg') ?>" 
             alt="Vaccination Record" 
             class="img-fluid">
      </div>
    </div>
  </div>
</div>

<!-- Spay/Neuter Modal -->
<div class="modal fade" id="spayNeuterModal" tabindex="-1" aria-labelledby="spayNeuterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="spayNeuterModalLabel">Spay/Neuter Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="<?= htmlspecialchars($petDetails['spay'] ?? 'default-spay-neuter.jpg') ?>" 
             alt="Spay/Neuter Record" 
             class="img-fluid">
      </div>
    </div>
  </div>
</div>



</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
