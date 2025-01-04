<?php
// Assuming you have the Firebase configuration already set up
$firebase = include('../../../config/firebase.php');

if (isset($_GET['petid']) && isset($_GET['collection'])) {
    // Get the petid and collection from the query string
    $petid = $_GET['petid'];

    // Fetch the current document from the 'adoptionApplication' collection
    $currentDocument = $firebase->getDocument("rescue", $petid);

    // Check if the document exists and has fields
    if (empty($currentDocument) || !isset($currentDocument['fields'])) {
        die("Document not found or has no fields.");
    }

    // Handle the homePhotos array field
    $additionalPhotos = isset($currentDocument['fields']['additionalPhotos']['arrayValue']['values']) 
        ? $currentDocument['fields']['additionalPhotos']['arrayValue']['values'] 
        : [];

    // Prepare the data to update
    $updateData = [
        'viewed' => 'YES',
        'timestamp' => new DateTime($currentDocument['fields']['timestamp']['timestampValue'] ?? 'now'),
        'statusChange' => new DateTime($currentDocument['fields']['statusChange']['timestampValue'] ?? 'now'),
        'additionalPhotos' => array_map(fn($photo) => ['stringValue' => $photo['stringValue'] ?? ''], $additionalPhotos),
    ];

    // Define the fields to retain
    $fieldsToKeep = [
        'additionalPhotos',
            'address',
            'age',
            'breed',
            'firstName',
            'lastName',
            'city',
            'streetNumber',
            'socials',
            'email',
            'message',
            'petPicture',
            'profilePicture',
            'gender',
            'size',
            'petType',
            'timestamp',
            'transactionNumber',
            'phoneNumber',
            'note',
            'description',
            'statusChange',
            'remarks',
            'reportStatus',
    ];

    // Copy existing fields into the updateData array
    foreach ($fieldsToKeep as $field) {
        if (isset($currentDocument['fields'][$field]['stringValue'])) {
            $updateData[$field] = $currentDocument['fields'][$field]['stringValue'];
        } elseif (isset($currentDocument['fields'][$field]['integerValue'])) {
            $updateData[$field] = $currentDocument['fields'][$field]['integerValue'];
        } elseif (isset($currentDocument['fields'][$field]['booleanValue'])) {
            $updateData[$field] = $currentDocument['fields'][$field]['booleanValue'];
        }
    }

    // Update the document in the 'adoptionApplication' collection
    $updateResponse = $firebase->updateDocument("rescue", $petid, $updateData);

    // Handle the response
    if (isset($updateResponse['error'])) {
        echo "Error updating document: " . $updateResponse['error']['message'];
        exit();
    } else {
        echo "Document updated successfully.";
    }

    // Redirect to the appropriate page after updating
    header("Location: view_profilePending.php?petid=" . urlencode($petid));
    exit();
}
?>
