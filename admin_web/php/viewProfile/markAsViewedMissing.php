<?php
// Assuming you have the Firebase configuration already set up
$firebase = include('../../config/firebase.php');

if (isset($_GET['petid']) && isset($_GET['collection'])) {
    // Get the petid and collection from the query string
    $petid = $_GET['petid'];

    $currentDocument = $firebase->getDocument("missing", $petid);

    if (empty($currentDocument) || !isset($currentDocument['fields'])) {
        die("Document not found or has no fields.");
    }

    $additionalPhotos = isset($currentDocument['fields']['additionalPhotos']['arrayValue']['values']) 
    ? $currentDocument['fields']['additionalPhotos']['arrayValue']['values'] 
    : [];

    $updateData = [
        'viewed' => 'YES',
        'timestamp' => new DateTime($currentDocument['fields']['timestamp']['timestampValue'] ?? 'now'),  
        'additionalPhotos' => array_map(fn($photo) => ['stringValue' => $photo['stringValue']], $additionalPhotos),
    ];

    $fieldsToKeep = [
        'address', 'age', 'firstName', 'lastName', 'birthdate', 'breed', 'additionalPhotos',
        'email', 'petPicture', 'profilePicture', 'gender', 'size', 'petType', 'timestamp', 
        'phoneNumber', 'description', 'name', 'streetNumber', 'city', 'note', 'postType'
    ];

    foreach ($fieldsToKeep as $field) {
        if (isset($currentDocument['fields'][$field]['stringValue'])) {
            $updateData[$field] = $currentDocument['fields'][$field]['stringValue'];
        }
    }

    $updateResponse = $firebase->updateDocument("missing", $petid, $updateData);

    if (isset($updateResponse['error'])) {
        echo "Error updating document: " . $updateResponse['error']['message'];
        exit();
    } else {
        echo "Document updated successfully.";
    }

    header("Location: view_profileMissing.php?petid=" . urlencode($petid));
    exit();
}
?>
