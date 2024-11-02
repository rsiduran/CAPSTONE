<?php

$config = [
    "apiKey" => "AIzaSyAoaNKMO2jwSLbx5xy7GK1-VGP3RWN18G0",
    "authDomain" => "wanderpets-db-2307b.firebaseapp.com",
    "databaseURL" => "https://wanderpets-db-2307b.firebaseio.com",
    "projectId" => "wanderpets-db-2307b",
    "storageBucket" => "wanderpets-db-2307b.appspot.com",
    "messagingSenderId" => "101528582501",
    "appId" => "1:101528582501:web:16a85b58bc4c25fa67498c"
];

include_once 'firebaseService.php';
return new FirebaseService($config);
