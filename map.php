<?php
// Controller Loads map view which fetches the sighting data via AJAX
session_start();

//make a view class
$view = new stdClass();
$view->pageTitle = 'Pet Sightings Map';
$view->isLoggedIn = isset($_SESSION['user_id']);
$view->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$view->username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$view->role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// include the View
require_once('Views/map.phtml');
