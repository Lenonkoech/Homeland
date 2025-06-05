const mysql = require('mysql2/promise');
const nodemailer = require('nodemailer');
const path = require('path');
require('dotenv').config({ path: path.join(__dirname, '..', '.env') });

// Database configuration
const dbConfig = {
    host: process.env.DB_HOST || 'localhost',
    port: process.env.DB_PORT || 3307,  // XAMPP MySQL port
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',  // No password for XAMPP default
    database: process.env.DB_NAME || 'homeland',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
};

// Email configuration
const emailConfig = {
    host: process.env.SMTP_HOST || 'smtp.mailtrap.io',
    port: process.env.SMTP_PORT || 2525,
    auth: {
        user: process.env.SMTP_USER,
        pass: process.env.SMTP_PASS
    }
};

// Create database pool
const pool = mysql.createPool(dbConfig);

// Create email transporter
const transporter = nodemailer.createTransport(emailConfig);

// Verify SMTP connection configuration
transporter.verify(function (error, success) {
    if (error) {
        throw new Error('SMTP connection failed: ' + error.message);
    }
});

// Process email queue
async function processEmailQueue() {
    let connection;
    try {
        // Connect to database
        connection = await mysql.createConnection(dbConfig);

        // Get pending emails
        const [emails] = await connection.execute(
            'SELECT * FROM email_queue WHERE status = "pending" ORDER BY created_at ASC'
        );

        if (emails.length === 0) {
            return;
        }

        // Process each email
        for (const email of emails) {
            try {
                // Send email
                await transporter.sendMail({
                    from: process.env.SMTP_FROM || 'noreply@homeland.com',
                    to: email.to_email,
                    subject: email.subject,
                    text: email.message,
                    html: email.message
                });

                // Update email status and timestamp
                await connection.execute(
                    'UPDATE email_queue SET status = "sent", updated_at = NOW() WHERE id = ?',
                    [email.id]
                );
            } catch (error) {
                // Update email status with error
                await connection.execute(
                    'UPDATE email_queue SET status = "failed", error = ?, updated_at = NOW() WHERE id = ?',
                    [error.message, email.id]
                );
            }
        }
    } catch (error) {
        throw new Error('Error processing emails: ' + error.message);
    } finally {
        if (connection) {
            await connection.end();
        }
    }
}

// Process queue every 30 seconds
setInterval(processEmailQueue, 30000);

// Initial processing
processEmailQueue(); 