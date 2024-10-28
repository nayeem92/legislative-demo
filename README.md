# Legislative System

The **Legislative System** is a web-based application designed to streamline the legislative process. The system allows different types of users — Members of Parliament (MPs), Administrators, and Reviewers — to interact with bills, including creating, editing, voting, reviewing, and managing legislative data. The system includes robust features like data export and status tracking for better governance.

## Project Description

This project aims to provide an intuitive interface for managing legislative processes, with features that support the needs of different roles:

- **Members of Parliament (MPs)** can:
  - Create new bills.
  - Edit and update bills.
  - Vote on bills that are under the voting stage.
  - Download bill data in XML or JSON format.

- **Administrators** can:
  - Manage all the bills created in the system.
  - View and delete bills.
  - See detailed voting results for each bill.
  - Export bill data to XML or JSON format.

- **Reviewers** can:
  - Review and amend bills.
  - Track the status of bills.

## Key Features

- **Role-Based Dashboards**:
  - Tailored dashboards for MPs, Admins, and Reviewers.
  - Clear navigation and action buttons for different tasks.
- **Bill Management**:
  - CRUD (Create, Read, Update, Delete) operations for bills.
  - Role-specific functionalities.
- **Voting**:
  - MPs can vote on bills.
  - Voting results are displayed in a user-friendly format.
- **Data Export**:
  - Bills can be exported in XML or JSON format for archival purposes.
- **Amendment Review**:
  - A streamlined process for reviewers to manage and track amendments.

## Technologies Used

- **PHP**: Server-side scripting for handling backend logic.
- **MySQL**: Relational database to store legislative data and user information.
- **Tailwind CSS**: A modern utility-first CSS framework for styling.
- **JavaScript**: Used for client-side interactions.
- **Composer**: Dependency management for PHP.

## Prerequisites

- **PHP** 8.2 or above.
- **MySQL Database**.
- **Apache Web Server** (XAMPP or similar).
- **Composer** for dependency management.

## Installation

### Step 1: Clone the Repository
 - Clone this repository to your local machine using the following command:

 - ```bash
   git clone https://github.com/yourusername/legislative-system.git

### Step 2: Navigate to Project Directory
 - cd legislative-system

### Step 3: Install Dependencies
 - composer install

### Step 4: Setup Database
- Create a new MySQL database named legislative_system.
- Import the legislative_system.sql file provided in the repository to your MySQL database.

User Credentials
Below are the default usernames and passwords for testing the system:

## User Credentials

Below are the default usernames and passwords for testing the system:

### Members of Parliament (MPs)

- **MP1**:
  - Username: `mp1`
  - Password: `mp1_password`
- **MP2**:
  - Username: `mp2`
  - Password: `mp2_password`
- **MP3**:
  - Username: `mp3`
  - Password: `mp3_password`
- **MP4**:
  - Username: `mp4`
  - Password: `mp4_password`

### Administrator

- **Admin**:
  - Username: `admin`
  - Password: `admin_password`

### Reviewer

- **Reviewer1**:
  - Username: `reviewer1`
  - Password: `reviewer_password`


