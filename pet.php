<?php
session_start();
// Load required classes
require_once('Models/PetDataSet.php');
require_once('Models/SightingDataSet.php');

// make a view class
$view = new stdClass();
$view->pageTitle = 'Pet Details';
$view->pet = null;
$view->sightings = [];
$view->message = '';

// Check if a pet ID was specified in the URL
if (!isset($_GET['id'])) {
    $view->message = "Error: No pet ID specified. Cannot show details.";
} else {
    $petID = $_GET['id'];

    $petDataSet = new PetDataSet();
    $sightingDataSet = new SightingDataSet();

    // Fetch the Pet and Check if it exists
    $view->pet = $petDataSet->fetchPetByID($petID);

    // Verify the pet exists
    if ($view->pet == null) {
        // If the ID was invalid or the pet was deleted
        $view->message = "Error: The pet you requested (ID: $petID) was not found.";
    } else {
        // If pet is found, Fetch all reported Sightings for this pet
        $view->sightings = $sightingDataSet->fetchPetSightings($petID);
    }
}
// include the View
require_once('Views/pet.phtml');