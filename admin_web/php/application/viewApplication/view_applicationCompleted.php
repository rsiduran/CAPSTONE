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
  <link rel="stylesheet" href="../../../assets/style.css">
</head>
<body>

<div class="sidebar">
  <a href="../../../index.php">Dashboard</a>
  <a href="#inquiry.php">Inquiry</a>
  <a href="../../missing.php">Missing</a>
  <a href="../../wandering.php">Wandering</a>
  <a href="../../found.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
    <a href="#petAdoptionList" class="sub-link">Pet Adoption List</a>
    <a href="#adoptedPets" class="sub-link">Adopted Pets</a>
    <a href="../../addPetAdoption.php" class="sub-link">Add Pet</a>
  </div>
  <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption Application
  </a>
  <div class="collapse" id="applicationMenu">
    <a href="../applicationPending.php" class="sub-link">Pending</a>
    <a href="../applicationReviewing.php" class="sub-link">Reviewing</a>
    <a href="../applicationApproved.php" class="sub-link">Approved</a>
    <a href="../applicationCompleted.php" class="sub-link">Completed</a>
    <a href="../applicationRejected.php" class="sub-link">Rejected</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="../../rescue/rescuePending.php" class="sub-link">Pending</a>
    <a href="../../rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
    <a href="../../rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
    <a href="../../rescue/rescueRescued.php" class="sub-link">Rescued</a>
    <a href="../../rescue/rescueDeclined.php" class="sub-link">Declined</a>
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

<div class="container main-content">
<div class="back-button-container">
    <a href="../applicationCompleted.php" class="back-button">Back</a>
</div>

