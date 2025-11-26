<?php
session_start();
// load required class
require_once('Models/PetDataSet.php');

// make a view class
$view = new stdClass();
$view->pageTitle = 'Browse Pets';
$view->dbMessage = '';
$view->pets = [];

// Get the filter values from th URL
$searchText = isset($_GET['search']) ? $_GET['search'] : null;
$speciesFilter = isset($_GET['species']) ? (array)$_GET['species'] : [];
$statusFilters = isset($_GET['status']) ? (array)$_GET['status'] : [];

//Store the filers so View can remember what the user selected
$view->filters = [
    'search' => $searchText,
    'species' => $speciesFilter,
    'status' => $statusFilters
];

// Call the model to get the pets, passing the new filters
$petDataSet = new PetDataSet();
$view->pets = $petDataSet->fetchFilteredPets($searchText, $speciesFilter, $statusFilters);

// send a results count to the view to show how many results were retrieved
$count = count($view->pets);

if ($count > 0) {
    $view->dbMessage = "$count pet(s) found matching your criteria.";
} else {
    // Determine if the user was searching (any filter is active)
    $isSearching = !empty($searchText) || !empty($speciesFilter) || !empty($statusFilters);

    if ($isSearching) {
        $view->dbMessage = "No pets found matching your filters.";
    } else {
        $view->dbMessage = "No pets found in the database.";
    }
}

// include the View
require_once('Views/listpets.phtml');