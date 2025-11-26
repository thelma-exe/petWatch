<?php
session_start();

// Load required classes
require_once('Models/UserDataSet.php');

// make a view class
$view = new stdClass;
$view->pageTitle = "Login";
$view->isLoggedIn = false;
$view->loginSuccess = '';
$view->loginError = ''; // Initialize error message

// check if the user is already logged in
if (isset($_SESSION['username'])) {
    $view->isLoggedIn = true;
    $view->loginSuccess = $_SESSION['username'] . ' is Logged In';
}

// Check if the login form was submitted
if (isset($_POST['submit'])) {
    // Collect and sanitize input
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Give the form data to the Model via the UserDataSet class
    $userDataSet = new UserDataSet();
    // Attempt to fetch the user by the provided username
    $user = $userDataSet->fetchByUsername($username);

    // Check if the user exists
    if ($user != null) {
        // User found, now check the password
        $passwordHash = $user->getPasswordHash();

        // Check if password is correct using password_verify
        if (password_verify($password, $passwordHash)) {
            // Since password is correct, Store user details in the session
            $_SESSION['user_id'] = $user->getID();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['role'] = $user->getRole();

            // Login complete
            $view->isLoggedIn = true;
            $view->loginSuccess = $user->getUsername() . ' Successfully Logged In';
        } else {
            // The password is wrong
            $view->loginError = "Invalid username or password!";
        }
    } else {
        // The user wasn't found
        $view->loginError = "Invalid username or password!";
    }
}
// include the View
require_once('Views/login.phtml');