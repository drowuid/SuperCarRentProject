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

Make sure your system has the following installed: 
- PHP >= 8.2
- Composer 
- MySQL or MariaDB
- Node.js & npm
- Git.

To set up the SuperCarRent project, run the following commands step-by-step:

```bash
git clone https://github.com/drowuid/SuperCarRentProject.git
cd SuperCarRentProject
composer install
cp .env.example .env
```

Then, open the `.env` file in your code editor and set the database and mail credentials, for example:

```dotenv
DB_DATABASE=locacao
DB_USERNAME=root
DB_PASSWORD=your_password_here

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@supercarrent.com
MAIL_FROM_NAME="SuperCarRent"
```

Then continue in the terminal:

```bash
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve (running on a seperate terminal window)
npm run dev (running on a seperate terminal window)
```

The application will be available at:

http://127.0.0.1:8000

Admin Credentials (default):
Email: admin@supercarrent.com
Password: admin

(You can change these in the `DatabaseSeeder.php` or directly in the database.)

To run tests:

```bash
php artisan test
```

If using Pest:

```bash
./vendor/bin/pest
```



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








