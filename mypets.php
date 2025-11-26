<?php
session_start();

// load required class
require_once('Models/PetDataSet.php');

// make a view class
$view = new stdClass();
$view->pageTitle = 'My Pets';
$view->formMessage ='';
$view->errorMessage = null; // Error message will be set here if checks fail

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    $view->errorMessage = "You must be logged in to view your pets.";
// Verify user has the required 'admin' role permission
} elseif(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    $view->errorMessage = "You don't have permission to view this page.";
} else {
    // User is logged in and meets role requirements.
    $userID = $_SESSION['user_id'];
    $petDataSet = new PetDataSet();

    // Fetch all pets associated with the logged-in user's ID
    $view->pets = $petDataSet->fetchByUserId($userID);
}

require_once('Views/mypets.phtml');