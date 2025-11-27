<?php
/**
 * Class PetData
 *
 * This class models a single Pet entry from the database.
 * It utilizes protected fields for data encapsulation and provides accessors
 * for all properties.
 */
class PetData {

    /**
     * @var int The unique ID of the pet record.
     * @var string The name given to the pet.
     * @var string The species of the pet (e.g., Dog, Cat).
     * @var string The breed of the pet.
     * @var string The color of the pet.
     * @var string The URL to the pet's photo.
     * @var string The status of the pet (e.g., lost, found).
     * @var string A detailed description of the pet.
     * @var string The original timestamp the pet was reported.
     * @var int The ID of the user who reported the pet missing
     */
    protected $_id, $_name, $_species, $_breed, $_color, $_photo_url, $_status, $_description, $_date_reported, $_userID;

    /**
     * PetData constructor.
     * Initializes the object using an associative array from a database row.
     * * @param array $dbRow An associative array containing pet data.
     */
    public function __construct($dbRow) {
        $this->_id = $dbRow['id'];
        $this->_name = $dbRow['name'];
        $this->_species = $dbRow['species'];
        $this->_breed = $dbRow['breed'];
        $this->_color = $dbRow['color'];
        $this->_photo_url = $dbRow['photo_url'];
        $this->_status = $dbRow['status'];
        $this->_description = $dbRow['description'];
        $this->_date_reported = $dbRow['date_reported'];
        $this->_userID = $dbRow['user_id'];
    }

    /**
     * Gets the unique pet ID.
     * @return int
     */
    public function getID() {
        return $this->_id;
    }

    /**
     * Gets the pet's name.
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Gets the pet's species.
     * @return string
     */
    public function getSpecies() {
        return $this->_species;
    }

    /**
     * Gets the pet's breed.
     * @return string
     */
    public function getBreed() {
        return $this->_breed;
    }

    /**
     * Gets the pet's color.
     * @return string
     */
    public function getColor() {
        return $this->_color;
    }

    /**
     * Gets the URL for the pet's photo.
     * @return string
     */
    public function getPhotoURL() {
        return $this->_photo_url;
    }

    /**
     * Gets the pet's current status (lost or found).
     * @return string
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * Gets the pet's description.
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Gets the date the pet was reported, formatted as 'Day Month Year'.
     * @return string
     */
    public function getDateReported() {
        $date = new DateTime($this->_date_reported);
        return $date->format('j M Y');
    }

    /**
     * Gets the ID of the user who reported this pet.
     * @return int
     */
    public function getUserID() {
        return $this->_userID;
    }
}