    <?php

    class FirebaseService {
        private $projectId;
        private $apiKey;

        public function __construct($config) {
            if (!isset($config['projectId']) || !isset($config['apiKey'])) {
                throw new Exception("Invalid configuration: 'projectId' or 'apiKey' is missing.");
            }

            $this->projectId = $config['projectId'];
            $this->apiKey = $config['apiKey'];
        }

        public function getDocuments($collection) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec($ch);
            curl_close($ch);
        
            $data = json_decode($response, true);
        
            if (isset($data['documents'])) {
                $result = [];
                foreach ($data['documents'] as $document) {
                    $docId = basename($document['name']);
                    $fields = $document['fields'];
                    $result[$docId] = [
                        'name' => $fields['name']['stringValue'] ?? 'N/A',
                        'breed' => $fields['breed']['stringValue'] ?? 'N/A',
                        'age' => $fields['age']['stringValue'] ?? 'N/A',
                        'gender' => $fields['gender']['stringValue'] ?? 'N/A',
                        'size' => $fields['size']['stringValue'] ?? 'N/A', 
                        'petType' => $fields['petType']['stringValue'] ?? 'N/A',
                        'postType' => $fields['postType']['stringValue'] ?? 'N/A', 
                        'profilePicture' => $fields['profilePicture']['stringValue'] ?? 'N/A', 
                        'petPicture' => $fields['petPicture']['stringValue'] ?? 'N/A', 
                        'characteristic' => $fields['characteristic']['stringValue'] ?? 'N/A',
                        'address'=> $fields['address']['stringValue'] ?? 'N/A',
                        'streetNumber'=> $fields['address']['stringValue'] ?? 'N/A',
                        'city' => $fields['city']['stringValue'] ?? 'N/A', 
                        'note' => $fields['note']['stringValue'] ?? 'N/A', 
                        'message' => $fields['message']['stringValue'] ?? 'N/A',
                        'removedAt' => $fields['removedAt']['timestampValue'] ?? 'N/A',
                        'firstName' => $fields['firstName']['stringValue'] ?? 'N/A',
                        'lastName' => $fields['lastName']['stringValue'] ?? 'N/A',
                        'email' => $fields['email']['stringValue'] ?? 'N/A',
                        'phoneNumber' => $fields['phoneNumber']['stringValue'] ?? 'N/A',
                        'socials' => $fields['socials']['stringValue'] ?? 'N/A',
                        'reportStatus' => $fields['reportStatus']['stringValue'] ?? 'N/A',
                        'allergic' => $fields['allergic']['stringValue'] ?? 'N/A',
                        'applicationStatus' => $fields['applicationStatus']['stringValue'] ?? 'N/A',
                        'birthdate' => $fields['birthdate']['timestampValue'] ?? 'N/A',
                        'contactEmail' => $fields['contactEmail']['stringValue'] ?? 'N/A',
                        'contactFirstName' => $fields['contactFirstName']['stringValue'] ?? 'N/A',
                        'contactLastName' => $fields['contactLastName']['stringValue'] ?? 'N/A',
                        'contactPhone' => $fields['contactPhone']['stringValue'] ?? 'N/A',
                        'contactRelationship' => $fields['contactRelationship']['stringValue'] ?? 'N/A',
                        'description' => $fields['description']['stringValue'] ?? 'N/A',
                        'homePhotos' => isset($fields['homePhotos']['arrayValue']['values']) 
                            ? array_map(function ($value) {
                                return $value['stringValue'] ?? 'N/A';
                            }, $fields['homePhotos']['arrayValue']['values'])
                            : [],
                        'idealPet' => $fields['idealPet']['stringValue'] ?? 'N/A',
                        'introduceSurroundings' => $fields['introduceSurroundings']['stringValue'] ?? 'N/A',
                        'liveAlone' => $fields['liveAlone']['stringValue'] ?? 'N/A',
                        'lookAfter' => $fields['lookAfter']['stringValue'] ?? 'N/A',
                        'medical' => $fields['medical']['stringValue'] ?? 'N/A',
                        'meet' => $fields['meet']['stringValue'] ?? 'N/A',
                        'move' => $fields['move']['stringValue'] ?? 'N/A',
                        'occupation' => $fields['occupation']['stringValue'] ?? 'N/A',
                        'otherPets' => $fields['otherPets']['stringValue'] ?? 'N/A',
                        'pastPets' => $fields['pastPets']['stringValue'] ?? 'N/A',
                        'postedTimestamp' => $fields['postedTimestamp']['timestampValue'] ?? 'N/A',
                        'previouslyAdopted' => $fields['previouslyAdopted']['stringValue'] ?? 'N/A',
                        'pronouns' => $fields['pronouns']['stringValue'] ?? 'N/A',
                        'rent' => $fields['ewnt']['stringValue'] ?? 'N/A',
                        'responsibleFinancial' => $fields['responsibleFinancial']['stringValue'] ?? 'N/A',
                        'responsibleGrooming' => $fields['pronouns']['stringValue'] ?? 'N/A',
                        'spay' => $fields['spay']['stringValue'] ?? 'N/A',
                        'status' => $fields['status']['stringValue'] ?? 'N/A',
                        'timestamp' => $fields['timestamp']['timestampValue'] ?? 'N/A',
                        'typeBuilding' => $fields['typeBuilding']['stringValue'] ?? 'N/A',
                        'vaccination' => $fields['vaccination']['stringValue'] ?? 'N/A',
                        'validID' => $fields['validID']['stringValue'] ?? 'N/A',
                        'petId' => $fields['petId']['stringValue'] ?? 'N/A',
                        'workHours' => $fields['workHours']['stringValue'] ?? 'N/A',
                        'additionalPhotos' => isset($fields['additionalPhotos']['arrayValue']['values']) 
                            ? array_map(function ($value) {
                                return $value['stringValue'] ?? 'N/A';
                            }, $fields['additionalPhotos']['arrayValue']['values'])
                            : [],
                        'transactionNumber' => $fields['transactionNumber']['stringValue'] ?? 'N/A',
                        'viewed' => $fields['viewed']['stringValue'] ?? 'N/A',
                        'foundAt' => $fields['foundAt']['stringValue'] ?? 'N/A',
                        'foundBy' => $fields['foundBy']['stringValue'] ?? 'N/A',
                        'foundOn' => $fields['foundOn']['timestampValue'] ?? 'N/A',
                        'rescuer' => $fields['rescuer']['stringValue'] ?? 'N/A',
                        'rescuedDate' => $fields['rescuedDate']['timestampValue'] ?? 'N/A',
                        'salaryRange' => $fields['salaryRange']['stringValue'] ?? 'N/A',
                        'createdAt' => $fields['createdAt']['timestampValue'] ?? 'N/A',
                        'adoptionDate' => $fields['adoptionDate']['timestampValue'] ?? 'N/A',
                    ];
                }
                return $result;
            }
        
            return [];
        }

        public function deleteDocument($collection, $documentId) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}?key={$this->apiKey}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                // Log curl errors
                echo 'Curl error: ' . curl_error($ch);
            } else {
                // Log response from Firestore
                echo 'Response: ' . $response;
            }
            
            curl_close($ch);
            return json_decode($response, true);
        }

        public function copyDocumentToHistoryMissing($document, $documentId) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/missingHistory/{$documentId}?key={$this->apiKey}";
            
            // Build fields payload
            $fields = [
                'fields' => [
                    'name' => ['stringValue' => $document['name'] ?? ''],
                    'breed' => ['stringValue' => $document['breed'] ?? ''],
                    'age' => ['stringValue' => $document['age'] ?? ''],
                    'gender' => ['stringValue' => $document['gender'] ?? ''],
                    'size' => ['stringValue' => $document['size'] ?? ''],
                    'petType' => ['stringValue' => $document['petType'] ?? ''],
                    'postType' => ['stringValue' => $document['postType'] ?? ''],
                    'profilePicture' => ['stringValue' => $document['profilePicture'] ?? ''],
                    'petPicture' => ['stringValue' => $document['petPicture'] ?? ''],
                    'description' => ['stringValue' => $document['description'] ?? ''],
                    'streetNumber' => ['stringValue' => $document['streetNumber'] ?? ''],
                    'city' => ['stringValue' => $document['city'] ?? ''],
                    'address' => ['stringValue' => $document['address'] ?? ''],
                    'firstName' => ['stringValue' => $document['firstName'] ?? ''],
                    'lastName' => ['stringValue' => $document['lastName'] ?? ''],
                    'email' => ['stringValue' => $document['email'] ?? ''],
                    'phoneNumber' => ['stringValue' => $document['phoneNumber'] ?? ''],
                    'removedAt' => ['timestampValue' => date('Y-m-d\TH:i:sP')],
                    'note' => ['stringValue' => $document['note'] ?? ''],
                ]
            ];
            
            $jsonPayload = json_encode($fields);
            
            // Debug payload
            error_log("Payload to Firestore: " . $jsonPayload);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            
            // Debug response
            error_log("Firestore API Response: " . $response);
            
            if (curl_errno($ch)) {
                error_log("cURL Error: " . curl_error($ch));
            }
            
            curl_close($ch);
            
            $responseData = json_decode($response, true);
            
            // Return response data or false in case of error
            return $responseData ? true : false;
        }
        
        

        public function copyDocumentToHistoryWandering($document, $documentId) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/wanderingHistory/{$documentId}?key={$this->apiKey}";
            
            // Add a timestamp field
            $fields = json_encode([
                'fields' => [
                    'name' => ['stringValue' => $document['name'] ?? ''],
                    'breed' => ['stringValue' => $document['breed'] ?? ''],
                    'age' => ['stringValue' => $document['age'] ?? ''],
                    'gender' => ['stringValue' => $document['gender'] ?? ''],
                    'size' => ['stringValue' => $document['size'] ?? ''],
                    'petType' => ['stringValue' => $document['petType'] ?? ''],
                    'postType' => ['stringValue' => $document['postType'] ?? ''],
                    'profilePicture' => ['stringValue' => $document['profilePicture'] ?? ''],
                    'petPicture' => ['stringValue' => $document['petPicture'] ?? ''],
                    'description' => ['stringValue' => $document['description'] ?? ''],
                    'streetNumber' => ['stringValue' => $document['streetNumber'] ?? ''],
                    'city' => ['stringValue' => $document['city'] ?? ''],
                    'address' => ['stringValue' => $document['address'] ?? ''],
                    'firstName' => ['stringValue' => $document['firstName'] ?? ''],
                    'lastName' => ['stringValue' => $document['lastName'] ?? ''],
                    'email' => ['stringValue' => $document['email'] ?? ''],
                    'phoneNumber' => ['stringValue' => $document['phoneNumber'] ?? ''],
                    'removedAt' => ['timestampValue' => date('Y-m-d\TH:i:sP')],
                    'note' => ['stringValue' => $document['note'] ?? ''],
                ]
            ]);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return json_decode($response, true);
        }

        public function copyDocumentToHistoryFound($document, $documentId) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/foundHistory/{$documentId}?key={$this->apiKey}";
            
            // Add a timestamp field
            $fields = json_encode([
                'fields' => [
                    'name' => ['stringValue' => $document['name'] ?? ''],
                    'breed' => ['stringValue' => $document['breed'] ?? ''],
                    'age' => ['stringValue' => $document['age'] ?? ''],
                    'gender' => ['stringValue' => $document['gender'] ?? ''],
                    'size' => ['stringValue' => $document['size'] ?? ''],
                    'petType' => ['stringValue' => $document['petType'] ?? ''],
                    'postType' => ['stringValue' => $document['postType'] ?? ''],
                    'profilePicture' => ['stringValue' => $document['profilePicture'] ?? ''],
                    'petPicture' => ['stringValue' => $document['petPicture'] ?? ''],
                    'description' => ['stringValue' => $document['description'] ?? ''],
                    'streetNumber' => ['stringValue' => $document['streetNumber'] ?? ''],
                    'city' => ['stringValue' => $document['city'] ?? ''],
                    'message' => ['stringValue' => $document['message'] ?? ''],
                    'address' => ['stringValue' => $document['address'] ?? ''],
                    'firstName' => ['stringValue' => $document['firstName'] ?? ''],
                    'lastName' => ['stringValue' => $document['lastName'] ?? ''],
                    'email' => ['stringValue' => $document['email'] ?? ''],
                    'phoneNumber' => ['stringValue' => $document['phoneNumber'] ?? ''],
                    'removedAt' => ['timestampValue' => date('Y-m-d\TH:i:sP')],
                    'note' => ['stringValue' => $document['note'] ?? ''],
                    'foundOn' => ['stringValue' => $document['foundOn'] ?? ''],
                    'foundBy' => ['stringValue' => $document['foundBy'] ?? ''],
                    'foundAt' => isset($document['foundAt']) && 
             !empty($document['foundAt']) && 
             strtoupper(trim($document['foundAt'])) !== 'N/A'
    ? ['timestampValue' => (new DateTime($document['foundAt']))->format(DateTime::ATOM)]
    : ['nullValue' => null],
                ]
            ]);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return json_decode($response, true);
        }
        public function updateDocument($collection, $documentId, $data) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}?key={$this->apiKey}";
        
            // Convert data to Firestore format
            $fields = [
                'fields' => array_map(function ($value) {
                    if (is_string($value)) {
                        return ['stringValue' => $value];
                    } elseif ($value instanceof DateTime) {
                        return ['timestampValue' => $value->format(DateTime::ATOM)];
                    } elseif (is_array($value)) {
                        return [
                            'arrayValue' => [
                                'values' => array_map(function ($item) {
                                    return is_array($item) && isset($item['stringValue'])
                                        ? ['stringValue' => $item['stringValue']]
                                        : ['nullValue' => null];
                                }, $value)
                            ]
                        ];
                    }
                    return ['nullValue' => null];
                }, $data)
            ];
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
        
            $response = curl_exec($ch);
            curl_close($ch);
        
            return json_decode($response, true);
        }
        

        public function getDocument($collection, $documentId) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}?key={$this->apiKey}";
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
        
            $response = curl_exec($ch);
            curl_close($ch);
        
            return json_decode($response, true);
        }
        
        public function insertDocument($collection, $data) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";
        
            // Transform the data for Firestore
            $fields = json_encode([
                'fields' => array_map(function($value) {
                    if (is_array($value)) {
                        // Handle arrays
                        return ['arrayValue' => ['values' => array_map(function($item) {
                            return $this->convertField($item);
                        }, $value)]];
                    } else {
                        return $this->convertField($value);
                    }
                }, $data)
            ]);
        
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
        
            // Execute and close
            $response = curl_exec($ch);
            curl_close($ch);
        
            return json_decode($response, true);
        }
        
        // Helper method to convert fields to appropriate Firestore types
        private function convertField($value) {
            if (is_string($value)) {
                return ['stringValue' => $value];
            } elseif (is_int($value)) {
                return ['integerValue' => $value];
            } elseif (is_float($value)) {
                return ['doubleValue' => $value];
            } elseif (is_bool($value)) {
                return ['booleanValue' => $value];
            } elseif ($value instanceof DateTime) {
                return ['timestampValue' => $value->format(DateTime::ATOM)]; // ISO 8601 format
            } elseif (is_array($value)) {
                // Check if the array is associative (map) or indexed (array)
                if (array_keys($value) === range(0, count($value) - 1)) {
                    // Indexed array: Treat as Firestore array
                    return [
                        'arrayValue' => [
                            'values' => array_map([$this, 'convertField'], $value),
                        ],
                    ];
                } else {
                    // Associative array: Treat as Firestore map
                    return [
                        'mapValue' => [
                            'fields' => array_map([$this, 'convertField'], $value),
                        ],
                    ];
                }
            } elseif (is_null($value)) {
                return ['nullValue' => null];
            }
        
            // Add support for GeoPoint if required
            // Example: Assuming $value is an object with lat and lng properties
            if (is_object($value) && isset($value->lat, $value->lng)) {
                return [
                    'geoPointValue' => [
                        'latitude' => $value->lat,
                        'longitude' => $value->lng,
                    ],
                ];
            }
        
            // Fallback for unsupported types
            throw new InvalidArgumentException('Unsupported field type: ' . gettype($value));
        }
        
        
        public function uploadFile($filePath, $uploadPath) {
            // Read the file contents
            $file = fopen($filePath, 'r');
            if (!$file) {
                throw new Exception("Failed to open file: $filePath");
            }
        
            // Get the content type
            $fileMimeType = mime_content_type($filePath);
            
            // Firebase Storage URL for uploading files
            $url = "https://firebasestorage.googleapis.com/v0/b/{$this->projectId}.appspot.com/o?name=" . rawurlencode($uploadPath) . "&key={$this->apiKey}";
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: ' . $fileMimeType,
                'Content-Length: ' . filesize($filePath),
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, fread($file, filesize($filePath))); // Read the file contents
        
            $response = curl_exec($ch);
            fclose($file);
            curl_close($ch);
        
            // Decode the response and return the download URL
            $data = json_decode($response, true);
            
            if (isset($data['downloadTokens'])) {
                return "https://firebasestorage.googleapis.com/v0/b/{$this->projectId}.appspot.com/o/" . rawurlencode($uploadPath) . "?alt=media&token=" . $data['downloadTokens'];
            }
        
            throw new Exception("File upload failed: " . json_encode($data));
        }

        public function getRecentDocumentsAcrossCollections($collections) {
            $recentDocuments = [];

            foreach ($collections as $collection) {
                $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?orderBy=createTime desc&limit=5&key={$this->apiKey}";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);

                $data = json_decode($response, true);

                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $document) {
                        $docId = basename($document['name']);
                        $fields = $document['fields'];
                        $recentDocuments[] = [
                            'id' => $docId,
                            'collection' => $collection,
                            'name' => $fields['name']['stringValue'] ?? 'N/A',
                            'createTime' => $document['createTime'] ?? 'N/A'
                        ];
                    }
                }
            }

            // Sort by createTime to get the most recent entries across all collections
            usort($recentDocuments, function ($a, $b) {
                return strtotime($b['createTime']) - strtotime($a['createTime']);
            });

            return array_slice($recentDocuments, 0, 5); // Return only the top 5 most recent documents
        }

        public function getDocumentCount($collection) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            return isset($data['documents']) ? count($data['documents']) : 0;
        }

        public function getUserType($username, $password) {
            // Firestore REST API URL
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:runQuery?key={$this->apiKey}";
        
            // Prepare the query body to find the user by username
            $data = [
                "structuredQuery" => [
                    "select" => [
                        "fields" => [
                            ["fieldPath" => "username"],
                            ["fieldPath" => "password"],
                            ["fieldPath" => "type"]
                        ]
                    ],
                    "from" => [
                        ["collectionId" => "superAdmin"] // Make sure this matches the actual Firestore collection name
                    ],
                    "where" => [
                        "fieldFilter" => [
                            "field" => ["fieldPath" => "username"],
                            "op" => "EQUAL",
                            "value" => ["stringValue" => $username]
                        ]
                    ]
                ]
            ];
        
            // Prepare HTTP context for POST request
            $options = [
                'http' => [
                    'header' => "Content-Type: application/json\r\n",
                    'method' => 'POST',
                    'content' => json_encode($data),
                ]
            ];
            $context = stream_context_create($options);
        
            // Send the request
            $response = file_get_contents($url, false, $context);
        
            // If response is false, return an error
            if ($response === FALSE) {
                return null;
            }
        
            // Decode the response to get user data
            $responseData = json_decode($response, true);
        
            // Check if data is available
            if (isset($responseData[0]['document']['fields'])) {
                $fields = $responseData[0]['document']['fields'];
        
                // Check if the username and password match
                if (isset($fields['username']['stringValue']) && $fields['username']['stringValue'] === $username) {
                    // Check if the password matches
                    if (isset($fields['password']['stringValue']) && $fields['password']['stringValue'] === $password) {
                        // Return user type if both username and password match
                        if (isset($fields['type']['stringValue'])) {
                            return $fields['type']['stringValue']; // Return the user type (superadmin, admin, staff)
                        }
                    }
                }
            }
        
            // Return null if username or password is incorrect or type is missing
            return null;
        }

        public function addDocument($collection, $data) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";
            
            // Prepare data to be sent to Firestore
            $postData = json_encode([
                'fields' => $data // Ensure that data is formatted as a map of field names to values
            ]);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Use POST for adding a document
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
            $response = curl_exec($ch);
            curl_close($ch);
        
            return json_decode($response, true);
        }
        
        public function getCollectionCount($collection) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}?key={$this->apiKey}";
            
            // Initialize CURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            
            // Execute CURL request
            $response = curl_exec($ch);
            curl_close($ch);
        
            // Decode JSON response
            $responseData = json_decode($response, true);
        
            // Count the documents in the collection
            if (isset($responseData['documents']) && is_array($responseData['documents'])) {
                return count($responseData['documents']);
            }
        
            // Return 0 if no documents or in case of error
            return 0;
        }
        
        public function countUnviewedForCollection($collection) {
            $count = 0;
        
            $documents = $this->getDocuments($collection);
        
            if ($documents) {
                foreach ($documents as $document) {
                    if (isset($document['viewed']) && $document['viewed'] === 'NO') {
                        $count++;
                    }   
                }
            }
        
            return $count;
        }
        
        public function countDocumentsForStatusAndViewed($collection, $statusField, $statusValue) {
            $count = 0;
            
            // Get all documents from the collection
            $documents = $this->getDocuments($collection);
            
            // Check if documents exist
            if ($documents) {
                foreach ($documents as $document) {
                    // Check if the document has the status and viewed fields
                    if (isset($document[$statusField]) && $document[$statusField] === $statusValue) {
                        if (isset($document['viewed']) && $document['viewed'] === 'NO') {
                            $count++;
                        }
                    }
                }
            }
            
            return $count;
        }

        public function getDocumentById($collection, $documentId) {
            $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}?key={$this->apiKey}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
            curl_close($ch);
    
            if ($httpCode === 200) {
                $data = json_decode($response, true);
                return $data['fields'] ?? null;
            }
    
            return null; // Return null if the document does not exist or an error occurred
        }
    }

    
