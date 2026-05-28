# Firalia - Event Management System

## Overview
Firalia is a web-based event management system built with PHP and MySQL. It allows users to create, read, update, and delete events, manage user profiles, and handle event dates and galleries. The system supports different user roles (Admin, Event Manager, User) with role-based access control. (Copia Examen)

## Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL with PDO
- **Frontend**: HTML5, CSS3, JavaScript (Swiper.js)
- **Server**: Apache (XAMPP)
- **Email**: PHPMailer

## Project Structure
```
MP0487_RA5RA6_Firalia/
├── controller/          # Business logic and controllers
├── view/               # Frontend templates and assets
│   ├── CSS/           # Stylesheets
│   ├── JS/            # JavaScript files
│   ├── events/        # Event media storage
│   └── images/        # User profile images
├── model/             # Database schema
└── XML/               # XML and XSL transformation files
```

## Class Architecture

### Class Diagram

```mermaid
classDiagram
    class EventController {
        +CreateEvent()
        +ReadEvent()
        +UpdateEvent(id)
        +DeleteEvent()
        +AddDateEvent()
        +AddGalleryVideoEvent()
        +GetEventsForDeletion()
        +conn() PDO
    }

    class UserController {
        +login()
        +register()
        +logout()
        +deleteUser()
        +updateUser()
        +changePassword()
        +conn() PDO
    }

    class Database {
        -host: String
        -dbname: String
        -username: String
        -password: String
    }

    class PDO {
        +prepare(sql)
        +execute(params)
        +fetch(mode)
        +fetchAll(mode)
        +beginTransaction()
        +commit()
        +rollBack()
    }

    class Session {
        +user_id
        +rol
        +name
        +lastname
        +email
        +username
        +user_image
    }

    class Event {
        -id: Integer
        -nombre: String
        -main_image_path: String
        -image_text_path: String
        -text1: String
    }

    class User {
        -id: Integer
        -user: String
        -name: String
        -lastname: String
        -email: String
        -password: String
        -rol: Integer
        -user_image: String
    }

    class EventDate {
        -id: Integer
        -num_dia: Integer
        -mes: String
        -nombre_dia: String
        -hora: Integer
        -minuto: Integer
        -ciudad: String
        -localizacion: String
        -id_evento: Integer
    }

    class Gallery {
        -id: Integer
        -video: String
        -id_evento: Integer
    }

    EventController --> PDO
    UserController --> PDO
    PDO --> Database
    EventController --> Session
    UserController --> Session
    EventController --> Event
    EventController --> EventDate
    EventController --> Gallery
    UserController --> User
```

---

## User Authentication Flow

### Login Sequence Diagram

```mermaid
sequenceDiagram
    participant Browser
    participant LoginView as Login View
    participant UserController
    participant Database as MySQL DB
    participant Session
    participant Dashboard as Profile Page

    Browser->>LoginView: Load login.php
    LoginView->>Browser: Display login form
    Browser->>UserController: Submit POST (user, password)
    
    UserController->>Database: Prepare & Execute<br/>SELECT * FROM users WHERE user = ?
    Database-->>UserController: User record returned
    
    alt User found AND password verified
        UserController->>Session: Set session variables<br/>(user_id, rol, name, etc)
        
        alt rol == 1 (Admin)
            UserController->>Dashboard: Redirect to profileAdmin.php
        else rol == 2 or 3
            UserController->>Dashboard: Redirect to profile.php
        end
        
        Dashboard->>Browser: Display user dashboard
    else Invalid credentials
        UserController->>Session: Set error_message
        UserController->>LoginView: Redirect with error
        LoginView->>Browser: Display error message
    end
```

---

## Event Management Flow

### Create Event Sequence Diagram

