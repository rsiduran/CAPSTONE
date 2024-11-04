<?php

$firebase = include('config/firebase.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <a href="index.php">Dashboard</a>
  <a href="php/missing.php">Inquiry</a>
  <a href="php/missing.php">Missing</a>
  <a href="php/wandering.php">Wandering</a>
  <a href="php/found.php">Found</a>
  <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption
  </a>
  <div class="collapse" id="adoptionMenu">
    <a href="#petAdoptionList" class="sub-link">Pet Adoption List</a>
    <a href="#adoptedPets" class="sub-link">Adopted Pets</a>
    <a href="addPetAdoption.php" class="sub-link">Add Pet</a>
  </div>
  <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    Adoption Application
  </a>
  <div class="collapse" id="applicationMenu">
    <a href="php/applicationPending.php" class="sub-link">Pending</a>
    <a href="php/applicationReviewing.php" class="sub-link">Reviewing</a>
    <a href="php/applicationApproved.php" class="sub-link">Approved</a>
    <a href="php/applicationCompleted.php" class="sub-link">Completed</a>
    <a href="php/applicationRejected.php" class="sub-link">Rejected</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="php/rescuePending.php" class="sub-link">Pending</a>
    <a href="php/rescueReviewing.php" class="sub-link">Reviewing</a>
    <a href="php/rescueOngoing.php" class="sub-link">Ongoing</a>
    <a href="php/rescueRescued.php" class="sub-link">Rescued</a>
    <a href="php/rescueDeclined.php" class="sub-link">Declined</a>
  </div>
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="history/missing_history.php" class="sub-link">Missing</a>
    <a href="history/wandering_history.php" class="sub-link">Wandering</a>
    <a href="history/wandering_history.php" class="sub-link">Adopted</a>
    <a href="history/found_history.php" class="sub-link">Found</a>
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

<!-- Main Content -->
<div class="main-content">
  <div class="container-fluid mt-5 pt-3">
    <h1>Admin Dashboard</h1>
    <p>Welcome to your dashboard. Below is a summary of key sections:</p>
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Inquiries</h5>
            <p class="card-text">120</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Missing Reports</h5>
            <p class="card-text">50</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Adoption Applications</h5>
            <p class="card-text">200</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
