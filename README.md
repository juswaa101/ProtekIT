# PROTEKIT

This is a guide on how to set up and run the PROTEKIT project.

### Description

PROTEKIT is a web application that provides you custom authentication that you can used to deploy web applications and setup security and policies across your applications with already custom built in authentication, dashboard, access level management that can speed up your development.

## Features

-   User Level Access Management
-   Custom Policies
-   Roles and Permissions
-   Authentication
-   Table Export Excel File
-   User Account Archiving
-   Custom Dashboard

## Setup

1. Clone the repository:

    ```bash
    git clone <repository_url>
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Copy the `.env.example` file to `.env` and update the necessary configuration values.

4. Generate an application key:

    ```bash
    php artisan key:generate
    ```

5. Migrate the database tables:

    ```bash
    php artisan migrate
    ```

6. Seed the database with initial data:
    ```bash
    php artisan db:seed
    ```

## Configuration

### Eloquent Policies

To configure Eloquent policies, follow these steps:

1. Create a new policy class:

    ```bash
    php artisan make:policy <PolicyName>
    ```

2. Define the necessary authorization methods in the policy class.

3. Register the policy in the `AuthServiceProvider` class.

4. Apply the policy to the desired model(s) using the `authorize` method.

## Running the Project

To run the PROTEKIT project, use the following command:

```bash
php artisan serve
```

Enter this url to access:

```bash
http://127.0.0.1:8000/login
```

or

```bash
http://localhost/login
```
