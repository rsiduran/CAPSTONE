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
            'description' => $_POST['description'],   
            'timestamp' => new DateTime('now', new DateTimeZone('Asia/Manila')),

        ];

        // Function to handle file upload
        function uploadFile($file, $firebaseService, $uploadFolder) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadPath = $uploadFolder . '/' . basename($file['name']);
                $url = $firebaseService->uploadFile($file['tmp_name'], $uploadPath); // Ensure this method exists
                return $url;
            }
            return null;
        }

        // Handle primary pet picture
        $petPictureUrl = uploadFile($_FILES['petPicture'], $firebaseService, 'pet-adoptions');
        if ($petPictureUrl) {
            $adoptionData['petPicture'] = $petPictureUrl;
        }

        // Upload other documents
        $adoptionData['medical'] = uploadFile($_FILES['medical'], $firebaseService, 'pet-medical');
        $adoptionData['spay'] = uploadFile($_FILES['spay'], $firebaseService, 'pet-spay');
        $adoptionData['vaccination'] = uploadFile($_FILES['vaccination'], $firebaseService, 'pet-vaccinations');

        // Handle additional photos
        $additionalPhotos = [];
        if (!empty($_FILES['additionalPhotos']['name'][0])) {
            foreach ($_FILES['additionalPhotos']['name'] as $index => $fileName) {
                $fileTempPath = $_FILES['additionalPhotos']['tmp_name'][$index];
                $fileUploadError = $_FILES['additionalPhotos']['error'][$index];

                if ($fileUploadError === UPLOAD_ERR_OK) {
                    $uploadPath = 'pet-additional/' . basename($fileName);
                    $photoUrl = $firebaseService->uploadFile($fileTempPath, $uploadPath);
                    $additionalPhotos[] = $photoUrl;
                }
            }
        }
        $adoptionData['additionalPhotos'] = $additionalPhotos;

        // Insert the adoption data into the 'adoption' collection
        $response = $firebaseService->insertDocument('adoption', $adoptionData);

        if (isset($response['name'])) {
          echo "<script>alert('Pet adoption entry created successfully with ID: " . basename($response['name']) . "');</script>";
        } else {
            error_log("Error creating pet adoption entry: " . json_encode($response));
            echo "Error creating pet adoption entry.";
        }
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Pet Adoption</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <style>
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
      <img src="../assets/images/logo.png" alt="WanderPets Logo">
      <h4>WanderPets</h4>
    </div>
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
    <a data-bs-toggle="collapse" href="#applicationMenu" role="button" aria-expanded="false" aria-controls="applicationMenu">
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
    <a data-bs-toggle="collapse" href="#historyMenu" role="button" aria-expanded="false" aria-controls="historyMenu">
      History
    </a>
    <div class="collapse" id="historyMenu">
      <a href="../history/missing_history.php" class="sub-link">Missing</a>
      <a href="../history/wandering_history.php" class="sub-link">Wandering</a>
      <a href="#adopted-history" class="sub-link">Adopted</a>
      <a href="../history/found_history.php" class="sub-link">Found</a>
    </div>
    <!-- Profile and Logout -->
    <div class="profile-section">
      <a href="#profile">Profile</a>
      <a href="login/logout.php">Logout</a>
    </div>
  </div>

<div class="main-content">
  <div class="form-container">
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
        <label for="additionalPhotos" class="form-label">Additional Photos</label>
        <input type="file" class="form-control" name="additionalPhotos[]" multiple>
      </div>
      <div class="mb-3">
        <label for="characteristic" class="form-label">Description</label>
        <input type="text" class="form-control" name="description" required>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

