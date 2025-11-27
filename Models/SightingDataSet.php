<?php

// load required classes
require_once ('Database.php');
require_once ('SightingData.php');

class SightingDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance(); //Call Database class to get Database object
        $this->_dbHandle = $this->_dbInstance->getdbConnection(); //Use the object to get the Database connection
    }

    // Fetches all sighting records for a specific pet ID.
    public function fetchPetSightings($petID) {
        // Define the SQL query string to get all sightings for a specific pet using a :petID placeholder.
        $sqlQuery = 'SELECT * FROM sightings WHERE pet_id = :petID;';

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // Bind $petID to the :petID placeholder to separate the SQL code from user input
        $statement->bindParam(':petID', $petID);
        $statement->execute();

        $dataSet = []; // Empty array to hold our SightingData objects instead of raw database rows

        // Grab the first row and store in an associative array $row
        while ($row = $statement->fetch()) {
            $dataSet[] = new SightingData($row); // create a SightingData object passing the raw array into its constructor
        }
        return $dataSet; // returns entire $dataSet array now full of SightingData objects
    }

    // Adds a new sighting report to the database.
    public function addSighting($pet_id, $user_id, $comment, $latitude, $longitude) {
        // Define the SQL query string to insert a new sighting using named placeholders.
        $sqlQuery = 'INSERT INTO sightings (pet_id, user_id, comment, latitude, longitude) 
                    VALUES (:pet_id, :user_id, :comment, :latitude, :longitude)';

        // Use the stored Database connection to prepare the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // execute the query by binding the array of data to their named placeholders
        $statement->execute([
            ':pet_id' => $pet_id,
            ':user_id' => $user_id,
            ':comment' => $comment,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
        ]);

        return $statement->rowCount(); // Returns 1 on successful insert
    }

    // Fetches all sighting reports created by a specific user ID.
    public function fetchByUserId($user_id) {
        // SQL query to get all sightings reported by a specific user_id
        $sqlQuery = 'SELECT *
                     FROM sightings 
                     WHERE user_id = :user_id 
                     ORDER BY pet_id'; // Orders results by pet ID

        // Prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();

        $dataSet = [];
        // Loop through results and create SightingData objects
        while ($row = $statement->fetch()) {
            $dataSet[] = new SightingData($row); // Assuming SightingData object creation
        }
        return $dataSet;
    }
}