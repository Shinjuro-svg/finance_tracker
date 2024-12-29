# Finance Tracker

A simple finance tracker application that allows users to manage their income, expenses, and budget. The app uses PHP and MySQL to store and retrieve financial records. Users can add, edit, and delete their financial entries securely, based on their unique email accounts.

## Features

- **User Authentication**: Users can log in to the app using their email.
- **Manage Income**: Users can track and manage their income sources.
- **Manage Expenses**: Users can add, update, and delete their expenses.
- **Manage Budget**: Users can set and edit their monthly budget.
- **CRUD Operations**: Users can create, read, update, and delete their records.
- **Security**: Only authenticated users can access their personal data, with data filtered by their email.

## Prerequisites

- PHP (version 7.4 or higher)
- MySQL or MariaDB
- A web server (like Apache or Nginx)

## Installation

1. **Clone the repository**:
    ```bash
    git clone https://github.com/Shinjuro-svg/finance_tracker.git
    cd finance-tracker
    ```

2. **Set up your MySQL Database**:
    - Create a new MySQL database:
      ```sql
      CREATE DATABASE finance_tracker;
      ```

    - Import the SQL schema to set up the necessary tables (e.g., `income`, `expenses`, `budget`). Below is an example of how to structure the tables:

    ```sql
    CREATE TABLE `users` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(100) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL
    );

    CREATE TABLE `income` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(100) NOT NULL,
      `amount` DECIMAL(10, 2) NOT NULL,
      `source` VARCHAR(255) NOT NULL,
      `date` DATE NOT NULL,
      FOREIGN KEY (`email`) REFERENCES `users`(`email`)
    );

    CREATE TABLE `expenses` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(100) NOT NULL,
      `amount` DECIMAL(10, 2) NOT NULL,
      `category` VARCHAR(255) NOT NULL,
      `date` DATE NOT NULL,
      FOREIGN KEY (`email`) REFERENCES `users`(`email`)
    );

    CREATE TABLE `budget` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(100) NOT NULL,
      `monthly_budget` DECIMAL(10, 2) NOT NULL,
      `date` DATE NOT NULL,
      FOREIGN KEY (`email`) REFERENCES `users`(`email`)
    );
    ```

3. **Configure Database Connection**:
    - Update the `db_config.php` (or directly within your PHP files) with your database credentials:
      ```php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "finance_tracker";
      ```

4. **Set Up Your Web Server**:
    - Make sure your PHP server (e.g., Apache) is running and correctly serving the `index.php`, `manage.php`, and other PHP files in the project directory.

5. **Start the Application**:
    - Open the project in a web browser by navigating to `http://localhost/finance-tracker/index.php`.

## Usage

1. **Login**:
    - Users must log in using their email. If they don't have an account, they will need to create one.
  
2. **Manage Data**:
    - Once logged in, users can manage their income, expenses, and budget.
    - They can add new records, edit existing ones, and delete records.

3. **Edit Records**:
    - Users can click on "Edit" next to any record to modify the information (income, expense, or budget).

4. **Delete Records**:
    - Users can delete records directly from the manage page by clicking the "Delete" button next to the record.

## Files Structure

- `index.php`: The login page where users can log in with their email.
- `manage.php`: The main page where users can manage their income, expenses, and budget.
- `edit.php`: The page where users can edit a specific income, expense, or budget record.
- `db_config.php`: (Optional) Configuration for the database connection.
- `README.md`: The documentation for the project.

## Technologies Used

- **PHP**: Server-side scripting language to handle logic and database interaction.
- **MySQL**: Relational database management system for storing user and financial data.
- **HTML/CSS**: For building the user interface and styling the web pages.

## Contributing

If you would like to contribute to this project, please fork the repository, make your changes, and create a pull request. Be sure to include relevant tests and documentation for your changes.

## License

This project is open-source and available under the [MIT License](LICENSE).

---

Feel free to customize this `README.md` according to your project specifics. It gives users and developers an easy-to-follow guide to understanding and running your Finance Tracker application.
