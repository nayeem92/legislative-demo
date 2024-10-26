# Legislation Process Management System

## Overview
The **Legislation Process Management System** is designed to streamline the legislative process within the Federal Parliament of Canada. It provides a platform for Members of Parliament (MPs) to propose, review, and vote on bills. The system aims to enhance transparency and efficiency in legislative activities.

## Features
- **User Roles**:
  - **Member of Parliament (MP)**: Can propose bills, review existing bills, and cast votes.
  - **Reviewer**: Reviews proposed bills and provides feedback.
  - **Administrator**: Manages users and oversees the legislative process.

- **Bill Management**:
  - Create, edit, and delete bills.
  - Track the status of bills through different legislative stages.

- **Voting System**:
  - Cast votes on bills with options: `For`, `Against`, or `Abstain`.
  - View voting results and individual votes.

- **Amendments**:
  - Propose and review amendments to bills.

## Technology Stack
- **Backend**: PHP with PDO for MySQL connectivity
- **Database**: MySQL
- **Containerization**: Podman and Docker Compose for deployment

## Installation

### Prerequisites
- Ensure you have **Podman** and **Docker Compose** installed on your system.
- MySQL server must be running.

### Steps
1. **Clone the repository**:
    ```bash
    git clone <repository-url>
    cd legislation-process-management-system
    ```

2. **Set up the database**:
   - Create a new MySQL database:
     ```sql
     CREATE DATABASE legislation_db;
     ```
   - Run the provided SQL scripts to set up the initial database structure and populate it with dummy data.

3. **Configure the database connection**:
   - Open the `config.php` file and update the database connection settings:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'legislation_db');
     define('DB_USER', 'your_username');
     define('DB_PASSWORD', 'your_password');
     ```

4. **Run the application using Podman**:
   - Build and start the containers:
     ```bash
     podman-compose up --build
     ```

## Usage
- Access the application via your web browser at `http://localhost/legislative-demo`.
- Log in as different users to test various functionalities based on roles.
- For MPs, you can create bills, propose amendments, and cast votes.
- Administrators can manage users and oversee the voting process.

## Database Structure
### Tables
- **users**: Stores user information and roles.
- **bills**: Contains details of each bill.
- **votes**: Records votes cast by MPs.
- **amendments**: Stores proposed amendments to bills.

### Example SQL Query for Dummy Data
((Add sql file link))
