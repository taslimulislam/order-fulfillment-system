# Order Fulfillment System

**Developer:** Taslimul Islam  
**Date:** 2025-10-20  
**Framework:** Laravel 10+  
**Architecture:** Modular, Event-Driven, Service-Repository Pattern  

---

## 🧩 Overview

This project implements a **multi-vendor Order Fulfillment System** where buyers can place orders containing products from multiple sellers.  
The system is fully **event-driven**, ensuring clean separation between core business logic and side effects such as balance updates, mail notifications, and audit logging.

---

## ⚙️ Installation & Running Steps

### 1. Clone Repository
```bash
git clone https://github.com/taslimulislam/order-fulfillment-system.git
cd order-fulfillment-system
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
Copy `.env.example` and update your local database credentials:
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Setup Database Connection
Before running migrations, open the `.env` file and configure your **database connection** properly:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```
Make sure your database is created and accessible.

### 5. Install and Configure Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

### 6. Run the Queue Worker
```bash
php artisan queue:work
```

### 7. Start the Development Server
```bash
php artisan serve
```
### 🧑‍💻 Login Information
Use the following credentials to log in via API:

- **Buyer Account:** Retrieve a user with role `buyer` from the `users` table  
- **Seller Account:** Retrieve a user with role `seller` from the `users` table  
- **Password:** `123456`

Example (Postman or cURL):
```json
POST /api/v1/login
{
  "email": "get buyer or seller email from database (buyer@example.com)",
  "password": "123456"
}
```

### 8. API Authentication
Log in via `/api/v1/login` to receive a Sanctum token for authorized API calls.

## 🧾 Example API Endpoints

| Endpoint | Method | Description |
|-----------|--------|-------------|
| `/api/v1/login` | POST | Authenticate user and generate Sanctum token |
| `/api/v1/orders` | POST | Create a new order (Buyer only) |
| `/api/v1/orders/report` | GET | Role-based order report (Buyer/Seller) |
| `/api/v1/logout` | POST | Logout and revoke current token |

---

## 🔁 System Flow (Bullet Summary)

1. **User Login** – Authenticates buyer/seller and issues Sanctum token.  
2. **Order Creation** – Buyer places an order with product and quantity details.  
3. **Order Service** – Validates stock, creates order + items transactionally, fires `OrderPlaced` event.  
4. **Event System** – Listeners handle side effects asynchronously (balance updates, email, audit log).  
5. **Invoice Jobs** – Generates invoices daily or on demand for missing ones.  
6. **Role-based Reports** – Returns filtered orders based on user role (buyer/seller).  
7. **Logout** – Invalidates the user’s active token.

---

## 📊 Flow Diagram (Textual Representation)

```
[ Buyer Places Order ]
          |
          v
[ OrderService::createOrder() ]
          |
          v
  [ Validate Stock + Compute Total ]
          |
          v
  [ Create Order + OrderItems (Transaction) ]
          |
          v
  [ Fire OrderPlaced Event ]
          |
          ├──> UpdateSellerBalanceListener
          ├──> SendOrderConfirmationListener
          └──> AuditTrailListener
          
(Asynchronous Jobs -> GenerateInvoiceJob)
```

---

## 🧠 Explanation of Components

### 🔹 Events
**OrderPlaced** – Fired after order creation to notify other subsystems.

### 🔹 Listeners
- **UpdateSellerBalanceListener:** Updates seller balances.  
- **SendOrderConfirmationListener:** Sends (simulated) order confirmation emails.  
- **AuditTrailListener:** Logs detailed JSON entries in `storage/logs/orders.log`.

### 🔹 Observers
- **OrderObserver:** Automatically assigns unique order numbers before saving.

### 🔹 Repositories
- **OrderRepository:** Handles all order CRUD operations.  
- **ProductRepository:** Manages product stock, availability, and retrieval.

### 🔹 Services
- **OrderService:** Contains all order creation logic, wrapped in a DB transaction.

### 🔹 Jobs
- **GenerateInvoiceJob:** Generates invoice files asynchronously under `storage/app/invoices/`.

---

## 🧾 Invoice Generation Commands

Finds **paid but uninvoiced orders** and dispatches `GenerateInvoiceJob` for each.

### Missing Invoice Dispatcher
```bash
php artisan invoices:dispatch-missing
```
Finds **paid orders missing invoices** (even after initial processing) and dispatches invoice generation jobs again.  
Useful for retrying failed or missed invoice generations.

---

## ✅ Key Features
- Laravel Sanctum authentication  
- Role-based authorization (Buyer/Seller)  
- Clean architecture (Service + Repository)  
- Event-driven domain logic  
- Queued listeners for async tasks  
- Daily & missing invoice generation commands  
- Structured audit logging  
- PSR-12 and SOLID-compliant code  

---

## 📘 Documentation Files
- **ARCHITECTURE_NOTES.md** – Design rationale & trade-offs  
- **README.md** – Setup & technical overview  
- **Postman Collection** – Sample API requests  

---

**Developer:** *Taslimul Islam*  
**Reviewed:** *2025-10-20*  
