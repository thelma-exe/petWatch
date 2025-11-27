<?php
/**
 * Class UserData
 *
 * This class models a single User entry from the database .
 * It utilizes protected fields for data encapsulation and provides accessors
 * for all properties .
 */
class UserData
{
    /**
     * @var int The unique ID of the user record.
     * @var string The user's chosen username.
     * @var string The user's email address.
     * @var string The hashed password string (securely stored).
     * @var string The role of the user (e.g., 'user', 'admin').
     */
    protected $_id, $_username, $_email, $_passwordHash, $_role;

    /**
     * UserData constructor.
     * Initializes the object using an associative array from a database row.
     * @param array $dbRow An associative array containing user data.
     */
    public function __construct($dbRow) {
        $this->_id = $dbRow['id'];
        $this->_username = $dbRow['username'];
        $this->_email = $dbRow['email'];
        $this->_passwordHash = $dbRow['password_hash'];
        $this->_role = $dbRow['role'];
    }

    /**
     * Gets the unique user ID.
     * @return int
     */
    public function getID() {
        return $this->_id;
    }

    /**
     * Gets the user's username.
     * @return string
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     * Gets the user's email address.
     * @return string
     */
    public function getEmail() {
        return $this->_email;
    }

    /**
     * Gets the user's hashed password.
     * @return string
     */
    public function getPasswordHash() {
        return $this->_passwordHash;
    }

    /**
     * Gets the user's role.
     * @return string
     */
    public function getRole() {
        return $this->_role;
    }
}