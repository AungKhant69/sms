# School Management System

Welcome to the School Management System project! This system is built using the Laravel PHP framework and JavaScript. It is designed to help educational institutions manage various aspects of their day-to-day operations efficiently.

## Features

- **User Management:** Create and manage different user roles such as administrators, teachers, students, and parents.

- **Student Information:** Keep track of student details, academic performance, attendance, and more.

- **Teacher Information:** Manage teacher profiles, subject assignments, and class schedules.

- **Attendance Tracking:** Record and monitor student and teacher attendance.

- **Grade Management:** Easily input and manage student grades for different subjects.

- **Class Scheduling:** Create and view class schedules for teachers and students.

- **Communication:** Facilitate communication between teachers, students, and parents through the messaging system.

## Requirements

To run the School Management System, ensure that your server meets the following requirements:

- PHP >= 7.3
- Composer
- Node.js and NPM
- MySQL or any other compatible database
- Laravel requirements (check Laravel documentation for specifics)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/AungKhant69/sms.git

2. **Change into the project directory:**

   ```bash
   cd sms

3. **Install PHP dependencies:**

   ```bash
   composer install

4. **Install JavaScript dependencies:**

   ```bash
   npm install && npm run dev

5. Copy the .env.example file to .env and configure your database settings.

6. **Generate an application key:**

   ```bash
   php artisan key:generate

6. **Run migrations and seed the database:**

   ```bash
   php artisan migrate --seed

## Configuration

The Configuration of the School Management System can be done in the `.env` file. Update the necessary settings, including database connection details and
mail configurations.

## Usage

1. **Start the development server:**

   ```bash
   php artisan serve

2. Access the application in your web browser at http://localhost:8000.
3. Log in using the default administrator credentials:

- Username: aungkhant@gmail.com
- Password: password

Feel free to explore the features and customize the system according to your institution's needs.
