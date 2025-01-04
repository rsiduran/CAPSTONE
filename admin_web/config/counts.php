<?php
// Fetch counts for adoptionApplication statuses (dynamically or statically)
$adoptionCounts = [
  'PENDING' => $firebase->countDocumentsForStatusAndViewed("adoptionApplication", "applicationStatus", "PENDING"),
  'REVIEWING' => $firebase->countDocumentsForStatusAndViewed("adoptionApplication", "applicationStatus", "REVIEWING"),
  'APPROVED' => $firebase->countDocumentsForStatusAndViewed("adoptionApplication", "applicationStatus", "APPROVED"),
  'COMPLETED' => $firebase->countDocumentsForStatusAndViewed("adoptionApplication", "applicationStatus", "COMPLETED"),
  'REJECTED' => $firebase->countDocumentsForStatusAndViewed("adoptionApplication", "applicationStatus", "REJECTED")
];

// Fetch counts for rescue statuses (dynamically or statically)
$rescueCounts = [
  'PENDING' => $firebase->countDocumentsForStatusAndViewed("rescue", "reportStatus", "PENDING"),
  'REVIEWING' => $firebase->countDocumentsForStatusAndViewed("rescue", "reportStatus", "REVIEWING"),
  'ONGOING' => $firebase->countDocumentsForStatusAndViewed("rescue", "reportStatus", "ONGOING"),
  'RESCUED' => $firebase->countDocumentsForStatusAndViewed("rescue", "reportStatus", "RESCUED"),
  'DECLINED' => $firebase->countDocumentsForStatusAndViewed("rescue", "reportStatus", "DECLINED")
];

// Fetch unviewed counts for other collections
$collectionsToCheck = [
    'missing'=> 'missing',
    'wandering' => 'wandering',
    'found' => 'found',
    'adoption' => 'adoption'
];
$unviewedCounts = [];
foreach ($collectionsToCheck as $collectionKey => $collectionName) {
    $unviewedCounts[$collectionName] = $firebase->countUnviewedForCollection($collectionKey);
}
?>