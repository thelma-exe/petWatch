# petWatch

PetWatch is a simple PHP + SQLite web application designed to help users register missing or found pets, report sightings, and browse pet reports submitted by others.  
It follows a lightweight MVC-style structure using PHP classes, views, and controllers.

## Features

### -Pet Registration
Users can register a pet as missing or found, including the category (dog, cat, rabbit, etc.), image, location text, and description.

### -Pet Sightings
Users can report sightings of existing pets, adding details such as notes, pet ID, and approximate location.

### -Browse Pets
A public directory that displays all registered pets with their categories and uploaded images.

### -User Profiles
Each user can view the pets they registered and their submitted sightings.

### -SQLite Database
The application stores all pet, user, and sighting data in a single SQLite database file.

### -Minimal MVC Structure
The codebase uses a structured layout inspired by MVC, including:
- Models (database logic)
- Views (UI templates)
- Page controllers (entry PHP files)

## Tech Stack

- **PHP** — core application logic  
- **SQLite** — single-file relational database  
- **HTML/CSS** — views and styling  
- **MVC-inspired architecture**  
- **Git & GitHub** — version control

## Database

The project uses an SQLite database:

petwatch.sqlite

This includes pre-configured tables and sample data.  
No migration steps required.

## Future Enhancements

- Add an interactive map for marking sighting locations  
- Display sightings and missing pets on a map interface  
- Improve UI/UX with responsive components

## License

This project is academic and provided as-is.
