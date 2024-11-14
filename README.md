# Task Management Application

This is a Laravel-based task management application with Vue.js and Inertia.js for the frontend. It includes basic user
registration, login, and task management functionalities.

## Technologies Used

- **Backend:** Laravel
- **Frontend:** Vue.js with Inertia.js
- **Database:** SQLite

## Features

- User Registration and Login
- Task CRUD Operations (Create, Read, Update, Delete)
- Integrated with Vue.js for frontend interactivity
- Inertia.js for single-page application (SPA) experience
- SQLite database for lightweight storage and easy setup

## Installation

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/1MuneebUrRehman/taskmanagementapp.git
   cd taskmanagementapp

2. **Install dependencies**

   ```bash
   composer install
   npm install

3. **Set up environment variables**

   ```bash
   cp .env.example .env

4. **Create the SQLite database file**

   ```bash
   touch database/database.sqlite

5. **Generate application key**

   ```bash
   php artisan key:generate

6. **Run migrations**

   ```bash
   php artisan migrate

7. **Build assets**

   ```bash
   npm run dev

8. **Run the development server**

   ```bash
   php artisan serve
