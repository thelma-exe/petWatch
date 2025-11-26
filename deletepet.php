<?php
session_start();
// load required class
require_once('Models/PetDataSet.php');

// make a view class
$view = new stdClass();
$view->pageTitle = "Delete Pets";
$view->formMessage = '';

// Perform Multiple security checks before a pet is deleted
// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    $view->formMessage = "Error: You must be logged in to delete this pet.";
// Verify user has the required 'admin' role permission
} elseif (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    $view->formMessage = "You don't have permission to to perform this action.";
} else {
    // Verify a pet ID was passed via the URL
    if (!isset($_GET['id'])) {
        $view->formMessage = "Error: No petID provided.";
    } else {
        $pet_id = $_GET['id'];
        $user_id = $_SESSION['user_id'];
        $petDataSet = new PetDataSet();

        // Fetch the pet data to verify existence and ownership
        $petToDelete = $petDataSet->fetchPetByID($pet_id);

        // Verify the pet exists in the database
        if ($petToDelete == null) {
            $view->formMessage = "Error: This pet does not exist.";
            // Verify the logged-in user owns this specific pet listing
        } elseif ($petToDelete->getUserID() != $user_id) {
            $view->formMessage = "Error: You don't have permission to delete this pet.";
        } else {
            // All checks passed: proceed with deletion
            $rowsAffected = $petDataSet->deletePet($pet_id);

            // Check deletion result
            if ($rowsAffected > 0) {
                $view->formMessage = "The pet was successfully deleted.";
            } else {
                $view->formMessage = "There was an error deleting this pet.";
            }

        }
    }
}
// include the View
require_once('Views/deletepet.phtml');