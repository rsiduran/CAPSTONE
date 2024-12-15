<?php

$firebase = include('config/firebase.php');

include('config/auth.php');

$missingReport = $firebase->getCollectionCount('missing');
$wanderingReport = $firebase->getCollectionCount('wandering');
$foundReport = $firebase->getCollectionCount('found');
$rescueReport = $firebase->getCollectionCount('rescue');
$adoptionApplication = $firebase->getCollectionCount('adoptionApplication');
$adoptedPets = $firebase->getCollectionCount('adopted');

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

    .main-content {
      margin-left: 250px;
      padding: 20px;
    }

    .card {
      background: #f8f9fa;
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .dash-box{
      text-decoration: none;
      color:black;
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
      <img src="assets/images/logo.png" alt="WanderPets Logo">
      <h4>Supremo Furbabies</h4>
    </div>
    <a href="index.php">Dashboard</a>
    <a href="#inquiry.php">Inquiry</a>
    <a href="php/users.php">Users</a>
    <a href="php/missing.php">Missing</a>
    <a href="php/wandering.php">Wandering</a>
    <a href="php/found.php">Found</a>
    <a data-bs-toggle="collapse" href="#adoptionMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">Adoption</a>
    <div class="collapse" id="adoptionMenu">
      <a href="php/adoptionList.php" class="sub-link">Pet Adoption List</a>
      <a href="php/adoptedPets.php" class="sub-link">Adopted Pets</a>
      <a href="php/addPetAdoption.php" class="sub-link">Add Pet</a>
    </div>
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">Adoption Application</a>
    <div class="collapse" id="applicationMenu">
      <a href="php/application/applicationPending.php" class="sub-link">Pending</a>
      <a href="php/application/applicationReviewing.php" class="sub-link">Reviewing</a>
      <a href="php/application/applicationApproved.php" class="sub-link">Approved</a>
      <a href="php/application/applicationCompleted.php" class="sub-link">Completed</a>
      <a href="php/application/applicationRejected.php" class="sub-link">Rejected</a>
    </div>
    <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">Rescue</a>
    <div class="collapse" id="rescueMenu">
      <a href="php/rescue/rescuePending.php" class="sub-link">Pending</a>
      <a href="php/rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
      <a href="php/rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
      <a href="php/rescue/rescueRescued.php" class="sub-link">Rescued</a>
      <a href="php/rescue/rescueDeclined.php" class="sub-link">Declined</a>
    </div>
    <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="historyMenu">History</a>
    <div class="collapse" id="historyMenu">
      <a href="history/missing_history.php" class="sub-link">Missing</a>
      <a href="history/wandering_history.php" class="sub-link">Wandering</a>
      <a href="#history/wandering_history" class="sub-link">Adopted</a>
      <a href="history/found_history.php" class="sub-link">Found</a>
    </div>
    <!-- Profile and Logout -->
    <div class="profile-section">
      <a href="#profile">Profile</a>
      <a href="php/login/logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid mt-5 pt-3">
      <h1>Admin Dashboard</h1>
      <p>Welcome to your dashboard. Below is a summary of key sections:</p>
      <div class="row">
        <div class="col-md-4">
          <div class="card mb-3" ><a href ="php/missing.php" class="dash-box"> 
            <div class="card-body">
              <h5 class="card-title" >Missing Reports</h5>
              <p class="card-text"><?php echo $missingReport; ?></p></a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-3" ><a href ="php/wandering.php" class="dash-box">
            <div class="card-body">
              <h5 class="card-title">Wandering Reports</h5>
              <p class="card-text"><?php echo $wanderingReport; ?></p></a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body"><a href ="php/found.php" class="dash-box">
              <h5 class="card-title">Found Reports</h5>
              <p class="card-text"><?php echo $foundReport; ?></p></a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body"><a href ="php/application/applicationPending.php" class="dash-box">
              <h5 class="card-title">Adoption Applications</h5>
              <p class="card-text"><?php echo $adoptionApplication; ?></p></a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body"><a href ="php/rescue/rescuePending.php" class="dash-box">
              <h5 class="card-title">Rescue Reports</h5>
              <p class="card-text"><?php echo $rescueReport; ?></p></a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body"><a href ="php/adoptedPets.php" class="dash-box">
              <h5 class="card-title">Adopted Pets</h5>
              <p class="card-text"><?php echo $adoptedPets; ?></p></a>
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
