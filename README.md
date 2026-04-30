# Bantay Barangay System

A web-based system for reporting and monitoring incidents in the barangay.

## Features

* Resident registration and login
* Incident reporting with description and image
* Admin dashboard for monitoring reports
* Status tracking of incidents

## Tech Stack

* Laravel (PHP Framework)
* MySQL / SQLite
* Blade (Frontend)
* XAMPP (Local Server)

## Installation

1. Clone the repository:
   git clone https://github.com/diaMae/bantay-barangay-system.git

2. Go to project folder:
   cd bantay-barangay-system

3. Install dependencies:
   composer install

4. Copy environment file:
   cp .env.example .env

5. Generate key:
   php artisan key:generate

6. Run migrations:
   php artisan migrate

7. Start server:
   php artisan serve

## Usage

Open your browser and go to:
http://127.0.0.1:8000

---

Developed for academic/project purposes.
