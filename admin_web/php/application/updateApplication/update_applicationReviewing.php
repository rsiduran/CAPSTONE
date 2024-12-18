<?php
$firebase = include('../../../config/firebase.php');
include('../../../config/auth.php');

if (isset($_POST['petid']) && isset($_POST['currentStatus'])) {
    $petid = $_POST['petid'];
    $currentStatus = $_POST['currentStatus'];

    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $currentDocument = $firebase->getDocument("adoptionApplication", $petid);
        
        if (empty($currentDocument) || !isset($currentDocument['fields'])) {
            die("Document not found or has no fields.");
        }

        $newStatus = ($currentStatus === 'REVIEWING') ? 'APPROVED' : $currentStatus;

        // Create the properly formatted timestamp
        $statusChange = new DateTime('now', new DateTimeZone('Asia/Manila')); // Timestamp
        $formattedStatusChange = $statusChange->format(DateTime::ATOM); // Firestore-friendly format
        $homePhotos = isset($currentDocument['fields']['homePhotos']['arrayValue']['values']) 
            ? $currentDocument['fields']['homePhotos']['arrayValue']['values'] 
            : [];  // Default to empty array if it's not set

        $updateData = [
            'applicationStatus' => $newStatus,
            'statusChange' => new DateTime('now', new DateTimeZone('Asia/Manila')), // Timestamp
            'homePhotos' => array_map(fn($photo) => ['stringValue' => $photo['stringValue']], $homePhotos), // Ensure proper array handling
            'timestamp' => new DateTime($currentDocument['fields']['timestamp']['timestampValue'] ?? 'now'), // Timestamp
            'postedTimestamp' => new DateTime($currentDocument['fields']['postedTimestamp']['timestampValue'] ?? 'now'), // Timestamp
        ];

        $fieldsToKeep = [
            'address',
            'age',
            'firstName',
            'lastName', 
            'birthdate',
            'breed',
            'socials',
            'email',
            'company',
            'petPicture',
            'profilePicture',
            'gender',
            'size',
            'petType',
            'postedTimestamp',
            'timestamp',
            'phoneNumber',
            'contactEmail',
            'contactFirstName',
            'contactLastName',
            'contactPhone',
            'contactRelationship',
            'description',
            'allergic',
            'homePhotos',
            'idealPet',
            'introduceSurroundings',
            'liveAlone',
            'lookAfter',
            'meet',
            'move',
            'occupation',
            'otherPets',
            'pastPets',
            'medical',
            'name',
            'pronouns',
            'rent',
            'responsibleFinancial',
            'responsibleGrooming',
            'spay',
            'status',
            'vaccination',
            'typeBuilding',
            'validID',
            'workHours',
            'previouslyAdopted',
            'petId',
            'adoptionDate',
            'transactionNumber',
            'salaryRange',
        ];

        foreach ($fieldsToKeep as $field) {
            if (isset($currentDocument['fields'][$field]['stringValue'])) {
                $updateData[$field] = $currentDocument['fields'][$field]['stringValue'];
            }
        }

        $remarks = isset($_POST['remarks']) ? filter_var($_POST['remarks'], FILTER_SANITIZE_STRING) : '';
        $updateData['remarks'] = $remarks;

        $updateResponse = $firebase->updateDocument("adoptionApplication", $petid, $updateData);
        
        if (isset($updateResponse['error'])) {
            echo "Error updating document: " . $updateResponse['error']['message'];
        } else {
            header("Location: ../applicationReviewing.php?petid=" . urlencode($petid));
            exit();
        }
    } else {
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
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks (optional):</label>
                                <input type="text" class="form-control" name="remarks" id="remarks" maxlength="200" placeholder="Add any remarks here...">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="petid" value="' . htmlspecialchars($petid) . '">
                                <input type="hidden" name="currentStatus" value="' . htmlspecialchars($currentStatus) . '">
                                <input type="hidden" name="confirm" value="yes">
                                <input type="hidden" name="remarks" id="hiddenRemarks">
                                <button type="submit" class="btn btn-danger">Yes, Change Status</button>
                            </form>
                            <a href="../viewApplication/view_applicationReviewing.php?petid=' . urlencode($petid) . '" class="btn btn-secondary">Cancel</a>
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

                // Transfer remarks input to hidden input on submit
                document.querySelector("form").addEventListener("submit", function() {
                    document.getElementById("hiddenRemarks").value = document.getElementById("remarks").value;
                });
            </script>
        </body>
        </html>';
        exit();
    }
} else {
    die("Invalid request.");
}
?>