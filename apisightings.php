<?php
session_start();
require_once('Models/SightingDataSet.php');

header('Content-type: application/json');

// Retrieve the stored AJAX token from the session
$token = "";
if (isset($_SESSION['ajaxToken'])) {
    $token = $_SESSION['ajaxToken'];
}

// Check the token is valid - reject request if it doesn't match
if (!isset($_GET['token']) || $_GET['token'] != $token) {
    $data = new stdClass();
    $data->error = "No data for you sir";
    echo json_encode($data);
    exit;
}

$sightingDataSet = new SightingDataSet();
$sightings = $sightingDataSet->fetchAllSightings();

$output = [];
foreach ($sightings as $sightingData) {
    $output[] = [
        'id' => $sightingData->getId(),
        'pet_id' => $sightingData->getPetId(),
        'pet_name' => $sightingData->getPetName(),
        'pet_species' => $sightingData->getPetSpecies(),
        'comment' => $sightingData->getComment(),
        'latitude' => $sightingData->getLatitude(),
        'longitude' => $sightingData->getLongitude(),
        'timestamp' => $sightingData->getTimestamp()
    ];
}
echo json_encode($output);
