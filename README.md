# Task Manager

A Laravel app where users log in, manage their tasks by category, and search, filter, and paginate them. The tasks page loads its data from a JSON API with Axios.

## Features

- Register, log in, and log out
- Create, view, edit, and delete tasks (title, description, status, due date)
- Categories: Work, School, Personal
- Search, category filter, sort, and pagination
- JSON API for tasks and categories
- Seeders and factories for users, categories, and tasks
- Master layout and reusable Blade components (button, input, textarea, select, modal, alert)

## Tech stack

Laravel 13, PHP 8.5, PostgreSQL, Axios, Blade

## Setup

```bash
git clone https://github.com/abeledatullaomaeann-lab/Task-Manager-System.git
cd Task-Manager-System
composer install
cp .env.example .env
php artisan key:generate
```

Create the PostgreSQL database and user:

```bash
sudo -u postgres psql
CREATE USER mae WITH PASSWORD 'your_password';
CREATE DATABASE task_manager OWNER mae;
\q
```

Set the database details in `.env`, then build and seed it:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

Open http://localhost:8000.

## Demo account

- Email: test@example.com
- Password: password

## API

The API uses the same login session as the pages, so log in first.

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | /api/tasks | List tasks (search, category_id, status, sort, page) |
| POST | /api/tasks | Create a task |
| GET | /api/tasks/{id} | Show a task |
| PUT | /api/tasks/{id} | Update a task |
| PATCH | /api/tasks/{id}/toggle | Mark pending or done |
| DELETE | /api/tasks/{id} | Delete a task |
| GET | /api/categories | List categories with task counts |

## Author

Mae Ann A. Tullao