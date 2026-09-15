# CU Giftshop

An online giftshop and reservation system for Capitol University. Students can browse the catalog, reserve items, and manage their reservations staff can manage the inventory, reservations, and view sales reports.

## Features

- **Customer side** — public shop + product detail pages, account area, shopping cart, reservation creation, and my-reservations tracking.
- **Staff side** — dashboard, inventory management (add/update/delete products), reservation management (view and update status), and reports with monthly sales charts.
- **Auth** — registration (student or staff) and login routed by role (`student` → user area, `staff`/`admin` → staff panel).

## Tech Stack

- PHP (mysqli) — controllers in `backend/`, views at the root (`index*.php`), `user/`, and `staff/`
- MySQL/MariaDB — database `db_CUGiftshop`
- Bootstrap 5.3 + Bootstrap Icons, Chart.js, Google Fonts (Poppins / Playfair Display)

## Database

Tables: `users`, `categories`, `products`, `reservations`, `reservation_items`, `inventory_logs`.

Create it with `backend/db-create.php` (or run the scripts in `backend/tables/`), then seed demo data. DB connection is configured in `backend/connection.php` (default: host `127.0.0.1`, user `root`, empty password).

## Demo accounts

| Role  | Email               | Password     |
|-------|---------------------|--------------|
| Staff | `staff@cu.edu.ph`   | `password123`|
| Student | `student@cu.edu.ph` | `password123`|

## Running locally

Point an Apache/PHP server (e.g. XAMPP) at the project root, or use PHP's built-in server:

```sh
php -S 127.0.0.1:8000
```

Then open `http://127.0.0.1:8000`.