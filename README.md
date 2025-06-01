# Homeland - Real Estate Management System

A comprehensive real estate management system built with PHP, MySQL, and Bootstrap. This system allows users to browse properties, make requests, and manage real estate listings.

## Features

### For Users
- User registration and login
- Browse available properties
- Search properties by location, price, and type
- View detailed property information
- Make property requests
- View request history
- Update profile information

### For Admins
- Admin dashboard
- Manage properties (Add, Edit, Delete)
- View and manage property requests
- User management
- Property type management
- Location management

## Prerequisites

- XAMPP (PHP 7.4 or higher)
- MySQL
- Web browser
- Git

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Lenonkoech/Homeland.git
   ```

2. **Set up XAMPP**
   - Install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start Apache and MySQL services from XAMPP Control Panel

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `homeland`
   - Import the database file from the project's `database` folder

4. **Project Setup**
   - Copy the cloned project to `C:\xampp\htdocs\` (Windows) or `/opt/lampp/htdocs/` (Linux)
   - Configure database connection in `config/config.php`:
     ```php
     $host = 'localhost';
     $dbname = 'homeland';
     $username = 'root';
     $password = '';
     ```

5. **Access the Project**
   - Open your web browser
   - Navigate to `http://localhost/homeland`

## Default Credentials

### Admin Access
- Email: admin@example.com
- Password: 1234

### User Registration
- Users can register with their email and password
- After registration, users can log in to access their dashboard

## Project Structure

```
homeland/
├── admin-panel/          # Admin dashboard files
├── assets/              # CSS, JS, and image files
├── config/              # Configuration files
├── database/            # Database files
├── includes/            # PHP includes
├── layout/              # Layout templates
└── user-panel/          # User dashboard files
```

## Features in Detail

### Property Management
- Add new properties with details (title, description, price, location, type)
- Upload multiple property images
- Edit and delete existing properties
- View property statistics

### Request Management
- Users can make requests for properties
- Admins can view and manage requests
- Request status tracking

### User Management
- User registration and authentication
- Profile management
- Password reset functionality

### Search and Filter
- Search properties by location
- Filter by property type
- Price range filtering
- Advanced search options

## Security Features

- Password hashing
- SQL injection prevention
- XSS protection
- Input validation
- Session management

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details
