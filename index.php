<?php
session_start();
// make a view class
$view = new stdClass();
$view->pageTitle = 'Welcome to PetWatch';

// include the View
require_once('Views/index.phtml');