<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <a href="https://laravel.com/docs"><img src="https://img.shields.io/badge/Laravel-12.x-red" alt="Laravel Version"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
</p>

---

# 🚗 SuperCarRent

SuperCarRent (a fictitious company) is a web-based car rental management system built with Laravel. It allows users to browse, reserve, and manage car rentals, while administrators can view, manage reservations, and interact with users through a real-time support chat interface.


---

## 🧰 Technologies Used

- Backend: Laravel 12
- Frontend: Blade templates, Bootstrap 5, Tailwind CSS
- Database: MySQL
- Authentication: Laravel Breeze
- Testing: PHPUnit, Pest 
- Payment Integration: PayPal API, Fake ATM reference generator
- Email: Laravel Mail for confirmation and password reset
- Real-Time Chat: Admin ↔ User messaging system with polling

---

## ⚙️ How to Run the Project

Prerequisites:
- PHP >= 8.2
- Composer
- MySQL 
- Node.js & npm

Step-by-Step Setup:
1. Clone the repository:
   git clone https://github.com/drowuid/SuperCarRentProject.git
   cd supercarrent

2. Install PHP dependencies:
   composer install

3. Set up your .env file:
   cp .env.example .env
   Edit .env with your database and mail credentials.

4. Generate application key:
   php artisan key:generate

5. Run migrations and seed data:
   php artisan migrate --seed

6. Serve the application:
   php artisan serve

   The app should now be running at:  
   http://127.0.0.1:8000

Admin Credentials (default):
Email: admin@supercarrent.com
Password: admin

You can change these in the DatabaseSeeder.php or directly in the database.


---

Testing:
(Optional) Run tests with:
php artisan test

If using Pest:
./vendor/bin/pest

---

Project Features:
- Secure user registration & login
- Admin dashboard to manage reservations and chat
- User dashboard for reservations
- Messaging system (Admin ↔ User)
- Password reset and email verification
- PayPal integration for rentals
- Fake ATM reference generator
- Invoice generation in PDF

---

To-Do (Future Enhancements):
- Live WebSockets for chat
- Role-based permissions
- SMS notifications
- Calendar-based booking view


---

-Author-

Developed by: Pedro Rodrigues

Feel free to reach out with feedback or contributions.








