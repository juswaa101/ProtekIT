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
-   Weekly Scheduled Deletion of Archive User Account.
-   System Notifications
-   Event Planner

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
# Configuration

## Email

How to setup email sending

Configure the mail settings below in .env file accordingly.

```bash
    MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
```

## Notification

How to setup notification

Run this command to create a table for notifications:

```bash
php artisan notifications:table
```

## Queue

How to setup queues

Set QUEUE_CONNECTION to database in env file

Run this command to create a table for queues:

```bash
php artisan queue:table
```

After run this command:
```bash
php artisan queue:listen
```

### Task Scheduler

How to run the scheduler to execute weekly deletion of archived users

```bash
php artisan schedule:work
```

## Running the Project

To run the PROTEKIT project, use the following command:

```bash
php artisan serve
```

Enter this url to access:

```bash
http://127.0.0.1:8000/
```

or

```bash
http://localhost:8000/
```
