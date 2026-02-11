# TALENAVI TODO API

Technical Home Test - Todo Management API
Built with Laravel 11

This API provides task management features including CRUD operations, chart summary endpoints, and Excel report generation with filtering.

## Installation

1. Clone repository
    ```bash
    git clone https://github.com/Luthfiyanto/Talenavi-api.git
    ```
2. Install dependencies
    ```bash
    composer install
    ```
    Note: Make sure PHP version >= 8.2
3. Copy environment file
    ```bash
    cp .env.example .env
    ```
4. Configure database in .env
5. Generate app key
    ```bash
    php artisan key:generate
    ```
6. Run migration
    ```bash
    php artisan migrate
    ```
7. Seed database
    ```bash
    php artisan db:seed
    ```
8. Run server
    ```bash
    php artisan serve
    ```

## API Endpoints

Import postman collection in folder postman then API will available to consume. Below is the API endpoint summary.

### Task CRUD

| Method | Endpoint       | Description     |
| ------ | -------------- | --------------- |
| GET    | /api/task      | Get all tasks   |
| POST   | /api/task/     | Create new task |
| PUT    | /api/task/{id} | Update task     |
| DELETE | /api/task/{id} | Delete task     |

---

### Excel Report

| Method | Endpoint    |
| ------ | ----------- |
| GET    | /api/report |

---

You can add some parameters to specify your report

| Params   | Description                                      |
| -------- | ------------------------------------------------ |
| title    | string                                           |
| assignee | string                                           |
| priority | "low" / "medium" / "high"                        |
| status   | "pending" / "open" / "in_progress" / "completed" |
| min      | refer to time_tracked (integer)                  |
| max      | refer to time_tracked (integer)                  |
| start    | refer to due_date (datetime)                     |
| end      | refer to due_date (datetime)                     |

---

### Chart Summary

| Method | Endpoint                 |
| ------ | ------------------------ |
| GET    | /api/chart?type=status   |
| GET    | /api/chart?type=priority |
| GET    | /api/chart?type=assignee |

---
