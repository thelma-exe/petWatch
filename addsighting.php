<?php

session_start();
// load required classes
require_once('Models/PetDataSet.php');
require_once('Models/SightingDataSet.php');
// make a view class
$view = new stdClass();
$view->pageTitle = "Add Sighting";
$view->formMessage = "";
$view->pet = null;
$view->allLostPets = [];

// Verify user is logged in (required to report a sighting)
if (!isset($_SESSION['user_id'])) {
    $view->formMessage = "Please log in to add a sighting.";
} else {
    $userID = $_SESSION['user_id'];
    $petDataSet = new PetDataSet();

    // --- SECTION 1: Handle Form Submission (POST Request) ---
    if (isset($_POST['submit'])) {
        // Collect data from the submitted form
        $petID = $_POST['pet_id'];
        // Sanitize input fields for security
        $comment = htmlspecialchars($_POST['comment']);
        $latitude = htmlspecialchars($_POST['latitude']);
        $longitude = htmlspecialchars($_POST['longitude']);

        // Basic validation check
        if (empty($petID)) {
            $view->formMessage = "Error: You must select a pet from the list.";
        } elseif (empty($latitude) || empty($longitude)) {
            $view->formMessage = "latitude and longitude are required";
        } else {
            // Fetch pet data to check status before saving sighting
            $currentPet = $petDataSet->fetchPetByID($petID);

            // Prevent sighting if pet is already marked as found
            if ($currentPet && $currentPet->getStatus() == 'found') {
                $view->formMessage = "Error: This pet is already marked as **FOUND**. No new sightings are needed.";
            } else {
                // All checks passed: proceed with saving the sighting
                $sightingDataSet = new SightingDataSet();
                $rowsAffected = $sightingDataSet->addSighting($petID, $userID, $comment, $latitude, $longitude);

                // Check database insertion result
                if ($rowsAffected > 0) {
                    $view->formMessage = "Success, Your sighting has been added.";
                } else {
                    $view->formMessage = "Error: There was a problem reporting your sighting.";
                }
            }
        }
    }

    // --- SECTION 2: Handle Form Load (GET Request) ---

    // Check if a pet ID was passed in the URL (i.e., user clicked "Add Sighting" from a pet's page)
    $petIDFromURL = null;
    if (isset($_GET['pet_id'])) {
        $petIDFromURL = $_GET['pet_id'];
    }

    if ($petIDFromURL != null) {
        // Load the specific pet requested from the URL
        $view->pet = $petDataSet->fetchPetByID($petIDFromURL);

        // Validate the pet from the URL
        if ($view->pet == null) {
            $view->formMessage = "Error: Specified pet was not found.";
        } elseif ($view->pet->getStatus() == 'found') {
            $view->formMessage = "Error: This pet has already been **FOUND** and recovered. New sightings cannot be added.";
            $view->pet = null; // Prevent the form from pre-selecting the pet
        } else {
            // Pet found, status is 'lost': pre-select this pet in the form.
        }
    } else {
        // No specific pet ID in URL: Load a list of all lost pets for the user to choose from.
        $view->pet = null;
        $view->allLostPets = $petDataSet->fetchFilteredPets(null, null, 'lost');
    }
}
// include the View
require_once('Views/addsighting.phtml');