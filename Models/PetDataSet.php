<?php

// load required classes
require_once ('Database.php');
require_once ('PetData.php');

class PetDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance(); //Call Database class to get Database object
        $this->_dbHandle = $this->_dbInstance->getdbConnection(); //Use the object to get the Database connection
    }

    public function fetchAllPets() {
        $sqlQuery = 'SELECT * FROM pets'; //Define the SQL query string to get all pets

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = []; // Empty array to hold our PetData objects instead of raw database rows

        // Grab the first row and store in an associative array $row
        while ($row = $statement->fetch()) {
            $dataSet[] = new PetData($row); // create a PetData object passing the raw array into its constructor
        }
        return $dataSet; // returns entire $dataSet array now full of PetData objects
    }

    public function fetchMissingPets($searchText = null) {

        $bindValues = [];

        // Check if search term was provided
        if ($searchText !== null && $searchText != '') {

            // Build the query for searching for lost pets
            $sqlQuery = 'SELECT *
                         FROM pets 
                         WHERE status = "lost"
                         AND (name LIKE ? OR species LIKE ? OR breed LIKE ? OR color LIKE ? OR description LIKE ?) ;';

            // Add a wildcard for partial search matches
            $possible_search_terms = '%' . $searchText . '%';

            // Set the bind values to bind placeholders
            $bindValues = [
                $possible_search_terms,
                $possible_search_terms,
                $possible_search_terms,
                $possible_search_terms,
                $possible_search_terms
            ];
        } else {
            // No search term provided
            // Build the query to show all pets
            $sqlQuery = 'SELECT * FROM pets 
                         WHERE status = "lost"';
        }

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute($bindValues);

        $dataSet = []; // Empty array to hold our PetData objects instead of raw database rows

        // Grab the first row and store in an associative array $row
        while ($row = $statement->fetch()) {
            $dataSet[] = new PetData($row); // create a PetData object passing the raw array into its constructor
        }
        return $dataSet; // returns entire $dataSet array now full of PetData objects
    }

    public function fetchFilteredPets($searchText, $speciesFilter, $statusFilter) {
        $sqlQuery = 'SELECT * FROM pets';

        // Build the WHERE clause dynamically
        $whereClauses = []; // An array to hold our 'WHERE' conditions
        $sqlParams = [];    // An array to hold the values to safely bind

        // Text Search filter
        if ($searchText != null && $searchText != '') {
            $whereClauses[] = '(name LIKE ? OR species LIKE ? OR breed LIKE ? OR description LIKE ?)';
            $searchTerm = '%' . $searchText . '%';
            // Add the same term 4 times for the 4 '?' placeholders
            $sqlParams[] = $searchTerm;
            $sqlParams[] = $searchTerm;
            $sqlParams[] = $searchTerm;
            $sqlParams[] = $searchTerm;
        }

        // Species filter
        if (!empty($speciesFilter)) {
            // Create a string of '?' placeholders, for the species selected
            $placeholders = implode(',', array_fill(0, count($speciesFilter), '?'));

            // Add the new WHERE clause using the SQL IN operator
            $whereClauses[] = "species IN ($placeholders)";

            // Merge the selected species array into the $sqlParams array
            $sqlParams = array_merge($sqlParams, $speciesFilter);
        }

        // Status filter
        if (!empty($statusFilters)) {
            $placeholders = implode(',', array_fill(0, count($statusFilters), '?'));
            $whereClauses[] = "status IN ($placeholders)";
            $sqlParams = array_merge($sqlParams, $statusFilters);
        }

        // Combine all filters into the final query
        if (!empty($whereClauses)) {
            $sqlQuery .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        // Execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // We pass the parameters array directly to execute()
        $statement->execute($sqlParams);

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PetData($row);
        }
        return $dataSet;
    }
    public function fetchByUserId($user_id) {
        // Define the SQL query string to get all pets belonging to a user using a :user_id placeholder.
        $sqlQuery = 'SELECT *
                     FROM pets 
                     WHERE user_id = :user_id;';

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // Bind $username to the :username placeholder to separate the SQL code from user input
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();

        $dataSet = []; // Empty array to hold our PetData objects instead of raw database rows

        // Grab the first row and store in an associative array $row
        while ($row = $statement->fetch()) {
            $dataSet[] = new PetData($row); // create a PetData object passing the raw array into its constructor
        }
        return $dataSet; // returns entire $dataSet array now full of PetData objects
    }

    public function fetchPetByID($pet_id) {
        // Define the SQL query string to get a specific pet using a :pet_id placeholder.
        $sqlQuery = 'SELECT *
                     FROM pets 
                     WHERE id = :pet_id;';
        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // Bind $pet_id to the :pet_id placeholder to separate the SQL code from user input
        $statement->bindParam(':pet_id', $pet_id);
        $statement->execute();

        // Grab the first row and store in an associative array $row
        $row = $statement->fetch();
        if ($row) {
            return new PetData($row); // if true, return a single PetData object
        }
        return null;
    }

    public function addPet($name, $species, $breed, $color, $photo_url, $description, $user_id) {

        // Define the SQL query string to insert a new pet using named placeholders.
        $sqlQuery = 'INSERT INTO pets (name, species, breed, color, photo_url, status, description, date_reported, user_id) 
                    VALUES (:name, :species, :breed, :color, :photo_url, :status, :description, datetime(\'now\'), :user_id);';

        // Use the stored Database connection to prepare the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // execute the query by binding the array of data to their named placeholders
        $statement->execute([
            ':name' => $name,
            ':species' => $species,
            ':breed' => $breed,
            ':color' => $color,
            ':photo_url' => $photo_url,
            ':status' => 'lost',
            ':description' => $description,
            ':user_id' => $user_id,
        ]);

        return $statement->rowCount();
    }

    public function updatePet($pet_id, $name, $species, $breed, $color, $description, $status) {

        $sqlQuery = 'UPDATE pets
                    SET name = :name, species = :species, breed = :breed, color = :color, description = :description, status = :status
                    WHERE id = :petID';
        // Use the stored Database connection to prepare the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([
            ':petID' => $pet_id,
            ':name' => $name,
            ':species' => $species,
            ':breed' => $breed,
            ':color' => $color,
            ':description' => $description,
            ':status' => $status,
        ]);
        return $statement->rowCount();

    }

    public function deletePet($pet_id) {
        $sqlQuery = 'DELETE FROM pets WHERE id = :pet_id';

        // Use the stored Database connection to prepare and execute the query
        $statement = $this->_dbHandle->prepare($sqlQuery);
        // Bind $pet_id to the :pet_id placeholder to separate the SQL code from user input
        $statement->bindParam(':pet_id', $pet_id);
        $statement->execute();

        return $statement->rowCount();
    }

}
