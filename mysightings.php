<?php
session_start();
// Load required classes
require_once('Models/SightingDataSet.php');
require_once('Models/PetDataSet.php'); // Needed to fetch pet details (name/species)
// make a view class
$view = new stdClass();
$view->pageTitle = 'My Reported Sightings';
$view->sightings = [];
$view->errorMessage = null;

// Verify user is logged in (required to view sightings)
if (!isset($_SESSION['user_id'])) {
    $view->errorMessage = 'You must be logged in to view your reported sightings.';
} else {
    $userID = $_SESSION['user_id'];

    $sightingDataSet = new SightingDataSet();
    $petDataSet = new PetDataSet();

    // Fetch all sightings reported by this specific user
    $rawSightings = $sightingDataSet->fetchByUserId($userID);

    // Enrich the data: Add pet name and species to each sighting object
    $enrichedSightings = [];
    foreach ($rawSightings as $sighting) {
        $petID = $sighting->getPetID();
        // Get the associated pet record
        $pet = $petDataSet->fetchPetByID($petID);

        // Check if the pet still exists
        if ($pet) {
            // Add the pet's name and species to the sighting object dynamically
            $sighting->setPetName($pet->getName());
            $sighting->setPetSpecies($pet->getSpecies());
        } else {
            // Handle cases where the pet might have been deleted
            $sighting->setPetName('N/A (Pet Deleted)');
            $sighting->setPetSpecies('N/A');
        }
        $enrichedSightings[] = $sighting;
    }

    $view->sightings = $enrichedSightings;

    // Set error message if the user has no reported sightings
    if (empty($view->sightings)) {
        $view->errorMessage = "You have not reported any sightings yet.";
    }
}

// Load the view
require_once('Views/mysightings.phtml');