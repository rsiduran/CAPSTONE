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

        $newStatus = ($currentStatus === 'APPROVED') ? 'COMPLETED' : $currentStatus;

         $statusChange = new DateTime('now', new DateTimeZone('Asia/Manila')); 
         $formattedStatusChange = $statusChange->format(DateTime::ATOM); 
         $homePhotos = isset($currentDocument['fields']['homePhotos']['arrayValue']['values']) 
             ? $currentDocument['fields']['homePhotos']['arrayValue']['values'] 
             : [];  
 
         $updateData = [
             'applicationStatus' => $newStatus,
             'statusChange' => new DateTime('now', new DateTimeZone('Asia/Manila')), 
             'homePhotos' => array_map(fn($photo) => ['stringValue' => $photo['stringValue']], $homePhotos), 
             'timestamp' => new DateTime($currentDocument['fields']['timestamp']['timestampValue'] ?? 'now'), 
             'postedTimestamp' => new DateTime($currentDocument['fields']['postedTimestamp']['timestampValue'] ?? 'now'), 
         ];

        if ($newStatus === 'COMPLETED') {
            if (empty($_POST['personnel']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['personnel'])) {
                die("Invalid personnel name. Name is required and should contain only letters.");
            }

            $personnel = filter_var($_POST['personnel'], FILTER_SANITIZE_STRING);
            $updateData['personnel'] = $personnel;
            $adoptionDate = new DateTime('now', new DateTimeZone('Asia/Manila')); 
            $formattedadoptionDate = $adoptionDate->format(DateTime::ATOM);
            $updateData['adoptionDate'] = new DateTime('now', new DateTimeZone('Asia/Manila')); 
        }

        $fieldsToKeep = [
            'address', 'age', 'firstName', 'lastName', 'birthdate', 'breed', 'socials', 'email', 'company', 
            'petPicture', 'profilePicture', 'gender', 'size', 'petType', 'postedTimestamp', 'timestamp', 
            'phoneNumber', 'contactEmail', 'contactFirstName', 'contactLastName', 'contactPhone', 
            'contactRelationship', 'description', 'allergic', 'homePhotos', 'idealPet', 'introduceSurroundings', 
            'liveAlone', 'lookAfter', 'meet', 'more', 'occupation', 'otherPets', 'postPets', 'medical', 
            'name', 'pronouns', 'rent', 'responsibleFinancial', 'responsibleGrooming', 'spay', 'status', 
            'vaccination', 'typeBuilding', 'validID', 'workHours', 'previouslyAdopted'
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
            header("Location: ../applicationApproved.php?petid=" . urlencode($petid));
            exit();
        }
    } else {
        $showPersonnelField = ($currentStatus === 'APPROVED') ? "block" : "none";
        
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
                            <div id="completedFields" style="display: ' . $showPersonnelField . ';">
                                <div class="mb-3">
                                    <label for="personnel" class="form-label">Personnel (required):</label>
                                    <input type="text" class="form-control" name="personnel" id="personnel" required>
                                    <div class="invalid-feedback">Invalid Name. Only letters and spaces are allowed.</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="petid" value="' . htmlspecialchars($petid) . '">
                                <input type="hidden" name="currentStatus" value="' . htmlspecialchars($currentStatus) . '">
                                <input type="hidden" name="confirm" value="yes">
                                <input type="hidden" name="remarks" id="hiddenRemarks">
                                <input type="hidden" name="personnel" id="hiddenPersonnel">
                                <button type="submit" class="btn btn-danger">Yes, Change Status</button>
                            </form>
                            <a href="../viewApplication/view_applicationApproved.php?petid=' . urlencode($petid) . '" class="btn btn-secondary">Cancel</a>
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

                // Transfer remarks and personnel inputs to hidden fields on submit
                document.querySelector("form").addEventListener("submit", function(event) {
                    var personnelInput = document.getElementById("personnel");
                    var personnelValue = personnelInput.value;
                    var personnelValid = /^[a-zA-Z\s]+$/.test(personnelValue);
                    
                    if (!personnelValid) {
                        event.preventDefault();
                        personnelInput.classList.add("is-invalid");
                    } else {
                        personnelInput.classList.remove("is-invalid");
                        document.getElementById("hiddenRemarks").value = document.getElementById("remarks").value;
                        document.getElementById("hiddenPersonnel").value = personnelValue;
                    }
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
