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

        // Validate and sanitize rescuer input
        $rescuer = isset($_POST['rescuer']) ? trim($_POST['rescuer']) : '';
        $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
        $errorMessage = '';

        if (empty($rescuer)) {
            $errorMessage = "Input field required";
        } elseif (preg_match('/\d/', $rescuer) || preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $rescuer)) {
            $errorMessage = "Invalid name"; // Prevent digits and script tags
        } else {
            $rescuer = filter_var($rescuer, FILTER_SANITIZE_STRING); // Sanitize the input
        }
        if (!empty($errorMessage)) {
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
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="rescuer" class="form-label">Rescuer (required):</label>
                                        <input type="text" class="form-control" name="rescuer" id="rescuer" value="' . htmlspecialchars($rescuer) . '" required>
                                        <div class="text-danger mt-2">' . htmlspecialchars($errorMessage) . '</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Remarks (optional):</label>
                                        <input type="text" class="form-control" name="remarks" id="remarks" maxlength="200" placeholder="Add any remarks here..." value="' . htmlspecialchars($remarks) . '">
                                    </div>
                                    <input type="hidden" name="petid" value="' . htmlspecialchars($petid) . '">
                                    <input type="hidden" name="currentStatus" value="' . htmlspecialchars($currentStatus) . '">
                                    <input type="hidden" name="confirm" value="yes">
                                    <button type="submit" class="btn btn-danger">Yes, Change Status</button>
                                    <a href="../viewProfile/view_profileOngoing.php?petid=' . urlencode($petid) . '" class="btn btn-secondary">Cancel</a>
                                </form>
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

        $newStatus = ($currentStatus === 'ONGOING') ? 'RESCUED' : $currentStatus;
        $rescuedDate = date('m-d-Y H:i'); 

        $updateData = [
            'reportStatus' => $newStatus,
            'statusChange' => $rescuedDate, 
            'rescuer' => $rescuer,
            'rescuedDate' => $rescuedDate 
        ];

        $updateData['remarks'] = $remarks;

        $fieldsToKeep = [
            'address', 'age', 'firstName', 'lastName', 'city', 'streetNumber',
            'socials', 'email', 'message', 'petPicture', 'profilePicture',
            'gender', 'size', 'petType', 'timestamp', 'phoneNumber',
        ];

        foreach ($fieldsToKeep as $field) {
            if (isset($currentDocument['fields'][$field]['stringValue'])) {
                $updateData[$field] = $currentDocument['fields'][$field]['stringValue'];
            }
        }

        $updateResponse = $firebase->updateDocument("rescue", $petid, $updateData);

        if (isset($updateResponse['error'])) {
            echo "Error updating document: " . $updateResponse['error']['message'];
        } else {
            header("Location: ../rescueOngoing.php?petid=" . urlencode($petid));
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
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="rescuer" class="form-label">Rescuer (required):</label>
                                    <input type="text" class="form-control" name="rescuer" id="rescuer" required>
                                </div>
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks (optional):</label>
                                    <input type="text" class="form-control" name="remarks" id="remarks" maxlength="200" placeholder="Add any remarks here...">
                                </div>
                                <input type="hidden" name="petid" value="' . htmlspecialchars($petid) . '">
                                <input type="hidden" name="currentStatus" value="' . htmlspecialchars($currentStatus) . '">
                                <input type="hidden" name="confirm" value="yes">
                                <button type="submit" class="btn btn-danger">Yes, Change Status</button>
                                <a href="../viewProfile/view_profileOngoing.php?petid=' . urlencode($petid) . '" class="btn btn-secondary">Cancel</a>
                            </form>
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
