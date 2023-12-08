# Flexisource IT Code Challenge

## Setup
If you are using Docker, you can use the Dockerfile, docker-compose.yml, and the nginx.conf included.

- For other environment make sure to set the database name to 
    ```
    customer_db
    ```
- Run the database migration to create customers table
    ```
    php artisan doctrine:migrations:migrate
    ```

## Import Customer Data Console Command
The artisan command used to import customer.
There is an optional parameter for the command `count`, if not set the default value is `100`

- Artisan command
    ```
    php artisan customers:import --count=100
    ```

## Customer API Endpoints
List of API endpoints available:
 - `/api/customers`
 - `/api/customers/{customerId}`

## Unit and Feature Tests
The Controller and Service classes are covered in the tests.