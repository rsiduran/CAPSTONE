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
        ]; // Initialize with new status

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
        
        if (isset($updateResponse['error'])) {
            echo "Error updating document: " . $updateResponse['error']['message'];
        } else {
            // Redirect back to the profile report
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
