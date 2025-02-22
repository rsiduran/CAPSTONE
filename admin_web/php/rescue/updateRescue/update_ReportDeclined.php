<?php
$firebase = include('../../../config/firebase.php');

// Check if the pet ID and current status are set
if (isset($_POST['petid']) && isset($_POST['currentStatus'])) {
    $petid = $_POST['petid'];
    $currentStatus = $_POST['currentStatus'];

    // Check if confirmation is requested
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Fetch the existing document data
        $currentDocument = $firebase->getDocument("rescue", $petid);
        
        // Check if the document exists
        if (empty($currentDocument) || !isset($currentDocument['fields'])) {
            die("Document not found or has no fields.");
        }

        // Define the new status based on the current status
        $newStatus = ($currentStatus === 'REVIEWING') ? 'DECLINED' : $currentStatus;

        $statusChange = new DateTime('now', new DateTimeZone('Asia/Manila')); 
        $formattedStatusChange = $statusChange->format(DateTime::ATOM);
        $additionalPhotos = isset($currentDocument['fields']['additionalPhotos']['arrayValue']['values']) 
             ? $currentDocument['fields']['additionalPhotos']['arrayValue']['values'] 
             : []; 

        // Prepare data to update
        $updateData = [
            'reportStatus' => $newStatus,
            'statusChange' => new DateTime('now', new DateTimeZone('Asia/Manila')),
            'additionalPhotos' => array_map(fn($photo) => ['stringValue' => $photo['stringValue']], $additionalPhotos), 
            'timestamp' => new DateTime($currentDocument['fields']['timestamp']['timestampValue'] ?? 'now'),
            'viewed' => 'NO',
        ]; 

        // Define the fields to keep
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
        ];

        // Loop through each field and check if it exists
        foreach ($fieldsToKeep as $field) {
            if (isset($currentDocument['fields'][$field]['stringValue'])) {
                $updateData[$field] = $currentDocument['fields'][$field]['stringValue'];
            }
        }

        // Update the document in Firebase
        $updateResponse = $firebase->updateDocument("rescue", $petid, $updateData);
        
        if (!isset($updateResponse['error'])) {
            $userEmail = $currentDocument['fields']['email']['stringValue'] ?? null;
            
            if ($userEmail) {

                error_log("Attempting to insert notification...");

                $userQuery = $firebase->queryDocuments("users", "email", "==", $userEmail);
                error_log("User Query Result: " . json_encode($userQuery));

                if (empty($userQuery['documents'])) {
                    die("No user found for email: " . htmlspecialchars($userEmail));
                }

                $userId = $userQuery['documents'][0]['name'] ?? null;
                if (!$userId) {
                    die("User ID not found");
                }

                $notificationData = [
                    'body' => ($currentDocument['fields']['transactionNumber']['stringValue'] ?? '') . 
                              ": Your rescue request for " . ($currentDocument['fields']['name']['stringValue'] ?? '') . 
                              " has been updated to DECLINED",
                    'petId' => $currentDocument['fields']['petId']['stringValue'] ?? '',
                    'read' => false,
                    'title' => 'Rescue Request Update',
                    'timestamp' => new DateTime('now', new DateTimeZone('Asia/Manila')),
                    'transactionNumber' => $currentDocument['fields']['transactionNumber']['stringValue'] ?? '',
                    'type' => 'rescue',
                    'userId' => basename($userId ?? '') // Extracts only the document ID
                ];

                error_log("Notification Data: " . json_encode($notificationData));

                $insertResponse = $firebase->insertDocument("notifications", $notificationData);
                if (isset($insertResponse['error'])) {
                    die("Notification insertion failed: " . json_encode($insertResponse['error']));
                }
                error_log("Notification successfully inserted.");
            }
            header("Location: ../rescueReviewing.php?petid=" . urlencode($petid));
            exit();
        }
    } else {
        // Show confirmation modal
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirm Status Change</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
        </head>
        <body>
            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel">Confirm Status Change</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to change the status of this pet?
                        </div>
                        <div class="modal-footer">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="petid" value="' . htmlspecialchars($petid) . '">
                                <input type="hidden" name="currentStatus" value="' . htmlspecialchars($currentStatus) . '">
                                <input type="hidden" name="confirm" value="yes">
                                <button type="submit" class="btn btn-danger">Yes, Change Status</button>
                            </form>
                            <a href="../viewProfile/view_profileReviewing.php?petid=' . urlencode($petid) . '" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Show the modal when the page loads
                var myModal = new bootstrap.Modal(document.getElementById("confirmModal"), {
                    backdrop: "static",
                    keyboard: false
                });
                myModal.show();
            </script>
        </body>
        </html>';
        exit();
    }
} else {
    die("Invalid request.");
}
?>
