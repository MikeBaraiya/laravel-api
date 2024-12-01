
## About the Project

This project provides a RESTful API built with Laravel for managing users and orders. It supports authentication, CRUD operations, and other essential functionalities for managing order confirmations and user access.

## Features:

- Authentication: Secure login and logout using Laravel Sanctum.
- User Management: CRUD operations for managing users.
- Order Management: CRUD operations for orders and order confirmation functionality.
- Middleware Security: Restricts unauthorized access to endpoints.
- Validation: Comprehensive request validation for all endpoints.


## Deployment

### Install Prerequisites:

Make sure the following are installed on your system:
- PHP: Version 8.2 or higher.
- Composer: Dependency manager for PHP.
- Database: MySQL.

### Clone the Repository:
Use git to clone the Laravel project repository to your local machine:
```
git clone https://github.com/MikeBaraiya/laravel-api.git
```
Navigate into the project directory:
```
cd laravel-api
```

### Install Dependencies:
Install PHP dependencies using Composer:
```
composer install
```

### Set Up the Environment File:
Create a .env file by copying the example file:
```
cp .env.example .env
```

Edit the `.env` file to configure your application settings, such as database credentials, app name, and environment. Example for MySQL:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Generate Application Key:
Generate a unique application key:
```
php artisan key:generate
```

### Set Up the Database:
- Create a database in your preferred database management tool (e.g., phpMyAdmin or MySQL CLI).
- Run migrations to create the necessary tables:
```
php artisan migrate
``` 

### If your project includes seeders for initial data, run:
```
php artisan db:seed AdminSeeder
```

### Start the Development Server:
Start the Laravel development server:
```
php artisan serve
```
- This will serve your application at [http://localhost:8000](http://localhost:8000).
## API Endpoints
### Authentication:

#### Login:

```http
  POST /api/login
```
- URI: `http://127.0.0.1:8000/api/login`
- Description: Authenticates the user and returns an API token.
- Request Body: `{"username": "john", "password": "123123"}`

### User Management:

#### Get All Users:

```http
  GET /api/users
```
- URI: `http://127.0.0.1:8000/api/users`
- Description: Retrieves a list of all users.
- Response: Returns an array of user objects.


#### Create a New User:
```http
  POST /api/user
```
- URI: `http://127.0.0.1:8000/api/user`
- Description: Creates a new user.
- Request Body: 
```json
{
    "name": "John Doe",
    "phone": "789456123",
    "username": "john",
    "designation": "User",
    "email": "john@doe.com",
    "password": "123123"
}
```
Response: Returns the created user object.

#### View a Single User:

```http
  GET /api/user/{id}
```
- URI: `http://127.0.0.1:8000/api/user/{id}`
- Description: Retrieves details of a user by ID.
- Response: Returns the user object.


#### Update a User:

```http
  PUT /api/user/{id}
```
- URI: `http://127.0.0.1:8000/api/user/{id}`
- Description: Updates the details of a user by ID.
- Request Body: Partial or full user data.
- Response: Returns the updated user object.


#### Delete a User:
```http
  DELETE /api/user/{id}
```
- URI: `http://127.0.0.1:8000/api/user/{id}`
- Description: Deletes a user by ID.
- Response: Returns a success message.



==============================================
### Order Management:

#### Get All Orders:

```http
  GET /api/orders
```
- URI: `http://127.0.0.1:8000/api/orders`
- Description: Retrieves a list of all orders.
- Response: Returns an array of user objects.


#### Create a New Order:
```http
  POST /api/order
```
- URI: `http://127.0.0.1:8000/api/order`
- Description: Creates a new order.
- Request Body: 
```json
{
    "user_id": "1",
    "order_date": "2024-11-30",
    "order_number": "ORD12345",
    "party_name": "John Doe",
    "gst_no": "GST123456789",
    "party_city": "New York",
    "party_phone": "1234567890",
    "series": "Series A",
    "code_no": "Code123",
    "size": "10 x 5",
    "auto_rent": 100.50,
    "vehicle_rent": 50.25,
    "transport": "Truck",
    "paid_by": "Credit Card",
    "total_amount": 200.75,
    "delivery_from": "Warehouse A",
    "package_no": "PKG001",
    "purchase_no": "PUR001",
    "sell_bill_no": "SB001",
    "bank_name": "Bank ABC",
    "date": "2024-11-30",
    "cash_received_by": "Jane Doe"
}

```
Response: Returns the created order object.

#### View a Single Order:

```http
  GET /api/order/{id}
```
- URI: `http://127.0.0.1:8000/api/order/{id}`
- Description: Retrieves details of a order by ID.
- Response: Returns the order object.


#### Update a Order:

```http
  PUT /api/order/{id}
```
- URI: `http://127.0.0.1:8000/api/order/{id}`
- Description: Updates the details of a order by ID.
- Request Body: Partial or full order data.
- Response: Returns the updated order object.

#### Get All Confirmed Orders:

```http
  PUT /api/confirmed-orders
```
- URI: `http://127.0.0.1:8000/api/confirmed-orders`
- Description: Retrieves a list of confirmed orders.
- Response: Returns an array of confirmed order objects.
- NOTE: Only for admin.

#### Confirm an Order:

```http
  PATCH /api/order/{id}/confirm
```
- URI: `http://127.0.0.1:8000/api/order/{id}/confirm`
- Description: Confirms an order by ID.
- Request Body: 
```json
{
    "confirmed": 1
}
```
- Response: Returns the updated order object with confirmation details.
- NOTE: Only for admin. 


#### Delete a Order:
```http
  DELETE /api/order/{id}
```
- URI: `http://127.0.0.1:8000/api/order/1`
- Description: Deletes a order by ID.
- Response: Returns a success message.
