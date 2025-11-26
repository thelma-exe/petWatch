<?php
session_start();
// make a view class
$view = new stdClass();
$view->pageTitle = "Add Pet";
$view->isLoggedIn = false;
$view->formMessage = '';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, set error message.
    $view->formMessage = 'You must be logged in to add a pet.';
} elseif (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Check if the user meets the required role permission.
    $view->formMessage = "You don't have permission to view this page.";
} else {
    // User is logged in and meets role requirements.
    $view->isLoggedIn = true;
    $userID = $_SESSION['user_id']; // Store the user ID for database insertion

    // Check if the add Pet form was submitted
    if (isset($_POST['submit'])) {

        // Check if a file was uploaded successfully (UPLOAD_ERR_OK = 0)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

            // --- File Processing and Path Generation ---
            $target_dir = "Images/pets/";
            // Get the file extension and convert it to lowercase
            $imageFileType = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            // Create a unique file name using uniqid()
            $newFileName = 'pet_' . uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $newFileName;

            // Attempt to move the temporary uploaded file to the permanent target directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {

                // --- Database Insertion ---
                require_once('Models/PetDataSet.php');

                // Sanitize all input fields using htmlspecialchars() for security
                $pet_name = htmlspecialchars($_POST['petName']);
                $species = htmlspecialchars($_POST['species']);
                $breed = htmlspecialchars($_POST['breed']);
                $color = htmlspecialchars($_POST['color']);
                $description = htmlspecialchars($_POST['description']);

                $petDataSet = new PetDataSet();
                // Call model function to insert pet data into the database
                $rowsAffected = $petDataSet->addPet($pet_name, $species, $breed, $color, $target_file, $description, $userID);

                // Check if the insertion was successful
                if ($rowsAffected > 0) {
                    $view->formMessage = "Pet added successfully.";
                } else {
                    $view->formMessage = "Error: Could not save pet to database.";
                }
            } else {
                // Error: File move failed
                $view->formMessage = "Error: Could not move uploaded file.";
            }
        } else {
            // Error: File upload failed
            $view->formMessage = "Error: There was a problem with the file upload. Please try again.";
        }
    }
}
// include the View
require_once('Views/addpet.phtml');