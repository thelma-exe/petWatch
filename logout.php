<?php
//start the session to get access to it

session_start();
// Empty the session array
$_SESSION = array();
//destroy the session
session_destroy();
// make a view class
$view = new stdClass;
$view->pageTitle = "Logged out";
$view->logoutMessage = "You have successfully logged out.";

// include the View
require_once('Views/logout.phtml');
