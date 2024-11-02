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
                    'message' => $fields['message']['stringValue'] ?? 'N/A',
                    'removedAt' => $fields['removedAt']['timestampValue'] ?? 'N/A',
                    'firstName' => $fields['firstName']['stringValue'] ?? 'N/A',
                    'lastName' => $fields['lastName']['stringValue'] ?? 'N/A',
                    'email' => $fields['email']['stringValue'] ?? 'N/A',
                    'phoneNumber' => $fields['phoneNumber']['stringValue'] ?? 'N/A',
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
        curl_close($ch);
        
        return json_decode($response, true);
    }

    public function copyDocumentToHistoryMissing($document, $documentId) {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/missingHistory/{$documentId}?key={$this->apiKey}";
        
        // Add a timestamp field
        $fields = json_encode([
            'fields' => [
                'name' => ['stringValue' => $document['name']],
                'breed' => ['stringValue' => $document['breed']],
                'age' => ['stringValue' => $document['age']],
                'gender' => ['stringValue' => $document['gender']],
                'size' => ['stringValue' => $document['size']],
                'petType' => ['stringValue' => $document['petType']],
                'postType' => ['stringValue' => $document['postType']],
                'profilePicture' => ['stringValue' => $document['profilePicture']],
                'petPicture' => ['stringValue' => $document['petPicture']],
                'characteristic' => ['stringValue' => $document['characteristic']],
                'streetNumber' => ['stringValue' => $document['streetNumber']],
                'city' => ['stringValue' => $document['city']],
                'message' => ['stringValue' => $document['message']],
                'address' => ['stringValue' => $document['address']],
                'firstName' => ['stringValue' => $document['firstName']],
                'lastName' => ['stringValue' => $document['lastName']],
                'email' => ['stringValue' => $document['email']],
                'phoneNumber' => ['stringValue' => $document['phoneNumber']],
                'removedAt' => ['timestampValue' => date('Y-m-d\TH:i:sP')] // Timestamp for when the pet was removed
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

    public function copyDocumentToHistoryWandering($document, $documentId) {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/wanderingHistory/{$documentId}?key={$this->apiKey}";
        
        // Add a timestamp field
        $fields = json_encode([
            'fields' => [
                'name' => ['stringValue' => $document['name']],
                'breed' => ['stringValue' => $document['breed']],
                'age' => ['stringValue' => $document['age']],
                'gender' => ['stringValue' => $document['gender']],
                'size' => ['stringValue' => $document['size']],
                'petType' => ['stringValue' => $document['petType']],
                'postType' => ['stringValue' => $document['postType']],
                'profilePicture' => ['stringValue' => $document['profilePicture']],
                'petPicture' => ['stringValue' => $document['petPicture']],
                'characteristic' => ['stringValue' => $document['characteristic']],
                'streetNumber' => ['stringValue' => $document['streetNumber']],
                'city' => ['stringValue' => $document['city']],
                'message' => ['stringValue' => $document['message']],
                'address' => ['stringValue' => $document['address']],
                'firstName' => ['stringValue' => $document['firstName']],
                'lastName' => ['stringValue' => $document['lastName']],
                'email' => ['stringValue' => $document['email']],
                'phoneNumber' => ['stringValue' => $document['phoneNumber']],
                'removedAt' => ['timestampValue' => date('Y-m-d\TH:i:sP')] // Timestamp for when the pet was removed
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
}
