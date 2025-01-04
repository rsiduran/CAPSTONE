<?php
// Assuming you have the Firebase configuration already set up
$firebase = include('../../../config/firebase.php');

if (isset($_GET['petid']) && isset($_GET['collection'])) {
    // Get the petid and collection from the query string
    $petid = $_GET['petid'];

    // Fetch the current document from the 'adoptionApplication' collection
    $currentDocument = $firebase->getDocument("adoptionApplication", $petid);

    // Check if the document exists and has fields
    if (empty($currentDocument) || !isset($currentDocument['fields'])) {
        die("Document not found or has no fields.");
    }

    // Handle the homePhotos array field
    $homePhotos = isset($currentDocument['fields']['homePhotos']['arrayValue']['values']) 
        ? $currentDocument['fields']['homePhotos']['arrayValue']['values'] 
        : [];

    // Prepare the data to update
    $updateData = [
        'viewed' => 'YES',
        'timestamp' => new DateTime($currentDocument['fields']['timestamp']['timestampValue'] ?? 'now'),
        'postedTimestamp' => new DateTime($currentDocument['fields']['postedTimestamp']['timestampValue'] ?? 'now'),
        'statusChange' => new DateTime($currentDocument['fields']['statusChange']['timestampValue'] ?? 'now'),
        'homePhotos' => array_map(fn($photo) => ['stringValue' => $photo['stringValue'] ?? ''], $homePhotos),
    ];

    // Define the fields to retain
    $fieldsToKeep = [
        'applicationStatus','address', 'age', 'firstName', 'lastName', 'birthdate', 'breed', 'adoptionDate', 'socials',
        'email', 'company', 'petPicture', 'profilePicture', 'gender', 'size', 'petType', 'postedTimestamp',
        'timestamp', 'phoneNumber', 'contactEmail', 'contactFirstName', 'contactLastName', 'contactPhone', 
        'contactRelationship', 'description', 'allergic', 'homePhotos', 'idealPet', 'introduceSurroundings', 
        'liveAlone', 'lookAfter', 'meet', 'move', 'occupation', 'otherPets', 'pastPets', 'medical', 
        'name', 'pronouns', 'rent', 'responsibleFinancial', 'responsibleGrooming', 'spay', 'status', 
        'vaccination', 'typeBuilding', 'validID', 'transactionNumber', 'workHours', 'previouslyAdopted', 
        'petId', 'salaryRange', 'statusChange'
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
    $updateResponse = $firebase->updateDocument("adoptionApplication", $petid, $updateData);

    // Handle the response
    if (isset($updateResponse['error'])) {
        echo "Error updating document: " . $updateResponse['error']['message'];
        exit();
    } else {
        echo "Document updated successfully.";
    }

    // Redirect to the appropriate page after updating
    header("Location: view_applicationPending.php?petid=" . urlencode($petid));
    exit();
}
?>