```mermaid
sequenceDiagram
    participant Browser
    participant CreateEventView as Create Event View
    participant EventController
    participant FileSystem as File System
    participant Database as MySQL DB
    participant Session

    Browser->>CreateEventView: Load createEvent.php
    CreateEventView->>Browser: Display event form
    Browser->>EventController: Submit POST with files<br/>(nombre, text1, images)
    
    EventController->>EventController: Validate file types<br/>(JPG, JPEG, PNG)
    
    alt Files valid
        EventController->>FileSystem: Create event directory<br/>view/events/[NOMBRE]/
        FileSystem-->>EventController: Directory created
        
        EventController->>FileSystem: Move uploaded files to<br/>event directory
        FileSystem-->>EventController: Files saved
        
        EventController->>Database: Prepare & Execute<br/>INSERT INTO eventos
        Database-->>EventController: Success
        
        EventController->>Session: Set success_message
        EventController->>CreateEventView: Redirect with success
        CreateEventView->>Browser: Display success message
    else File validation fails
        EventController->>Session: Set error_message
        EventController->>CreateEventView: Redirect with error
        CreateEventView->>Browser: Display error
    end
```

---

### Update Event Sequence Diagram

```mermaid
sequenceDiagram
    participant Browser
    participant UpdateEventView as Update Event View
    participant EventController
    participant FileSystem
    participant Database as MySQL DB
    participant Session

    Browser->>UpdateEventView: Load updateEvent.php?id=X
    UpdateEventView->>Browser: Display form with event data
    Browser->>EventController: Submit POST with files<br/>(id, nombre, text1, images)
    
    EventController->>Database: SELECT event data WHERE ID = ?
    Database-->>EventController: Event record
    
    EventController->>EventController: Validate file types (if provided)
    
    EventController->>FileSystem: Create/Update event directory
    
    alt Main image provided
        EventController->>FileSystem: Move main image to directory
        FileSystem-->>EventController: File saved
    end
    
    alt Text image provided
        EventController->>FileSystem: Move text image to directory
        FileSystem-->>EventController: File saved
    end
    
    EventController->>Database: Prepare & Execute<br/>UPDATE eventos SET ... WHERE ID = ?
    Database-->>EventController: Update complete
    
    EventController->>Session: Set success_message
    EventController->>UpdateEventView: Redirect to updateSuccess.php
    UpdateEventView->>Browser: Display success page
```

---

### Delete Event Sequence Diagram

```mermaid
sequenceDiagram
    participant Browser
    participant DeleteEventView as Delete Event View
    participant EventController
    participant Database as MySQL DB
    participant FileSystem
    participant Session

    Browser->>DeleteEventView: Load deleteEvent.php
    DeleteEventView->>Browser: Display events dropdown
    Browser->>EventController: Submit POST<br/>(delete, id)
    
    EventController->>EventController: Validate event ID
    
    EventController->>Database: START TRANSACTION
    
    EventController->>Database: SELECT event data WHERE ID = ?
    Database-->>EventController: Event record
    
    EventController->>Database: DELETE FROM fechas_eventos<br/>WHERE ID_EVENTO = ?
    EventController->>Database: DELETE FROM galeria_eventos<br/>WHERE ID_EVENTO = ?
    EventController->>Database: DELETE FROM eventos WHERE ID = ?
    
    EventController->>FileSystem: Delete main image file
    EventController->>FileSystem: Delete text image file
    EventController->>FileSystem: Remove event directory<br/>(if empty)
    
    EventController->>Database: COMMIT TRANSACTION
    
    EventController->>Session: Set success_message
    EventController->>DeleteEventView: Redirect to deleteEvent.php
    DeleteEventView->>Browser: Display success message
```

---

## Database Schema

### Users Table
- `ID` (INT, PRIMARY KEY)
- `USER` (VARCHAR)
- `NAME` (VARCHAR)
- `LASTNAME` (VARCHAR)
- `EMAIL` (VARCHAR)
- `PASSWORD` (VARCHAR - hashed)
- `ROL` (INT) - 1: Admin, 2: Event Manager, 3: User
- `USER_IMAGE` (VARCHAR - path to image)

### Eventos Table
- `ID` (INT, PRIMARY KEY)
- `NOMBRE` (VARCHAR)
- `MAIN_IMAGE_PATH` (VARCHAR)
- `IMAGE_TEXT_PATH` (VARCHAR)
- `TEXT1` (TEXT)

