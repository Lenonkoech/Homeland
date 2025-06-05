# Homeland - Real Estate Management System

A comprehensive real estate management system built with PHP and Node.js.

## Features

- Property listings (Buy, Rent, Lease)
- User authentication
- Admin panel
- Property requests
- Email notifications
- Favorites system
- Search functionality
- Responsive design

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Node.js 14 or higher
- XAMPP (recommended for local development)
- Composer (for PHP dependencies)
- npm (for Node.js dependencies)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/homeland.git
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

   # Email Configuration (Mailtrap)
   SMTP_HOST=smtp.mailtrap.io
   SMTP_PORT=2525
   SMTP_USER=your_mailtrap_username
   SMTP_PASS=your_mailtrap_password
   SMTP_FROM=noreply@homeland.com

   # Application Settings
   APP_URL=http://localhost/homeland
   APP_NAME=Homeland
   APP_ENV=local
   APP_DEBUG=true

   # Session & Security
   SESSION_SECRET=your_session_secret
   JWT_SECRET=your_jwt_secret

   # File Upload Settings
   UPLOAD_MAX_SIZE=5242880
   ALLOWED_FILE_TYPES=jpg,jpeg,png,pdf,doc,docx
   ```

4. Install PHP dependencies:
```bash
composer install
```

5. Install Node.js dependencies:
```bash
cd services
npm install
```

6. Start the email service:
```bash
cd services
node index.js
```

7. Configure your web server:
   - Point your web server to the project root directory
   - Ensure the `services` directory is accessible to Node.js
   - Make sure the `images` directory is writable

8. Access the application:
   - Frontend: `http://localhost/homeland`
   - Admin Panel: `http://localhost/homeland/admin-panel`
   - Default admin credentials:
     - Username: admin
     - Password: admin123

## Directory Structure

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

## Recent Changes

### Environment Configuration
- Added centralized `.env` file for all configuration
- Updated database connection to use environment variables
- Configured email service to use environment variables
- Added fallback values for all settings

### Email Service
- Implemented Node.js email service
- Added support for Mailtrap SMTP
- Configured email queue processing
- Added error handling and logging

### Security Updates
- Improved session handling
- Added environment-based configuration
- Removed hardcoded credentials
- Added secure password handling

### Code Improvements
- Updated file paths to use environment variables
- Improved error handling
- Added debugging information
- Enhanced code organization

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
