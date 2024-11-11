<?php
require '../config/firebase.php'; 
include('../config/auth.php');

try {
    // Initialize FirebaseService with the configuration
    $firebaseService = new FirebaseService($config);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve data from the form
        $adoptionData = [
            'name' => $_POST['name'],
            'breed' => $_POST['breed'],
            'age' => $_POST['age'],
            'gender' => $_POST['gender'],
            'size' => $_POST['size'], 
            'petType' => $_POST['petType'],
            'characteristic' => $_POST['characteristic'],   
            'message' => $_POST['message'],
            'timestamp' => (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z')
        ];

        // Handle file uploads
        $uploadedFiles = [];

        function uploadFile($file, $firebaseService) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadPath = 'pet-adoptions/' . basename($file['name']);
                $url = $firebaseService->uploadFile($file['tmp_name'], $uploadPath); // Ensure this method exists
                return $url;
            }
            return null;
        }

        $petPictureUrl = uploadFile($_FILES['petPicture'], $firebaseService);
        if ($petPictureUrl) {
            $adoptionData['petPicture'] = $petPictureUrl;
        }

        // Upload other documents
        $uploadedFiles['medical'] = uploadFile($_FILES['medical'], $firebaseService);
        $uploadedFiles['spay'] = uploadFile($_FILES['spay'], $firebaseService);
        $uploadedFiles['vaccination'] = uploadFile($_FILES['vaccination'], $firebaseService);

        $adoptionData['medicalRecords'] = $uploadedFiles['medical'];
        $adoptionData['spayCertificate'] = $uploadedFiles['spay'];
        $adoptionData['vaccinationRecords'] = $uploadedFiles['vaccination'];

        // Insert the adoption data into the 'adoption' collection
        $response = $firebaseService->insertDocument('adoption', $adoptionData);

        if (isset($response['name'])) {
            echo "Pet adoption entry created successfully with ID: " . basename($response['name']);
        } else {
            error_log("Error creating pet adoption entry: " . json_encode($response));
            echo "Error creating pet adoption entry.";
        }
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Pet Adoption</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="sidebar">
  <a href="../index.php">Dashboard</a>
  <a href="#inquiry">Inquiry</a>
  <a href="missing.php">Missing</a>
  <a href="wandering.php">Wandering</a>
  <a href="found.php">Found</a>
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
    <a href="application/applicationPending.php" class="sub-link">Pending</a>
    <a href="application/applicationReviewing.php" class="sub-link">Reviewing</a>
    <a href="application/applicationApproved.php" class="sub-link">Approved</a>
    <a href="application/applicationCompleted.php" class="sub-link">Completed</a>
    <a href="application/applicationRejected.php" class="sub-link">Rejected</a>
  </div>
  <a data-bs-toggle="collapse" href="#rescueMenu" role="button" aria-expanded="false" aria-controls="rescueMenu">
    Rescue
  </a>
  <div class="collapse" id="rescueMenu">
    <a href="rescue/rescuePending.php" class="sub-link">Pending</a>
    <a href="rescue/rescueReviewing.php" class="sub-link">Reviewing</a>
    <a href="rescue/rescueOngoing.php" class="sub-link">Ongoing</a>
    <a href="rescue/rescueRescued.php" class="sub-link">Rescued</a>
    <a href="rescue/rescueDeclined.php" class="sub-link">Declined</a>
  </div>
  <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="adoptionMenu">
    History
  </a>
  <div class="collapse" id="historyMenu">
    <a href="../history/missing_history.php" class="sub-link">Missing</a>
    <a href="../history/wandering_history.php" class="sub-link">Wandering</a>
    <a href="#adopted-history" class="sub-link">Adopted</a>
    <a href="../history/found_history.php" class="sub-link">Found</a>
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
          <a class="nav-link" href="login/logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2>Add Pet Adoption</h2>
  <form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>
    <div class="mb-3">
      <label for="age" class="form-label">Age</label>
      <input type="text" class="form-control" name="age" required>
    </div>
    <div class="mb-3">
      <label for="breed" class="form-label">Breed</label>
      <input type="text" class="form-control" name="breed" required>
    </div>
    <div class="mb-3">
      <label for="petPicture" class="form-label">Pet Picture</label>
      <input type="file" class="form-control" name="petPicture" required>
    </div>
    <div class="mb-3">
      <label for="characteristic" class="form-label">Characteristic</label>
      <input type="text" class="form-control" name="characteristic" required>
    </div>
    <div class="mb-3">
      <label for="gender" class="form-label">Gender</label>
      <select class="form-select" name="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="medical" class="form-label">Medical Records</label>
      <input type="file" class="form-control" name="medical" required>
    </div>
    <div class="mb-3">
      <label for="spay" class="form-label">Spay Certificate</label>
      <input type="file" class="form-control" name="spay" required>
    </div>
    <div class="mb-3">
      <label for="vaccination" class="form-label">Vaccination Records</label>
      <input type="file" class="form-control" name="vaccination" required>
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea class="form-control" name="message" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label for="size" class="form-label">Size</label>
      <input type="text" class="form-control" name="size" required>
    </div>
    <div class="mb-3">
      <label for="petType" class="form-label">Pet Type</label>
      <input type="text" class="form-control" name="petType" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
