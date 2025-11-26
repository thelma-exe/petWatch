<?php
session_start();
// load required class
require_once('Models/PetDataSet.php');
// make a view class
$view = new stdClass();
$view->pageTitle = "Edit Pet";
$view->isLoggedIn = false;
$view->formMessage = '';
$view->pet = null;

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    $view->formMessage = "Error: You must be logged in to edit this pet.";
// Verify user has the required 'admin' role permission
} elseif (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    $view->formMessage = "You don't have permission to view this page.";
} else {

    $view->isLoggedIn = true;
    $userID = $_SESSION['user_id'];

    // --- SECTION 1: Handle Form Submission (POST Request to save changes) ---
    if (isset($_POST['submit'])) {
        // Collect and sanitize all data from the submitted form
        $petID = $_POST['petID'];
        $name = htmlspecialchars($_POST['petName']);
        $species = htmlspecialchars($_POST['species']);
        $breed = htmlspecialchars($_POST['breed']);
        $color = htmlspecialchars($_POST['color']);
        $description = htmlspecialchars($_POST['description']);
        $status = htmlspecialchars($_POST['status']);

        $petDataSet = new PetDataSet();
        $petToUpdate = $petDataSet->fetchPetByID($petID);

        // Verify the pet exists AND belongs to the current user (ownership check)
        if ($petToUpdate != null && $petToUpdate->getUserID() == $userID) {

            // Call model function to update pet details
            $rowsAffected = $petDataSet->updatePet($petID, $name, $species, $breed, $color, $description, $status);

            // Check if the database update was successful
            if ($rowsAffected > 0) {
                $view->formMessage = "Pet details updated successfully!";
            } else {
                $view->formMessage = "No changes were made.";
            }

            // Reload the pet object to display the updated data in the form
            $view->pet = $petDataSet->fetchPetByID($petID);
        } else {
            // Error: Pet not found or user is not the owner
            $view->formMessage = "Error: You do not have permission to edit this pet.";
        }

        // --- SECTION 2: Handle Initial Form Load (GET Request to fetch pet data) ---
    } elseif (isset($_GET['id'])) {
        $petID = $_GET['id'];

        $petDataSet = new PetDataSet();
        $view->pet = $petDataSet->fetchPetByID($petID);

        // Verify the pet was found in the database
        if ($view->pet == null) {
            $view->formMessage = "Error: Pet not be found.";
            // Verify the loaded pet belongs to the current user (ownership check)
        } elseif ($view->pet->getUserID() != $userID) {
            $view->formMessage = "Error: You are not allowed to edit this pet.";
            $view->pet = null; // Clear unauthorized pet data
        } else {
            // Pet found and owned by user: form will load successfully
        }

    } else {
        // Error if no ID was provided in the URL
        $view->formMessage = "Error: No pet ID specified.";
    }
}
// include the View
require_once('Views/editpet.phtml');