# Sports Manager API

An API built with Laravel 10 to manage sports data, including Players, Teams, and Games, with integration to the public BallDontLie API. The application implements authentication using Laravel Sanctum and follows best development practices, such as the repository pattern.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Pre-created Users](#pre-created-users)
- [Tests](#tests)
- [Features & Endpoints](#features--endpoints)
- [Authentication](#authentication)
- [Access Profiles & Permissions](#access-profiles--permissions)
- [Documentation](#documentation)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)

## Installation

1. **Clone the repository:**

   ```bash
   git clone git@github.com:leosanlima/sports-manager-api-laravel.git
   cd sports-manager-api-laravel
   ```

2. **Build and start Docker containers:**

   ```bash
   docker-compose up -d
   ```

   This step will also create default users with specific roles (see [Pre-created Users](#pre-created-users)).

4. **Access the API:**

   The API will be available at `http://localhost:8008`.

## Usage

### Synchronize with BallDontLie

To fetch and synchronize the game data from the BallDontLie public API, run the following command:

```bash
docker exec -it sports-manager-app php artisan sync:sports-data
```

This command will retrieve and update the games data in the database.

## Pre-created Users

During the installation, the following users are automatically created with predefined roles:

1. **Admin User**:
   - **Email**: `admin@admin.com`
   - **Password**: `admin.1234`
   - **Role**: Administrator

2. **Regular User**:
   - **Email**: `user@user.com`
   - **Password**: `user.1234`
   - **Role**: User

These users can be used to log in and test different access levels in the application.

## Tests

To run the automated tests, execute the following command:

```bash
docker exec -it sports-manager-api-app-1 php artisan test
```

This command will execute all the test cases defined in the `tests` directory, ensuring that key functionalities such as authentication, CRUD operations, and other features work correctly.

## Features & Endpoints

- **Players CRUD**:
  - Create, Read, Update, Delete Players
  - Search by name
- **Games Synchronization**:
  - Fetch and sync game data from the public BallDontLie API

## Authentication

This project uses **Laravel Sanctum** for API authentication. 

- **Login Endpoint**: `/api/login` to obtain a `X-Authorization` token.
- **Logout Endpoint**: `/api/logout` to invalidate the token and log out.

## Access Profiles & Permissions

The project implements two main access profiles:

- **Admin**: Can create, read, update, and delete all resources.
- **User**: Can create, read, and update resources, but not delete them.


## Documentation

- **Postman Collection**: [Download Postman Collection](app/Docs/Sport%20Manager%20API.postman_collection.json)