### Fechas_Eventos Table (Event Dates)
- `ID` (INT, PRIMARY KEY)
- `NUM_DIA` (INT)
- `MES` (VARCHAR)
- `NOMBRE_DIA` (VARCHAR)
- `HORA` (INT)
- `MINUTO` (INT)
- `CIUDAD` (VARCHAR)
- `LOCALIZACION` (VARCHAR)
- `ID_EVENTO` (INT, FOREIGN KEY)

### Galeria_Eventos Table (Event Gallery)
- `ID` (INT, PRIMARY KEY)
- `VIDEO` (VARCHAR - URL)
- `ID_EVENTO` (INT, FOREIGN KEY)

---

## Key Features

### User Management
- **Registration**: Users can register with email validation and password hashing
- **Login**: Secure login with password verification
- **Password Change**: Users can update their password with validation
- **Profile Update**: Users can update their profile information
- **Account Deletion**: Users can delete their accounts

### Event Management (Admin/Event Manager)
- **Create Events**: Add new events with main image, text image, and description
- **Read Events**: View all events in the system
- **Update Events**: Modify event details and images
- **Delete Events**: Remove events and associated data (cascading delete)

### Event Details
- **Add Event Dates**: Schedule events with location and time information
- **Add Gallery Videos**: Add video links to event galleries
- **Image Management**: Upload and manage event promotional images

### Session Management
- User session data stored including user ID, role, name, email, and profile image
- Role-based redirects (Admin → profileAdmin.php, Users → profile.php)

---

## Security Features

- ✅ **Password Hashing**: Uses PHP `password_hash()` with PASSWORD_DEFAULT algorithm
- ✅ **SQL Injection Prevention**: Uses PDO prepared statements with parameterized queries
- ✅ **Input Sanitization**: `htmlspecialchars()` and `trim()` applied to user inputs
- ✅ **File Upload Validation**: 
  - File type validation (JPG, JPEG, PNG only)
  - File size limits enforced
  - Files saved outside web root where possible
- ✅ **Email Validation**: Uses `filter_var()` with FILTER_VALIDATE_EMAIL
- ✅ **Error Handling**: PDO exception handling with rollback on transaction failure

---

## Installation & Setup

### Prerequisites
- XAMPP or similar Apache/PHP/MySQL stack
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Steps
1. Place project in `htdocs` folder
2. Import `model/db.sql` into MySQL
3. Update database credentials in:
   - `controller/databasePDO.php`
   - `controller/database.php`
4. Create necessary directories:
   - `view/events/`
   - `controller/images/`
5. Access via: `http://localhost/MP0487/MP0487_RA5RA6_Firalia/view/index.php`

---

## API Endpoints (Controller Actions)

### UserController
- `POST` - login: Authenticate user
- `POST` - register: Create new user account
- `POST` - logout: End user session
- `POST` - updateUser: Modify user profile
- `POST` - changePassword: Update password
- `POST` - deleteUser: Remove user account

### EventController
- `POST` - create: Add new event
- `POST` - read: Retrieve all events
- `POST` - update: Modify event details
- `POST` - delete: Remove event
- `POST` - date: Add event date/schedule
- `POST` - galleryVideo: Add video to event gallery

---

## Development Notes

- Both controllers use PDO for database operations
- Database connections are established per request (not persistent)
- Session variables are used for user context and messaging
- File operations include directory creation and cleanup
- Transaction support for complex operations (event deletion)
- Email functionality available via PHPMailer (see `enviarEmail.php`)

---

## Future Enhancements

- [ ] API REST endpoints
- [ ] User authentication tokens
- [ ] Event search and filtering
- [ ] Advanced permission system
- [ ] Event attendance tracking
- [ ] Email notifications
- [ ] Image optimization
- [ ] Pagination for event listing

---

## Authors & Contributors
Firalia Development Team - RA5/RA6 Project

---

## License
All rights reserved. Firalia © 2026
