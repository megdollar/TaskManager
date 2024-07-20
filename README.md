# TaskMinder

This PHP App for managing tasks is structured into different classes including User, Task, Category, Notification, and Occurence.

## Classes

- **User**: Manages creating user accounts, logging in to account, logging out of account, and profile updates.
- **Task**: Handles task creation, updates, deletion, completion, and viewing tasks.
- **Category**: Manages adding, updating, deleting, and viewing categories.
- **Notification**: Schedules and sends notifications for tasks.
- **Occurence**: Sets, updates, removes, and views occurence patterns for tasks.

## Table of Contents

- [Project Setup](#project-setup)
  - [Running in XAMPP](#running-in-xampp)
  - [Running in Docker- NOT FULLY FUNCTIONING](#running-in-docker)
- [Usage](#usage)
- [Unit Tests](#unit-tests)
- [Troubleshooting](#troubleshooting)


## Project Setup

### Running in XAMPP

#### Prerequisites

- XAMPP installed on your machine
- PHP and MySQL enabled in XAMPP

#### Steps to Run the Project in XAMPP

1. **Clone the repository to the XAMPP htdocs directory**:

    ```bash
    cd /Applications/XAMPP/xamppfiles/htdocs
    git clone https://github.com/megdollar/TaskMinder.git
    cd TaskMinder
    ```

2. **Ensure correct ownership and permissions**:

    ```bash
    sudo chown -R $(yournamehere):staff /Applications/XAMPP/xamppfiles/htdocs/TaskMinder
    sudo chmod -R 775 /Applications/XAMPP/xamppfiles/htdocs/TaskMinder
    ```

3. **Create a database for the project**:
   - Open the XAMPP control panel and start the MySQL module.
   - Access phpMyAdmin by navigating to `http://localhost/phpmyadmin`.
   - Create a new database named `taskminder`.

4. **Import the database schema**:
   - In phpMyAdmin, select the `taskminder` database.
   - Click on the "Import" tab and upload the `create_tables.sql` file from the project directory.

5. **Update the database configuration**:
   - Open the `Database.php` file located in the `php` directory.
   - Update the database credentials as needed:

    ```php
    private $host = "localhost";
    private $db_name = "taskminder";
    private $username = "root";  
    private $password = "";   
    ```

6. **Start the Apache server**:
   - Open the XAMPP control panel and start the Apache module.

7. **Access the application**:
   - Open your browser and navigate to `http://localhost/TaskMinder/views/index.php`.

### Running in Docker -- Not fully functional (issue with CSS/ and PHP/ folders)

#### Prerequisites

- Docker installed on your machine

#### Steps to Run the Project in Docker

1. **Navigate to your project directory**:

    ```bash
    cd /Applications/XAMPP/xamppfiles/htdocs/TaskMinder
    ```

2. **Build the Docker image**:

    ```bash
    docker build -t taskminder-app .
    ```

3. **Run the Docker container**:

    ```bash
    docker run -d -p 8001:80 --name taskminder-container taskminder-app
    ```

4. **Verify the container is running**:

    ```bash
    docker ps
    ```

5. **Check the logs (if needed)**:

    ```bash
    docker logs taskminder-container
    ```

6. **Access the application**:
   - Open your browser and navigate to `http://localhost:8001`.
   - Sorry, I spent 6 hours trying to get this to work, the CSS folder and PHP folders are showing as 404 so you cannot really use the app this way


## Usage

### User Account

- **Create an Account**: Navigate to `http://localhost/TaskMinder/views/createAccount.php` to create an account.
- **Login**: Navigate to `http://localhost/TaskMinder/views/index.php` and enter your email and password.
- **Dashboard**: After login, you will be redirected to the dashboard where you can view your tasks.

### Task Management 

- **View/Add/Edit/Delete Tasks**: Navigate to http://localhost/taskminder/views/editTask.php for task management


## Unit Tests

Unit tests are provided for the `User` class in the `UserTests.php` file. The tests include:

- `testCreateAccount`
- `testLoginCorrectCredentials`
- `testLoginIncorrectCredentials`
- `testLogout`
- `testUpdateProfile`

To run the tests, navigate to the `tests` directory and execute the tests by navigating to http://localhost/taskminder/tests/UserTests.php or by using the command line.

## Troubleshooting

### Common Issues

1. **CSS and PHP Files Not Loading in Docker**:
   - Ensure the Apache configuration within the Docker container is correctly set to serve the correct directories.
   - Check the permissions of the files and directories.
   - Unfortunately I was unable to get this to work on my end.

2. **Database Connection Issues**:
   - Verify the database credentials in the `Database.php` file.
   - Ensure the MySQL module is running in XAMPP.

### Apache Configuration

If you have issues with Apache not serving files correctly, check if your `000-default.conf` file is correct. Here is an example:

```plaintext
<VirtualHost *:80>
    DocumentRoot /Applications/XAMPP/xamppfiles/htdocs/TaskMinder/views

    <Directory "/Applications/XAMPP/xamppfiles/htdocs/TaskMinder/css">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    <Directory "/Applications/XAMPP/xamppfiles/htdocs/TaskMinder/views">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    <Directory "/Applications/XAMPP/xamppfiles/htdocs/TaskMinder/php">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>


