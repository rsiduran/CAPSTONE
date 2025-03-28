<?php

$firebase = include('../../../config/firebase.php');
include('../../../config/auth.php');

$petid = $_GET['petid'] ?? null;

include('../../../config/counts.php');

$usersCount = $firebase->getCollectionCount('users');

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
      <img src="../../../assets/images/logo.png" alt="WanderPets Logo">
      <h4>Supremo Furbabies</h4>
    </div>
    <a href="../../../index.php">Dashboard</a>
    <a href="#inquiry">Inquiry</a>
    <a href="../../../users.php"><span class="badge1 "><?php echo $usersCount ?></span> Users</a>
    <a href="../../../php/missing.php">Missing <span class="badge bg-danger"><?= $unviewedCounts['missing'] ?? 0 ?></span></a>
    <a href="../../../php/wandering.php">Wandering <span class="badge bg-danger"><?= $unviewedCounts['wandering'] ?? 0 ?></span></a>
    <a href="../../../php/found.php">Found <span class="badge bg-danger"><?= $unviewedCounts['found'] ?? 0 ?></span></a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
      Adoption
    </a>
    <div class="collapse" id="adoptionMenu">
      <a href="../../../php/adoptionList.php" class="sub-link">Pet Adoption List <span class="badge bg-danger"><?= $unviewedCounts['adoption'] ?? 0 ?></span></a>
      <a href="../../../php/adoptedPets.php" class="sub-link">Adopted Pets</a>
      <a href="../../../php/addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
      Adoption Application
    </a>
    <div class="collapse" id="applicationMenu">
      <a href="../../../php/application/applicationPending.php" class="sub-link">Pending <span class="badge bg-danger"><?= $adoptionCounts['PENDING'] ?? 0 ?></span></a>
      <a href="../../../php/application/applicationReviewing.php" class="sub-link">Reviewing <span class="badge bg-danger"><?= $adoptionCounts['REVIEWING'] ?? 0 ?></span></a>
      <a href="../../../php/application/applicationApproved.php" class="sub-link">Approved <span class="badge bg-danger"><?= $adoptionCounts['APPROVED'] ?? 0 ?></span></a>
      <a href="../../../php/application/applicationCompleted.php" class="sub-link">Completed <span class="badge bg-danger"><?= $adoptionCounts['COMPLETED'] ?? 0 ?></span></a>
      <a href="../../../php/application/applicationRejected.php" class="sub-link">Rejected <span class="badge bg-danger"><?= $adoptionCounts['REJECTED'] ?? 0 ?></span></a>
    </div>
    <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
      Rescue
    </a>
    <div class="collapse" id="rescueMenu">
      <a href="../../../php/rescue/rescuePending.php" class="sub-link">Pending <span class="badge bg-danger"><?= $rescueCounts['PENDING'] ?? 0 ?></span></a>
      <a href="../../../php/rescue/rescueReviewing.php" class="sub-link">Reviewing <span class="badge bg-danger"><?= $rescueCounts['REVIEWING'] ?? 0 ?></span></a>
      <a href="../../../php/rescue/rescueOngoing.php" class="sub-link">Ongoing <span class="badge bg-danger"><?= $rescueCounts['ONGOING'] ?? 0 ?></span></a>
      <a href="../../../php/rescue/rescueRescued.php" class="sub-link">Rescued <span class="badge bg-danger"><?= $rescueCounts['RESCUED'] ?? 0 ?></span></a>
      <a href="../../../php/rescue/rescueDeclined.php" class="sub-link">Declined <span class="badge bg-danger"><?= $rescueCounts['DECLINED'] ?? 0 ?></span></a>
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
    
    <form method="POST">
      <input type="hidden" name="petid" value="<?= htmlspecialchars($petid) ?>">
      <input type="hidden" name="currentStatus" value="<?= htmlspecialchars($petDetails['applicationStatus'] ?? 'N/A') ?>">

      <div class="row">
  <div class="col-md-6">
    <div class="card p-3 mb-4 shadow-sm">
      <h3 class="text-info">Person Information</h3>
      <label><strong>Transaction Number:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['transactionNumber'] ?? 'N/A') ?>" disabled>

      <label><strong>Application Status:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['applicationStatus'] ?? 'N/A') ?>" disabled>

      <label><strong>Name:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?>" disabled>

      <label><strong>Pronouns:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['pronouns'] ?? 'N/A') ?>" disabled>

      <label><strong>Email:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?>" disabled>

      <label><strong>Phone Number:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?>" disabled>

      <label><strong>Birthdate:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['birthdate'] ?? 'N/A') ?>" disabled>

      <label><strong>Status:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['status'] ?? 'N/A') ?>" disabled>

      <label><strong>Occupation:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['occupation'] ?? 'N/A') ?>" disabled>

      <label><strong>Company:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['company'] ?? 'N/A') ?>" disabled>

      <label><strong>How many hours in an average workday will your pet be left alone?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['workHours'] ?? 'N/A') ?>" disabled>

      <label><strong>What is his/her monthly salary range?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['salaryRange'] ?? 'N/A') ?>" disabled>

      <label><strong>Have you adopted from Supremo Furbabies before?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['previouslyAdopted'] ?? 'N/A') ?>" disabled>

      <label><strong>Are any members of your household allergic to animals?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['allergic'] ?? 'N/A') ?>" disabled>

      <label><strong>Who will be financially responsible for your pet’s needs?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['responsibleFinancial'] ?? 'N/A') ?>" disabled>

      <label><strong>Who will be responsible for feeding, grooming, and generally caring for your pet?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['responsibleGrooming'] ?? 'N/A') ?>" disabled>

      <label><strong>Meet Ups?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['meet'] ?? 'N/A') ?>" disabled>

      <label><strong>What happens to your pet if or when you move?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['move'] ?? 'N/A') ?>" disabled>

      <label><strong>Describe your ideal Pet:</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['idealPet'] ?? 'N/A') ?>" disabled>

      <label><strong>Do you have other pets?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['otherPets'] ?? 'N/A') ?>" disabled>

      <label><strong>Have you had pets in the past?</strong></label>
      <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['pastPets'] ?? 'N/A') ?>" disabled>

      <div class="row">
        <div class="col-md-6">
          <h3 class="text-info">Contact Person Information</h3>
          
          <label><strong>Name:</strong></label>
          <input type="text" class="form-control mb-2" 
                value="<?= htmlspecialchars($petDetails['contactFirstName'] ?? 'N/A') . ' ' . htmlspecialchars($petDetails['contactLastName'] ?? 'N/A') ?>" 
                disabled>

          <label><strong>Contact Number:</strong></label>
          <input type="text" class="form-control mb-2" 
                value="<?= htmlspecialchars($petDetails['contactPhone'] ?? 'N/A') ?>" 
                disabled>

          <label><strong>Email:</strong></label>
          <input type="text" class="form-control mb-2" 
                value="<?= htmlspecialchars($petDetails['contactEmail'] ?? 'N/A') ?>" 
                disabled>

          <label><strong>Relationship:</strong></label>
          <input type="text" class="form-control mb-2" 
                value="<?= htmlspecialchars($petDetails['contactRelationship'] ?? 'N/A') ?>" 
                disabled>
        </div>
    </div>
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
            <pre>Photo attachment of home. This has replaced on-site ocular inspections.
  1. Front of the house
  2. Street photo
  3. Living room
  4. Dining Area
  5. Kitchen 
  6. Bedroom/s (if your pet will have access)
  7. Windows (if adopting a cat)
  8. Front & backyard (if adopting a dog)
            </pre>
            <div class="row">
              <?php if (!empty($petDetails['homePhotos']) && is_array($petDetails['homePhotos'])): ?>
                <?php foreach ($petDetails['homePhotos'] as $photo): ?>
                  <div class="col-md-4 mb-3">
                    <img src="<?= htmlspecialchars($photo) ?>" alt="House Photo"   class="img-fluid rounded" style="border: 2px solid #ccc; object-fit: cover;">
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No house photos available.</p><p><strong>Name:</strong> <?= htmlspecialchars($petDetails['name'] ?? 'N/A') ?></p>
              <?php endif; ?>
            </div>
            <h4 class="text-info">Pet Information</h4>
            <div class="col-md-6 mb-3">
                <img src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-profile.jpg') ?>" alt="Profile Picture" class="img-fluid rounded" style="width: 100%; object-fit: cover; border-radius: 10px;">
              </div>

              <label><strong>Name:</strong></label>
              <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['name'] ?? 'N/A') ?>" disabled>

              <label><strong>Breed:</strong></label>
              <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['breed'] ?? 'N/A') ?>" disabled>

              <label><strong>Age:</strong></label>
              <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['age'] ?? 'N/A') ?>" disabled>

              <label><strong>Gender:</strong></label>
              <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['gender'] ?? 'N/A') ?>" disabled>

              <label><strong>Size:</strong></label>
              <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['size'] ?? 'N/A') ?>" disabled>

              <label><strong>Type:</strong></label>
              <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($petDetails['petType'] ?? 'N/A') ?>" disabled>

              <label><strong>Description:</strong></label>
              <textarea class="form-control mb-2" rows="3" disabled><?= htmlspecialchars($petDetails['description'] ?? 'N/A') ?></textarea>
              
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

</div>
          </div>
        </div>
      </div>

    </form>
  </div>
 </div>



</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
