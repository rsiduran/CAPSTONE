<?php

$firebase = include('../../config/firebase.php');
include('../../config/auth.php');

$petid = $_GET['petid'] ?? null;

$petDetails = $firebase->getDocuments("missing")[$petid] ?? null;

if (!$petDetails) {
    die("Pet not found.");
}
?>
<div class="sidebar">
  <a href="../../index.php">Dashboard</a>
  <a href="#inquiry.php">Inquiry</a>
  <a href="../missing.php">Missing</a>
  <a href="../wandering.php">Wandering</a>
  <a href="../found.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
    <a href="#petAdoptionList" class="sub-link">Pet Adoption List</a>
    <a href="#adoptedPets" class="sub-link">Adopted Pets</a>
    <a href="../addPetAdoption.php" class="sub-link">Add Pet</a>
  </div>
  <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption Application
  </a>
  <div class="collapse" id="applicationMenu">
    <a href="../application/applicationPending.php" class="sub-link">Pending</a>
    <a href="../application/applicationReviewing.php" class="sub-link">Reviewing</a>
    <a href="../application/applicationApproved.php" class="sub-link">Approved</a>
    <a href="../application/applicationCompleted.php" class="sub-link">Completed</a>
    <a href="../application/applicationRejected.php" class="sub-link">Rejected</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="../rescue/rescuePending.php" class="sub-link">Pending</a>
    <a href="../rescueReviewing.php" class="sub-link">Reviewing</a>
    <a href="../rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
    <a href="../rescue/rescueRescued.php" class="sub-link">Rescued</a>
    <a href="../rescue/rescueDeclined.php" class="sub-link">Declined</a>
  </div>
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="../../history/missing_history.php" class="sub-link">Missing</a>
    <a href="../../history/wandering_history.php" class="sub-link">Wandering</a>
    <a href="#history/wandering_history" class="sub-link">Adopted</a>
    <a href="../../history/found_history.php" class="sub-link">Found</a>
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
          <a class="nav-link" href="../login/logout.php">Logout</a>
        </li>
      </ul> 
    </div>
  </div>
</nav>


<div class="container main-content">
<div class="back-button-container">
    <a href="javascript:history.back()" class="back-button">Back</a>
</div>
  <div class="row">
    <div class="col-md-6">
      <img src="<?= htmlspecialchars($petDetails['petPicture'] ?? 'default-pet.jpg') ?>" alt="<?= htmlspecialchars($petDetails['name'] ?? 'Pet Image') ?>" class="pet-image">
      <img src="<?= htmlspecialchars($petDetails['profilePicture'] ?? 'default-pet.jpg') ?>"class="pet-image">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($petDetails['name'] ?? 'N/A') ?></h2>
      <p><strong>Breed:</strong> <?= htmlspecialchars($petDetails['breed'] ?? 'N/A') ?></p>
      <p><strong>Age:</strong> <?= htmlspecialchars($petDetails['age'] ?? 'N/A') ?></p>
      <p><strong>Gender:</strong> <?= htmlspecialchars($petDetails['gender'] ?? 'N/A') ?></p>
      <p><strong>Size:</strong> <?= htmlspecialchars($petDetails['size'] ?? 'N/A') ?></p>
      <p><strong>City:</strong> <?= htmlspecialchars($petDetails['city'] ?? 'N/A') ?></p>
      <p><strong>Street Number:</strong> <?= htmlspecialchars($petDetails['streetNumber'] ?? 'N/A') ?></p>
      <p><strong>Address</strong> <?= htmlspecialchars($petDetails['address'] ?? 'N/A') ?></p>
      <p><strong>Message:</strong> <?= htmlspecialchars($petDetails['message'] ?? 'N/A') ?></p> 
      <p><strong>Pet Type:</strong> <?= htmlspecialchars($petDetails['petType'] ?? 'N/A') ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($petDetails['postType'] ?? 'N/A') ?></p>
      <p><strong>Characteristic:</strong> <?= htmlspecialchars($petDetails['characteristic'] ?? 'N/A') ?></p>
      <p>
    <strong>Posted Date:</strong> 
    <?= htmlspecialchars(
        is_numeric($petDetails['timestamp']) && $petDetails['timestamp'] > 0 
        ? date('Y-m-d H:i:s', (int)$petDetails['timestamp']) 
        : date('Y-m-d H:i:s', time())
    ) ?>
</p>
      
      <h2>OWNER INFORMATION</h2>
      <p><strong>Owner Name:</strong> <?= htmlspecialchars($petDetails['firstName'] ?? 'N/A') ?> <?= htmlspecialchars($petDetails['lastName'] ?? 'N/A') ?></p>  
      <p><strong>Email:</strong> <?= htmlspecialchars($petDetails['email'] ?? 'N/A') ?></p>
      <p><strong>Phone Number:</strong> <?= htmlspecialchars($petDetails['phoneNumber'] ?? 'N/A') ?></p>
    </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
