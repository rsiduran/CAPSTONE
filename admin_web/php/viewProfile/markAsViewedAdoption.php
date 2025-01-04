<?php
// Assuming you have the Firebase configuration already set up
$firebase = include('../../config/firebase.php');

if (isset($_GET['petid']) && isset($_GET['collection'])) {
    // Get the petid and collection from the query string
    $petid = $_GET['petid'];

    $currentDocument = $firebase->getDocument("adoption", $petid);

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
        'age', 'breed', 'additionalPhotos','petPicture', 'gender', 'size', 'petType', 'timestamp', 'description', 'name', 'medical', 'spay', 'vaccination'
    ];

    foreach ($fieldsToKeep as $field) {
        if (isset($currentDocument['fields'][$field]['stringValue'])) {
            $updateData[$field] = $currentDocument['fields'][$field]['stringValue'];
        }
    }

    $updateResponse = $firebase->updateDocument("adoption", $petid, $updateData);

    if (isset($updateResponse['error'])) {
        echo "Error updating document: " . $updateResponse['error']['message'];
        exit();
    } else {
        echo "Document updated successfully.";
    }

    header("Location: view_profileAdoptionList.php?petid=" . urlencode($petid));
    exit();
}
?>
