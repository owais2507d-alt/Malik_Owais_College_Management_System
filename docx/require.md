# College Event Management System

## Project Requirements Specification

**Event Theme:** International Fiesta 2026 – "Voices Unmuted: The Spectrum"

**Project Name:** College Event Management System

**Framework:** Laravel (PHP)

**Database:** MySQL

**Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript

**Template Reference:** TailAdmin – Laravel Admin Dashboard Template

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Main Modules of the System](#2-main-modules-of-the-system)
3. [Functional Requirements](#3-functional-requirements)
4. [Non-Functional Requirements](#4-non-functional-requirements)
5. [Technology Stack](#5-technology-stack)
6. [Database Design and Tables](#6-database-design-and-tables)
7. [User Interface and Design Requirements](#7-user-interface-and-design-requirements)
8. [Expected Project Outcome](#8-expected-project-outcome)
9. [Project Conclusion](#9-project-conclusion)

---

## 1. Project Overview

### 1.1 Project Objective

The objective of the College Event Management System is to develop a web-based platform that simplifies and streamlines the management of college events.

The system will allow administrators to create, manage, and monitor college events, while students will be able to browse upcoming events, view event details, register for events, and track their registration status.

The system will maintain organized records of events, event categories, student registrations, and participants in a centralized database.

### 1.2 Project Scope

The system will provide separate interfaces for administrators and students. It will include event management, student registration, participant management, event categorization, registration approval, and event status tracking.

The application will be responsive, secure, and user-friendly, ensuring accessibility across desktop and mobile devices.

---

## 2. Main Modules of the System

### 2.1 Admin Panel

The Admin Panel will provide administrators with complete control over the event management system.

**Features:**

- Secure administrator login and logout.
- Dashboard displaying the total number of events, registered students, and event registrations.
- Create, view, update, and delete events.
- Manage student event registrations.
- Approve or reject registration requests.
- Manage event categories.
- View registered participants for individual events.
- Monitor event capacity and registration status.
- Manage event details, schedules, venues, and images.
- Access relevant event and registration records.

> **Admin Dashboard Template Reference:** TailAdmin – Laravel Admin Dashboard Template.

### 2.2 Student Panel

The Student Panel will allow students to access and manage their event-related activities.

**Features:**

- Student account registration, login, and logout.
- View and update personal profile information.
- Browse upcoming college events.
- View complete event details, including descriptions, dates, times, venues, and available seats.
- Register for available events.
- View personal event registrations.
- Track registration status, including pending, approved, or rejected.
- Cancel an event registration when cancellation is permitted.
- View relevant information about registered events.

### 2.3 Event Management Module

The Event Management Module will allow administrators to maintain complete information about college events.

**Each event will include the following details:**

| Field | Description |
|-------|-------------|
| Event title | Name of the event |
| Event description | Detailed description of the event |
| Event category | Sports, Seminars, Workshops, Cultural Events, etc. |
| Event date | Date on which the event is held |
| Start time and end time | Duration of the event |
| Event venue | Location of the event |
| Event poster or image | Visual representation of the event |
| Maximum participant capacity | Maximum number of students allowed |
| Registration deadline | Last date to register |
| Event status | Upcoming, Ongoing, or Completed |

The system will display events according to their status and ensure that event information remains organized and accessible.

---

## 3. Functional Requirements

The system must meet the following functional requirements:

| # | Requirement | Description |
|---|-------------|-------------|
| 1 | **User Authentication** | Provide secure login, registration, and logout functionality. |
| 2 | **Role-Based Access Control** | Maintain separate dashboards and permissions for administrators and students. |
| 3 | **Event CRUD Operations** | Allow administrators to create, view, update, and delete events. |
| 4 | **Event Search and Filtering** | Allow users to search for events and filter them by category. |
| 5 | **Student Event Registration** | Allow students to register for available events. |
| 6 | **Duplicate Registration Prevention** | Prevent students from registering multiple times for the same event. |
| 7 | **Registration Approval and Rejection** | Allow administrators to approve or reject student registration requests. |
| 8 | **Participant Management** | Allow administrators to view the list of registered participants for each event. |
| 9 | **Form Validation** | Validate user inputs on the server side and display appropriate error messages when invalid information is submitted. |
| 10 | **Database Management** | Store and maintain event details, student information, categories, and registration records in the database. |
| 11 | **Event Capacity Management** | Prevent new registrations when an event reaches its maximum participant capacity. |
| 12 | **Registration Deadline Enforcement** | Prevent registration after the specified registration deadline. |
| 13 | **Event Status Management** | Display upcoming, ongoing, and completed events separately. |
| 14 | **Registration Status Tracking** | Allow students to view the current status of their registrations. |
| 15 | **Registration Cancellation** | Allow students to cancel registrations when cancellation is enabled and permitted by the system. |
| 16 | **Image Upload Management** | Allow administrators to upload event posters and images with appropriate validation. |
| 17 | **Dashboard Statistics** | Display relevant statistics for events, students, registrations, and participants. |

---

## 4. Non-Functional Requirements

### 4.1 Usability

- The interface must be simple, intuitive, and user-friendly.
- Navigation must be clear and consistent across all pages.
- Forms and dashboards must be easy to understand and operate.

### 4.2 Responsiveness

- The website must be responsive and compatible with desktop computers, tablets, and mobile devices.
- Bootstrap 5 must be used to support responsive layouts.

### 4.3 Security

- User passwords must be securely hashed.
- Unauthorized users must not be permitted to access the Admin Panel.
- Role-based authorization must be enforced on the server side.
- Forms must use server-side validation.
- Appropriate protection must be implemented against common web vulnerabilities.
- Authentication and session management must follow Laravel security best practices.

### 4.4 Data Integrity

- Database relationships must be properly defined.
- Duplicate event registrations must be prevented.
- Event capacity must be enforced consistently.
- Event and registration records must remain accurate and consistent.

### 4.5 File Upload Security

- Uploaded event images must be validated for permitted file types and maximum file size.
- Uploaded files must be stored and accessed securely.

### 4.6 Performance and Reliability

- Database queries should be optimized for efficient data retrieval.
- The system should handle normal event browsing and registration activities reliably.
- Appropriate error handling must be implemented.

### 4.7 Maintainability

- The application must follow Laravel's MVC architecture.
- Code should be modular, readable, and maintainable.
- Database operations should use Laravel Eloquent ORM wherever appropriate.

---

## 5. Technology Stack

| Technology | Purpose |
|------------|---------|
| **Laravel (PHP)** | Backend development and application logic |
| **MySQL** | Database management and data storage |
| **Blade Templates** | Dynamic frontend page rendering |
| **HTML5** | Webpage structure |
| **CSS3** | Styling and visual presentation |
| **Bootstrap 5** | Responsive design and UI components |
| **JavaScript** | Interactive frontend functionality |
| **Laravel Authentication** | User authentication and session management |
| **Laravel Eloquent ORM** | Database operations and relationships |
| **TailAdmin Laravel Template** | Admin dashboard interface reference |

---

## 6. Database Design and Tables

The database will be designed to maintain structured and consistent records of users, events, categories, and registrations.

### 6.1 Users Table

Stores account information for administrators and students.

| Field | Type | Description |
|-------|------|-------------|
| `id` | BIGINT UNSIGNED, PK | Primary key |
| `name` | VARCHAR(255) | User's full name |
| `email` | VARCHAR(255), UNIQUE | User's email address |
| `password` | VARCHAR(255) | Hashed password |
| `role` | ENUM/VARCHAR | Distinguishes admin from student |
| `created_at` | TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | Record update timestamp |

> The `role` field will distinguish administrators from students.

### 6.2 Event Categories Table

Stores the categories used to organize college events.

| Field | Type | Description |
|-------|------|-------------|
| `id` | BIGINT UNSIGNED, PK | Primary key |
| `name` | VARCHAR(255) | Category name |
| `description` | TEXT | Category description |
| `created_at` | TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | Record update timestamp |

### 6.3 Events Table

Stores all event-related information.

| Field | Type | Description |
|-------|------|-------------|
| `id` | BIGINT UNSIGNED, PK | Primary key |
| `category_id` | BIGINT UNSIGNED, FK | References `event_categories.id` |
| `title` | VARCHAR(255) | Event title |
| `description` | TEXT | Event description |
| `event_date` | DATE | Date of the event |
| `start_time` | TIME | Event start time |
| `end_time` | TIME | Event end time |
| `venue` | VARCHAR(255) | Event venue |
| `image` | VARCHAR(255) | Path to event poster/image |
| `max_participants` | INT UNSIGNED | Maximum participant capacity |
| `registration_deadline` | DATETIME | Last date/time to register |
| `status` | ENUM | Upcoming / Ongoing / Completed |
| `created_at` | TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | Record update timestamp |

> The `category_id` field will establish a relationship between events and event categories.

### 6.4 Event Registrations Table

Stores student registration records for individual events.

| Field | Type | Description |
|-------|------|-------------|
| `id` | BIGINT UNSIGNED, PK | Primary key |
| `user_id` | BIGINT UNSIGNED, FK | References `users.id` (student) |
| `event_id` | BIGINT UNSIGNED, FK | References `events.id` |
| `registration_status` | ENUM | Pending / Approved / Rejected |
| `registered_at` | TIMESTAMP | When the registration was made |
| `created_at` | TIMESTAMP | Record creation timestamp |
| `updated_at` | TIMESTAMP | Record update timestamp |

> A **unique database constraint** on the combination of `user_id` and `event_id` should prevent duplicate registrations.

### 6.5 Database Relationships

The following relationships will be implemented:

- **One event category** can contain **multiple events**.
- **One event** belongs to **one category**.
- **One student** can register for **multiple events**.
- **One event** can have **multiple student registrations**.
- **Each registration** belongs to **one student** and **one event**.

> Laravel Eloquent relationships and appropriate foreign key constraints will be used to maintain data integrity.

---

## 7. User Interface and Design Requirements

The system interface will be developed using HTML5, CSS3, Bootstrap 5, Blade Templates, and JavaScript.

### 7.1 Public Pages

| # | Page | Description |
|---|------|-------------|
| 1 | Home page | Landing page with event highlights |
| 2 | Upcoming events page | List of upcoming events |
| 3 | Event details page | Full details of a selected event |
| 4 | Student registration page | Account creation for students |
| 5 | Login page | Authentication page for all users |

### 7.2 Admin Pages

| # | Page | Description |
|---|------|-------------|
| 1 | Admin dashboard | Statistics and overview |
| 2 | Event management page | List and manage all events |
| 3 | Add and edit event pages | Forms to create/update events |
| 4 | Event category management page | Manage event categories |
| 5 | Student registration management page | Approve/reject registrations |
| 6 | Participant list page | View participants per event |

### 7.3 Student Pages

| # | Page | Description |
|---|------|-------------|
| 1 | Student dashboard | Personal overview and stats |
| 2 | Student profile page | View/update profile |
| 3 | Browse events page | Explore and search events |
| 4 | Event details and registration page | View details and register |
| 5 | My registrations page | Track personal registrations |

> The interface should maintain **consistent styling**, **clear navigation**, **appropriate form validation messages**, and a **responsive layout**.

---

## 8. Expected Project Outcome

Upon completion, the College Event Management System will provide a centralized platform for managing college events efficiently.

**The system is expected to:**

- Simplify the process of creating and managing college events.
- Provide students with convenient access to event information.
- Enable online event registration and registration status tracking.
- Improve participant record management.
- Reduce duplicate registrations and manual recordkeeping.
- Provide administrators with an organized dashboard for monitoring events and registrations.
- Maintain secure, structured, and reliable event-related data.

---

## 9. Project Conclusion

The College Event Management System will be developed using **Laravel**, **MySQL**, **Bootstrap 5**, and related web technologies. It will provide dedicated administrative and student interfaces, structured database management, secure authentication, and comprehensive event registration functionality.

The project will support the effective organization and management of college events, including activities associated with **International Fiesta 2026 – "Voices Unmuted: The Spectrum."**

The final implementation will follow the requirements outlined in this document, with emphasis on:

- **Security**
- **Usability**
- **Responsiveness**
- **Data Integrity**
- **Maintainability**

---

*Document Version: 1.0*
*Last Updated: 2026*