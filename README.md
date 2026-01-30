# Order and Payment Management API

This is a Laravel-based API designed for managing orders and payments with a focus on clean code, extensibility, and security.

## Features

- **Authentication**: Secure JWT-based authentication.
- **Order Management**: Create, view, update, and delete orders.
- **Payment Management**: Simulate payments via multiple gateways (Stripe, PayPal) using the Strategy Pattern.
- **Extensibility**: Easily add new payment gateways with minimal code changes.
- **Validation**: Strict input validation using Form Requests.
- **Official Repository Pattern**: Clean separation of database logic.

## Setup Instructions

1. **Clone the repository**:

    ```bash
    git clone <repository_url>
    cd tocaan_task
    ```

2. **Install dependencies**:

    ```bash
    composer install
    ```

3. **Environment Setup**:
   Copy `.env.example` to `.env` and configure your database settings.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **JWT Security**:
   Generate the JWT secret key:

    ```bash
    php artisan jwt:secret
    ```

5. **Run Migrations**:

    ```bash
    php artisan migrate
    ```

6. **Serve the application**:
    ```bash
    php artisan serve
    ```

## Payment Gateway Extensibility

The system uses the **Strategy Pattern** to handle different payment gateways.

### How it works:

- **`PaymentGatewayInterface`**: Defines the contract for all gateways.
- **Implementation Classes**: Each gateway (e.g., `StripeGateway`, `PayPalGateway`) implements the `process()` method.
- **`PaymentService` Resolver**: The `resolveGateway()` method in `PaymentService` dynamically instantiates the correct gateway based on the user's request.

### Adding a New Gateway:

To add a new payment gateway (e.g., Apple Pay):

1. Create a new class `app/Services/V1/Payment/Gateways/ApplePayGateway.php` that implements `PaymentGatewayInterface`.
2. Implement the `process()` method with your gateway-specific logic.
3. Register the new gateway in `app/Services/V1/Payment/PaymentService.php` within the `resolveGateway()` method.
4. Add any necessary API keys to `config/services.php` and `.env`.

## API Documentation

A Postman collection is included in the root directory: `Tocaan_API_Collection.json`.

## Testing

Run the test suite using:

```bash
php artisan test
```

**Note:** Tests use SQLite in-memory by default (see `phpunit.xml`). Ensure the PHP SQLite extension is installed (`php-sqlite3` on Ubuntu/Debian). For unit and feature tests that use the database, run migrations in the test environment or use a local SQLite database.
