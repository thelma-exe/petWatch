<?php

// load required classes
require_once ('Database.php');
require_once ('UserData.php');

class UserDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance(); //Call Database class to get Database object
        $this->_dbHandle = $this->_dbInstance->getdbConnection(); //Use the object to get the Database connection
    }

    // Fetches all user records from the database.
    public function fetchAllUsers() {
        $sqlQuery = 'SELECT * FROM users;'; //Define the SQL query string to get all users

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = []; // Empty array to hold our UserData objects instead of raw database rows

        // Grab the first row and store in an associative array $row
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row); // create a UserData object passing the raw array into its constructor
        }
        return $dataSet; // returns entire $dataSet array now full of UserData objects
    }

    // Fetches a single user record based on the username.
    public function fetchByUsername($username) {
        // Define the SQL query string to get a specific user using a :username placeholder.
        $sqlQuery = 'SELECT * FROM users WHERE username = :username;';

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // Bind $username to the :username placeholder to separate the SQL code from user input
        $statement->bindParam(':username', $username);
        $statement->execute();

        // Grab the first row and store in an associative array $row
        $row = $statement->fetch();
        if ($row) {
            return new UserData($row); // if true, return a single UserData object
        }
        return null; //return false if no user was found
    }

}