# Copilot Instructions for Secretaria Project

## Overview
This project is a Laravel-based web application. It leverages Laravel's MVC architecture, Eloquent ORM, and various Laravel features such as queues, jobs, and service providers. The application appears to manage user registrations, exams, and notifications.

## Key Components

### 1. **Models**
   - Located in `app/Models/`.
   - Represent database entities such as `User`, `Inscription`, `ExamLocation`, etc.
   - Follow Laravel's Eloquent ORM conventions.

### 2. **Controllers**
   - Located in `app/Http/Controllers/`.
   - Handle HTTP requests and responses.
   - Organized by feature (e.g., `UserController`, `ExamController`).

### 3. **Services**
   - Located in `app/Services/`.
   - Contain business logic, such as `ExamAllocationService` and `MailService`.
   - Promote code reusability and separation of concerns.

### 4. **Jobs**
   - Located in `app/Jobs/`.
   - Handle background tasks, such as sending notifications (`SendCallNotificationJob`).

### 5. **Routes**
   - Defined in `routes/`.
   - Organized by context (e.g., `admin.php`, `user.php`).

### 6. **Views**
   - Located in `resources/views/`.
   - Blade templates for rendering HTML.

### 7. **Exports**
   - Located in `app/Exports/`.
   - Use the `Maatwebsite/Excel` package for exporting data.

## Developer Workflows

### 1. **Running the Application**
   - Start the development server:
     ```bash
     php artisan serve
     ```

### 2. **Database Migrations**
   - Run migrations to set up the database schema:
     ```bash
     php artisan migrate
     ```

### 3. **Running Tests**
   - Execute the test suite:
     ```bash
     php artisan test
     ```

### 4. **Queue Workers**
   - Start the queue worker for processing jobs:
     ```bash
     php artisan queue:work
     ```

## Project-Specific Conventions

1. **Helper Functions**
   - Located in `app/Helpers/`.
   - Example: `GlobalDataHelper` for shared utility functions.

2. **Custom Rules**
   - Located in `app/Rules/`.
   - Example: `CpfRule` for validating Brazilian CPF numbers.

3. **Service Providers**
   - Located in `app/Providers/`.
   - Example: `AppServiceProvider` for binding services to the container.

4. **Exports**
   - Follow the structure in `app/Exports/`.
   - Use `Maatwebsite/Excel` for generating Excel files.

## External Dependencies

1. **Maatwebsite/Excel**
   - Used for data export functionality.
   - Documentation: [Maatwebsite/Excel](https://docs.laravel-excel.com/3.1/).

2. **Laravel Queues**
   - Used for background job processing.
   - Ensure `queue:work` is running for jobs to be processed.

3. **Mail**
   - Configured in `config/mail.php`.
   - Used for sending notifications.

## Examples

### Export Example
- File: `app/Exports/UsersWithInscriptionsExport.php`
- Purpose: Export users with their inscriptions.

### Job Example
- File: `app/Jobs/SendCallNotificationJob.php`
- Purpose: Send notifications for calls.

## Notes
- Follow Laravel conventions for naming and structure.
- Use dependency injection for services and repositories.
- Document any new patterns or workflows in this file.