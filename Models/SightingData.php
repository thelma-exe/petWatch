<?php
/**
 * Class SightingData
 *
 * This class models a single sighting report. It is used to encapsulate
 * data fetched from the 'sightings' database table.
 * * Note: It also includes mutators/accessors for Pet Name and Species to
 * enrich the data display on the 'My Sightings' page.
 */
class SightingData
{
    /**
     * @var int The unique ID of the sighting record.
     * @var int The ID of the pet that was sighted.
     * @var int The ID of the user who reported the sighting.
     * @var string The user's comment about the sighting (e.g., location, behavior).
     * @var string The geographical latitude of the sighting.
     * @var string The geographical longitude of the sighting.
     * @var string The timestamp when the sighting was reported.
     * @var string The name of the pet (dynamically added by controller).
     * @var string The species of the pet (dynamically added by controller).
     */
    protected $_id, $_petID, $_userID, $_comment, $_latitude, $_longitude, $_timestamp, $_petName, $_petSpecies;

    /**
     * SightingData constructor.
     * Initializes the object using an associative array from a database row.
     * @param array $dbRow An associative array containing sighting data.
     */
    public function __construct($dbRow) {
        $this->_id = $dbRow['id'];
        $this->_petID = $dbRow['pet_id'];
        $this->_userID = $dbRow['user_id'];
        $this->_comment = $dbRow['comment'];
        $this->_latitude = $dbRow['latitude'];
        $this->_longitude = $dbRow['longitude'];
        $this->_timestamp = $dbRow['timestamp'];
    }

    /**
     * Gets the unique sighting ID.
     * @return int
     */
    public function getID() {
        return $this->_id;
    }

    /**
     * Gets the ID of the pet associated with this sighting.
     * @return int
     */
    public function getPetID() {
        return $this->_petID;
    }

    /**
     * Gets the ID of the user who reported the sighting.
     * @return int
     */
    public function getUserID() {
        return $this->_userID;
    }

    /**
     * Gets the user who reported the sighting's comment.
     * @return string
     */
    public function getComment() {
        return $this->_comment;
    }

    /**
     * Gets the latitude of the sighting location.
     * @return string
     */
    public function getLatitude() {
        return $this->_latitude;
    }

    /**
     * Gets the longitude of the sighting location.
     * @return string
     */
    public function getLongitude() {
        return $this->_longitude;
    }

    /**
     * Gets the timestamp of the sighting report.
     * @return string
     */
    public function getTimestamp() {
        return $this->_timestamp;
    }

    /**
     * Gets the pet's name (used for enriched view data).
     * @return string
     */
    public function getPetName() {
        return $this->_petName;
    }

    /**
     * Gets the pet's species (used for enriched view data).
     * @return string
     */
    public function getPetSpecies() {
        return $this->_petSpecies;
    }

    /**
     * Sets the pet's name after fetching from PetData (data enrichment).
     * @param string $name
     */
    public function setPetName($name) {
        $this->_petName = $name;
    }

    /**
     * Sets the pet's species after fetching from PetData (data enrichment).
     * @param string $species
     */
    public function setPetSpecies($species) {
        $this->_petSpecies = $species;
    }

}