<div class="container">
  <h1>Pet Application Form</h1>
  
    <div class="row mb-4">
      <div class="col-md-6">
        <h3>Person Information</h3>
        <p><strong>Application Status:</strong> <span id="status" class="status"><?= htmlspecialchars($petDetails['applicationStatus'] ?? 'N/A') ?></span></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?></p>
        <p><strong>Pronouns:</strong> <?= htmlspecialchars($petDetails['pronouns'] ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?></p>
        <p><strong>Birthdate:</strong> <?= htmlspecialchars($petDetails['birthdate'] ?? 'N/A') ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($petDetails['status'] ?? 'N/A') ?></p>
        <p><strong>Socials:</strong> <?= htmlspecialchars($petDetails['socials'] ?? 'N/A') ?></p>
        <p><strong>Occupation:</strong> <?= htmlspecialchars($petDetails['occupation'] ?? 'N/A') ?></p>
        <p><strong>Company:</strong> <?= htmlspecialchars($petDetails['company'] ?? 'N/A') ?></p>
        <p><strong>Work Hours:</strong> <?= htmlspecialchars($petDetails['workHours'] ?? 'N/A') ?></p>
        <p><strong>Allergic:</strong> <?= htmlspecialchars($petDetails['allergic'] ?? 'N/A') ?></p>
        <p><strong>Financial Responsibillities:</strong> <?= htmlspecialchars($petDetails['responsibleFinancial'] ?? 'N/A') ?></p>
        <p><strong>Grroming Responsibillities:</strong> <?= htmlspecialchars($petDetails['responsibleGrooming'] ?? 'N/A') ?></p>
        <p><strong>Meet ups?:</strong> <?= htmlspecialchars($petDetails['meet'] ?? 'N/A') ?></p>
        <p><strong>Move?</strong> <?= htmlspecialchars($petDetails['move'] ?? 'N/A') ?></p>
        <p><strong>Ideal Pet:</strong> <?= htmlspecialchars($petDetails['idealPet'] ?? 'N/A') ?></p>
        <p><strong>Other Pets?:</strong> <?= htmlspecialchars($petDetails['otherPets'] ?? 'N/A') ?></p>
        <p><strong>Past Pets?:</strong> <?= htmlspecialchars($petDetails['pastPets'] ?? 'N/A') ?></p>
        <p><strong>Previously Adopted:</strong> <?= htmlspecialchars($petDetails['previouslyAdopted'] ?? 'N/A') ?></p>
    
        <h3>House Information</h3>
        <p><strong>Address:</strong> <?= htmlspecialchars($petDetails['address'] ?? 'N/A') ?></p>
        <p><strong>Rent?:</strong> <?= htmlspecialchars($petDetails['rent'] ?? 'N/A') ?></p>
        <p><strong>Building Type:</strong> <?= htmlspecialchars($petDetails['buildingType'] ?? 'N/A') ?></p>
        <p><strong>Live Alone?:</strong> <?= htmlspecialchars($petDetails['liveAlone'] ?? 'N/A') ?></p>
        <p><strong>Looking After?:</strong> <?= htmlspecialchars($petDetails['lookAfter'] ?? 'N/A') ?></p>
        <p><strong>Introduce Surroundings:</strong> <?= htmlspecialchars($petDetails['introduceSurroundings'] ?? 'N/A') ?></p>

        <p>
    <strong>Posted Date:</strong> 
    <?= htmlspecialchars(
        is_numeric($petDetails['postedTimestamp']) && $petDetails['postedTimestamp'] > 0 
        ? date('Y-m-d H:i:s', (int)$petDetails['postedTimestamp']) 
        : date('Y-m-d H:i:s', time())
    ) ?>
</p>
      </div>

      <!-- Right Side: Pictures -->
      <div class="col-md-6">
        <div class="row mb-4">
          <div class="col-6">
            <h3>Profile Picture</h3>
            <img src="<?= htmlspecialchars($petDetails['profilePicture'] ?? 'default-profile.jpg') ?>" 
                 alt="Profile Picture" 
                 class="pet-image" 
                 style="width: 2in; height: 2in; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
          </div>
          <div class="col-6">
            <h3>Valid ID</h3>
            <img src="<?= htmlspecialchars($petDetails['validID'] ?? 'default-id.jpg') ?>" 
                 alt="Valid ID" 
                 class="pet-image" 
                 style="width: 4in; height: 2in; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
          </div>
        </div>
        <h3>House Picture</h3>
        <img src="<?= htmlspecialchars($petDetails['homePhotos'] ?? 'default-house.jpg') ?>" 
             alt="House Picture" 
             class="pet-image" 
             style="width: 100%; height: auto; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <h3>Contact Person Information</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($petDetails['contactFirstName'] ?? 'N/A') ?><?= htmlspecialchars($petDetails['contactLastName'] ?? 'N/A') ?></p>
        <p><strong>Contact Number:</strong> <?= htmlspecialchars($petDetails['contactPhone'] ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($petDetails['contactEmail'] ?? 'N/A') ?></p>
        <p><strong>Relationship:</strong> <?= htmlspecialchars($petDetails['contactRelationship'] ?? 'N/A') ?></p>
      </div>
    </div>

    <div class="row mb-4">
      <h2>Pet Records</h2>
      <div class="col-md-6">
        <h3>Medical Record</h3>
        <a href="<?= htmlspecialchars($petDetails['medical'] ?? 'default-medical.jpg') ?>" data-toggle="modal" data-target="#medicalModal">
          <img src="<?= htmlspecialchars($petDetails['medical'] ?? 'default-medical.jpg') ?>" 
               alt="Medical Record" 
               class="pet-image" 
               style="width: 150px; height: 200px; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
        </a>
      </div>
      <div class="col-md-6">
        <h3>Vaccination</h3>
        <a href="<?= htmlspecialchars($petDetails['vaccination'] ?? 'default-vaccination.jpg') ?>" data-toggle="modal" data-target="#vaccinationModal">
          <img src="<?= htmlspecialchars($petDetails['vaccination'] ?? 'default-vaccination.jpg') ?>" 
               alt="Vaccination Record" 
               class="pet-image" 
               style="width: 150px; height: 200px; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
        </a>
      </div>
      <div class="col-md-6">
        <h3>Spay/Neuter</h3>
        <a href="<?= htmlspecialchars($petDetails['spay'] ?? 'default-spay-neuter.jpg') ?>" data-toggle="modal" data-target="#spayNeuterModal">
          <img src="<?= htmlspecialchars($petDetails['spay'] ?? 'default-spay-neuter.jpg') ?>" 
               alt="Spay/Neuter Record" 
               class="pet-image" 
               style="width: 150px; height: 200px; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
        </a>
      </div>
      <div class="col-md-6">
        <h3>Personal Info of Pet</h3>
        <div class="col-6">
            <h3>Profile Picture</h3>
            <img src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-profile.jpg') ?>" 
                 alt="Pet Picture" 
                 class="pet-image" 
                 style="width: 2in; height: 2in; object-fit: cover; border: 2px solid #ccc; border-radius: 8px;">
          </div>
        <p><strong>Name:</strong> <?= htmlspecialchars($petDetails['name'] ?? 'N/A') ?></p>
        <p><strong>Breed:</strong> <?= htmlspecialchars($petDetails['breed'] ?? 'N/A') ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($petDetails['age'] ?? 'N/A') ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($petDetails['gender'] ?? 'N/A') ?></p>
        <p><strong>Size:</strong> <?= htmlspecialchars($petDetails['size'] ?? 'N/A') ?></p>
        <p><strong>Pet Type:</strong> <?= htmlspecialchars($petDetails['petType'] ?? 'N/A') ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($petDetails['description'] ?? 'N/A') ?></p>
      </div>
    </div>

    </div>
  </form>
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
