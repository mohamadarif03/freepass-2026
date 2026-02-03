# BCC Canteen

A digital platform designed to transform how campus canteens operate. This system aims to provide a seamless food ordering and payment experience for users, efficient menu and order management for canteen owners, and centralized supervision for administrators.

## 🚀 Getting Started

Follow these steps to set up and run the project in your local environment.

### Prerequisites

- **PHP**: 8.2 or higher
- **Composer**
- **MySQL** (or any other database supported by Laravel)

### Installation

1.  **Clone the repository**

    ```bash
    git clone https://github.com/mohamadarif03/freepass-2026.git
    cd freepass-2026/e-canteen
    ```

2.  **Install dependencies**

    ```bash
    composer install
    ```

3.  **Environment Setup**
    Copy the example environment file and configure it.

    ```bash
    cp .env.example .env
    ```

4.  **Configure Database & Tripay**
    Open `.env` file and set up your database and Tripay credentials.

    **Database:**

    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

    **Tripay (Payment Gateway):**

    ```ini
    TRIPAY_API_URL=https://tripay.co.id/api-sandbox
    TRIPAY_API_KEY=your_tripay_api_key
    TRIPAY_PRIVATE_KEY=your_tripay_private_key
    TRIPAY_MERCHANT_CODE=your_merchant_code
    ```

5.  **Generate App Key**

    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations & Seeders**
    This will create the tables and a default Admin account.

    ```bash
    php artisan migrate --seed
    ```

7.  **Link Storage**

    ```bash
    php artisan storage:link
    ```

8.  **Run the Server**
    ```bash
    php artisan serve
    ```
    The API will be accessible at `http://localhost:8000/api`.

---

## 📚 API Documentation

### 🟢 Public Routes

#### **Authentication**

| Method | Endpoint    | Description         | Body Parameters                                                      |
| :----- | :---------- | :------------------ | :------------------------------------------------------------------- |
| `POST` | `/register` | Register a new User | `name`, `email`, `password`, `password_confirmation`, `phone_number` |
| `POST` | `/login`    | Login               | `email`, `password`                                                  |

#### **Callbacks**

| Method | Endpoint    | Description                    |
| :----- | :---------- | :----------------------------- |
| `POST` | `/callback` | Handle Tripay payment callback |

---

### 🔒 Protected Routes (Bearer Token Required)

All routes below require `Authorization: Bearer <token>` header.

#### **General Auth**

| Method | Endpoint   | Description                |
| :----- | :--------- | :------------------------- |
| `POST` | `/logout`  | Logout user (revoke token) |
| `GET`  | `/profile` | Get user profile           |
| `PUT`  | `/profile` | Update user profile        |

#### **👤 Role: Admin**

| Method   | Endpoint              | Description               | Body Parameters                                   |
| :------- | :-------------------- | :------------------------ | :------------------------------------------------ |
| `GET`    | `/canteen-owner`      | List Canteen Owners       | -                                                 |
| `POST`   | `/canteen-owner`      | Create Canteen Owner      | `name`, `email`, `password`, `phone_number`       |
| `PUT`    | `/canteen-owner/{id}` | Update Canteen Owner      | `name`, `email`, `password` (opt), `phone_number` |
| `DELETE` | `/canteen-owner/{id}` | Remove User/Canteen Owner | -                                                 |

#### **🏪 Role: Canteen Owner**

| Method   | Endpoint                      | Description           | Body (Create/Update)                                    |
| :------- | :---------------------------- | :-------------------- | :------------------------------------------------------ |
| `GET`    | `/menu`                       | List own menus        | -                                                       |
| `POST`   | `/menu`                       | Create Menu           | `name`, `description`, `price`, `stock`, `image` (file) |
| `PUT`    | `/menu/{id}`                  | Update Menu           | `name`, `description`, `price`, `stock`, `image` (file) |
| `DELETE` | `/menu/{id}`                  | Delete Menu           | -                                                       |
| `GET`    | `/orders`                     | View incoming orders  | -                                                       |
| `PATCH`  | `/orders-status-payment/{id}` | Update Payment Status | `status_payment` (paid/unpaid)                          |
| `PATCH`  | `/orders-status/{id}`         | Update Order Status   | `status` (cooking/completed/etc)                        |

#### **🍴 Role: User**

| Method   | Endpoint            | Description              | Payloads/Notes                                                                      |
| :------- | :------------------ | :----------------------- | :---------------------------------------------------------------------------------- |
| `GET`    | `/canteen`          | List all Canteens        | -                                                                                   |
| `GET`    | `/menu/{canteenId}` | List Menu by Canteen     | -                                                                                   |
| `GET`    | `/payment-channel`  | List Tripay Channels     | -                                                                                   |
| `POST`   | `/transaction`      | Create Order/Transaction | `payment_method`, `method_code` (if digital), `menus` array (`menu_id`, `quantity`) |
| `GET`    | `/orders`           | History of Orders        | -                                                                                   |
| `POST`   | `/reviews`          | Add Review               | `canteen_id`, `rating` (1-5), `comment`                                             |
| `PUT`    | `/reviews/{id}`     | Update Review            | `rating`, `comment`                                                                 |
| `DELETE` | `/reviews/{id}`     | Delete Review            | -                                                                                   |

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Payment Gateway**: Tripay
