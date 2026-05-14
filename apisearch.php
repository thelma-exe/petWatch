<?php
session_start();
require_once('Models/PetDataSet.php');

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

// Get and sanitise the search term from the URL
$searchText = isset($_GET['searchTerm']) ?htmlspecialchars(trim($_GET['searchTerm'])) : '';

// Return empty array if search term is empty
if ($searchText === '') {
    echo json_encode([]);
    exit;
}

//Fetch matching pets using fetchFilteredPets() method
$petDataSet = new PetDataSet();
$pets = $petDataSet->fetchFilteredPets($searchText, [], []);

//Build the output array with only the fields needed for search results
$output = [];
foreach ($pets as $petData) {
    $output[] = [
        'id' => $petData->getId(),
        'name' => $petData->getName(),
        'species' => $petData->getSpecies(),
        'breed' => $petData->getBreed(),
        'status' => $petData->getStatus(),
        'description' => $petData->getDescription(),
        'photo_url' => $petData->getPhotoURL(),
    ];
}
echo json_encode($output);