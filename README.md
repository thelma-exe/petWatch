# PetWatch 🐾

PetWatch is a data-driven, full-stack web application designed to help communities report missing pets and track sightings. The platform provides a centralized database for pet owners and a streamlined reporting system for the public to submit real-time sighting data.

## What It Does
 
**For anyone browsing:**
- Browse all missing and found pets with filtering by species, colour, status, and keyword
- View full pet profiles including photos, descriptions, and sighting history
- Submit a sighting report for any listed pet (with optional GPS coordinates for mapping)
  
**For registered owners/admins:**
- Full CRUD management of pet listings — add, edit, delete, and update status
- A personal "My Pets" dashboard with sort controls
- Sighting management dashboard with filters by pet, user, status, dates, and keyword
- Pagination throughout for handling large datasets
- Role-based access control — only the owner of a listing can manage it
  
## Tech Stack

The application is built using the **MVC (Model-View-Controller)** design pattern to ensure a modular, maintainable, and scalable codebase.

* **Frontend:** Built with HTML5, CSS3, JavaScript, and Bootstrap 5 for a responsive, mobile-friendly interface.
* **Backend:** Developed in Object-Oriented PHP 8.x using a front-controller routing system.
* **Database:** Relational data management using **SQLite via PDO**, utilizing normalized schemas for pet, user, and sighting entities.
* **Security:** Implements `password_hash()` and `password_verify()` for credential security, along with thorough input sanitization and session-based authentication.
## Project Structure

```text
├── /Models         -> Business logic and Database access classes (PDO)
├── /Views          -> Page templates and shared UI components
├── /Images         -> Stored pet photos and assets
├── index.php       -> Application entry point and router
├── map.php         -> Interactive geospatial visualization interface
├── apisearch.php   -> RESTful API endpoint for pet search
├── apisightings.php -> RESTful API endpoint for sighting data
├── petwatch.sqlite -> Relational database storage
└── [CRUD].php      -> Individual controllers for Pet and Sighting management
```
## How to Run Locally
 
1. Make sure **PHP 8+** is installed (via XAMPP or standalone)
2. Clone or download the repo into your server's web folder (e.g. `htdocs/`)
3. Start the built-in PHP server from the project root:
```bash
php -S localhost:8000
```
 
4. Open your browser and go to:
```
http://localhost:8000/index.php
```
 
> The SQLite database petwatch.sqlite is included in the project root — no setup needed.
 
---
 
## Test Accounts
 
Passwords are hashed in the database using `password_hash()`. Use the plaintext passwords below to log in:
 
| Role | Username | Password |
|---|---|---|
| Owner | admin | 4HybTL@HwLYuJ10 |
| Browsing User | Lee | %RTd@3WR4FUJ47 |
 
> Both users can view and search pets without logging in, but browsing users must be logged in to submit a sighting.
 
