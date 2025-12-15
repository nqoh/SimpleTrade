# Virgosoft Trading Assessment – Laravel + Vue

This project is a technical assessment implementing a simplified **limit-order trading system** with real-time updates, built using **Laravel (API)** and **Vue 3 (Composition API)**.

The goal of the assessment is to demonstrate:

* Financial data integrity
* Concurrency safety (race-condition prevention)
* Atomic balance & asset management
* Real-time event broadcasting

> ⚠️ **Note**: This is **not production-ready trading software**. Certain simplifications were intentionally made to match the assessment scope.

---

## 🛠 Tech Stack

**Backend**

* Laravel (latest stable)
* MySQL 
* Laravel Sanctum (SPA authentication)
* Laravel Broadcasting (Reverb)

**Frontend**

* Vue 3 (Composition API)
* Pinia (state management)
* Tailwind CSS
* Laravel Echo + Pusher 

---

## 📦 Core Features

* User wallet with USD balance
* Asset management with available & locked balances
* Limit Buy / Sell orders
* Full-match-only order matching engine
* Commission handling (1.5%)
* Atomic database transactions with row locking
* Private real-time notifications per user

---

## 🧱 Database Schema Overview

### users

* `id`
* `name`
* `email`
* `password`
* `balance` (DECIMAL) → available USD

### assets

* `user_id`
* `symbol` (BTC, ETH)
* `amount` (available)
* `locked_amount` (reserved for sell orders)

### orders

* `user_id`
* `symbol`
* `side` (buy / sell)
* `price`
* `amount`
* `status` (1=open, 2=filled, 3=cancelled)

### trades (optional)

* `buy_order_id`
* `sell_order_id`
* `symbol`
* `price`
* `amount`
* `usd_volume`
* `fee`

---

## 🔐 Authentication

* Uses **Laravel Sanctum (cookie-based SPA auth)**
* Required for **private broadcasting channels**
* `/broadcasting/auth` is protected by `auth:sanctum`

Private channel format:

```
private-user.{id}
```

---

## ⚡ Real-Time Events

On every successful order match:

* `OrderMatched` event is broadcast
* Delivered to both buyer and seller
* Channel: `private-user.{id}`

Frontend listens via **Laravel Echo + Pusher** and updates:

* Balances
* Assets
* Order status

---

## 🧠 Concurrency & Safety

To prevent double-spending and race conditions:

* All balance & asset updates are wrapped in `DB::transaction`
* Critical rows are locked using `SELECT ... FOR UPDATE`
* Orders are matched atomically
* Only OPEN orders can be matched or cancelled

---

## 🚀 Setup Instructions

### 1️⃣ Clone Repository

```bash
git clone <repository-url>
cd project-folder
```

---

### 2️⃣ Backend Setup (Laravel)

Install dependencies:

```bash
composer install
```

Copy environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure database in `.env`:

```env
DB_DATABASE=SimpleTrade
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

Start backend server:

```bash
php artisan serve
```

Start reverb server:

```bash
php artisan reverb:start
```

---

### 3️⃣ Default Seeded Users

The database seeder creates **2 default users** for testing:

| Email                                   | Password | USD Balance | BTC Balance |
| --------------------------------------- | -------- | ----------- | ----------- |
| [user1@test.com]                        |  123456  | 10000       |      0      |
| [user2@test.com]                        |  123456  |     0       |      10     |

Each user also starts with:

* BTC asset balance (example for testing)
* `locked_amount = 0`

> These users allow immediate Buy/Sell testing without manual setup.

---

### 4️⃣ Frontend Setup (Vue)

```bash
npm install
npm run dev
```

Configure Pusher keys in `frontend/.env`:

```env
VITE_PUSHER_KEY=your_key
VITE_PUSHER_CLUSTER=your_cluster
```

---

## 🧪 Manual Test Flow

1. Login as **User 1**
2. Place a **BUY** order
3. Login as **User 2**
4. Place a **SELL** order with matching price
5. Observe:

   * Orders move to FILLED
   * Balances update atomically
   * Real-time event received

---

## 📌 Matching Rules

* Full match only (no partial fills)
* BUY matches SELL when `sell.price <= buy.price`
* SELL matches BUY when `buy.price >= sell.price`
* Commission: **1.5% of USD volume**

Example:

```
1 BTC @ 95,000 USD
Volume = 950 USD
Fee = 14.25 USD
```

---

## 📎 Notes for Reviewers

* Business logic is intentionally kept simple and readable
* Matching engine implemented in a dedicated service
* Controllers remain thin
* Emphasis placed on correctness and safety over UI polish

---

## ✅ Assessment Scope

✔ Laravel API
✔ Vue 3 + Composition API
✔ Real-time private broadcasting
✔ Atomic balance handling
✔ Clean architecture

---

Thank you for reviewing this assessment 🙏
