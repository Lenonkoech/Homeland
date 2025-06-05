# Homeland - Real Estate Management System

A modern and comprehensive real estate management system built with PHP and Node.js. This platform enables real estate agencies to manage properties, handle client requests, and streamline their operations efficiently.

## 🌟 Key Features

### Property Management
- **Property Listings**: Create and manage property listings with detailed information
- **Multiple Property Types**: Support for Buy, Rent, and Lease properties
- **Media Management**: Upload and manage property images
- **Property Categories**: Organize properties by type and location
- **Search & Filter**: Advanced search functionality with multiple filters

### User Management
- **User Authentication**: Secure login and registration system
- **User Profiles**: Customizable user profiles with preferences
- **Role-Based Access**: Separate interfaces for users and administrators
- **Favorites System**: Users can save and track favorite properties

### Request Management
- **Property Requests**: Users can request information about properties
- **Request Tracking**: Monitor request status (Pending, Approved, Rejected)
- **Email Notifications**: Automated email notifications for request updates
- **Admin Dashboard**: Comprehensive request management interface

### Notification System
- **Real-time Updates**: Instant notifications for important events
- **Email Notifications**: Automated email alerts for property updates
- **Request Status Updates**: Notifications for request status changes
- **Unread Count**: Track unread notifications

### Admin Features
- **Dashboard**: Overview of key metrics and recent activities
- **Property Management**: Add, edit, and delete property listings
- **User Management**: Manage user accounts and permissions
- **Request Management**: Handle and respond to property requests
- **Category Management**: Organize property categories

## 🛠 Technical Stack

### Backend
- **PHP 7.4+**: Core application logic
- **Node.js**: Email service and background tasks
- **MySQL**: Database management
- **PDO**: Secure database connections

### Frontend
- **HTML5/CSS3**: Modern, responsive design
- **JavaScript**: Interactive user interface
- **Bootstrap**: Responsive layout framework
- **jQuery**: DOM manipulation and AJAX

### Email Service
- **Node.js**: Background email processing
- **Nodemailer**: Email sending functionality
- **SMTP Integration**: Support for multiple email providers
- **Email Queue**: Asynchronous email processing

### Security Features
- **Password Hashing**: Secure password storage
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: Input sanitization
- **CSRF Protection**: Form token validation
- **Session Management**: Secure user sessions

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Node.js 14 or higher
- XAMPP (recommended for local development)
- Composer (for PHP dependencies)
- npm (for Node.js dependencies)

## 🚀 Installation

1. Clone the repository:
```bash
git clone https://github.com/Lenonkoech/homeland.git
cd homeland
```

2. Set up the database:
   - Create a new database named `homeland` in phpMyAdmin
   - Import the database schema from `database/homeland.sql`

3. Configure environment variables:
   - Create a `.env` file in the project root:
   ```env
   # Database Configuration
   DB_HOST=localhost
   DB_PORT=3307
   DB_USER=root
   DB_PASSWORD=
   DB_NAME=homeland

   # Email Configuration
   SMTP_HOST=smtp.mailtrap.io
   SMTP_PORT=2525
   SMTP_USER=your_smtp_username
   SMTP_PASS=your_smtp_password
   SMTP_FROM=noreply@homeland.com

   # Application Settings
   APP_URL=http://localhost/homeland
   APP_NAME=Homeland
   APP_ENV=local
   APP_DEBUG=true
   ```

4. Install dependencies:
```bash
# PHP dependencies
composer install

# Node.js dependencies
cd services
npm install
```

5. Start the email service:
```bash
cd services
node index.js
```

6. Configure your web server:
   - Point your web server to the project root directory
   - Ensure the `services` directory is accessible to Node.js
   - Make sure the `images` directory is writable

## 📁 Project Structure

```
homeland/
├── admin-panel/          # Admin interface
├── auth/                 # Authentication files
├── config/              # Configuration files
├── css/                 # Stylesheets
├── database/            # Database files
├── features/            # Core features
├── images/              # Uploaded images
├── includes/            # Common includes
├── js/                  # JavaScript files
├── services/            # Node.js services
│   ├── index.js        # Email service
│   └── package.json    # Node.js dependencies
├── user/                # User interface
├── .env                 # Environment variables
└── README.md           # This file
```

## 🔄 Email Queue System

The system includes a robust email queue system that:
- Processes emails asynchronously
- Handles email sending failures gracefully
- Tracks email status and delivery
- Provides error reporting
- Supports multiple email templates

## 🔒 Security Measures

- Environment-based configuration
- Secure password handling
- Input validation and sanitization
- Prepared SQL statements
- Session security
- CSRF protection
- XSS prevention

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.
