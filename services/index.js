const mysql = require('mysql2/promise');
const nodemailer = require('nodemailer');
const path = require('path');
const fs = require('fs');

// Load environment variables
require('dotenv').config({ path: path.join(__dirname, '..', '.env') });

// Add at the very top of the file
process.on('uncaughtException', function (err) {
    require('fs').appendFileSync('server.log', 'Uncaught Exception: ' + err + '\n');
    process.exit(1);
});

// Function to check if MySQL is XAMPP's
async function isXamppMySQL(connection) {
    try {
        // Check if it's MariaDB
        const [versionRows] = await connection.query("SHOW VARIABLES LIKE 'version'");
        if (!versionRows.length || !versionRows[0].Value.includes('MariaDB')) {
            return false;
        }

        // Check if it's running from XAMPP directory
        const [dataDirRows] = await connection.query("SHOW VARIABLES LIKE 'datadir'");
        if (!dataDirRows.length || !dataDirRows[0].Value.includes('/opt/lampp/')) {
            return false;
        }

        return true;
    } catch (error) {
        return false;
    }
}

// Function to find MySQL port
async function findMySQLPort() {
    // Try XAMPP's default port first
    const xamppPort = 3307;
    try {
        const connection = await mysql.createConnection({
            host: 'localhost',
            port: xamppPort,
            user: 'root',
            password: ''
        });

        if (await isXamppMySQL(connection)) {
            await connection.end();
            return xamppPort;
        }
        await connection.end();
    } catch (error) {
    }

    // Try other common ports
    const ports = [3306, 3308, 3309, 3310];
    for (const port of ports) {
        try {
            const connection = await mysql.createConnection({
                host: 'localhost',
                port: port,
                user: 'root',
                password: ''
            });

            if (await isXamppMySQL(connection)) {
                await connection.end();
                return port;
            }
            await connection.end();
        } catch (error) {
        }
    }

    throw new Error('No XAMPP MySQL port found. Please make sure XAMPP is running.');
}

// Initialize database configuration
async function initializeDB() {
    try {
        const mysqlPort = await findMySQLPort();

        const dbConfig = {
            host: 'localhost',
            port: mysqlPort,
            user: 'root',
            password: '',
            database: 'homeland',
            waitForConnections: true,
            connectionLimit: 10,
            queueLimit: 0
        };

        // Create database pool
        const pool = mysql.createPool(dbConfig);

        // Test the connection
        await pool.query('SELECT 1');

        return pool;
    } catch (error) {
        process.exit(1);
    }
}

// Email configuration
const emailConfig = {
    host: process.env.SMTP_HOST || 'smtp.mailtrap.io',
    port: process.env.SMTP_PORT || 2525,
    auth: {
        user: process.env.SMTP_USER,
        pass: process.env.SMTP_PASS
    }
};

// Create email transporter
const transporter = nodemailer.createTransport(emailConfig);

// Verify SMTP connection configuration
async function verifySMTP() {
    try {
        await transporter.verify();
        return true;
    } catch (error) {
        return false;
    }
}

// Process email queue
async function processEmailQueue(pool) {
    let connection;
    try {
        // Connect to database
        connection = await pool.getConnection();

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
    } finally {
        if (connection) {
            connection.release();
        }
    }
}

// Main function
async function main() {
    try {
        const pool = await initializeDB();

        // Verify SMTP connection
        if (!await verifySMTP()) {
            process.exit(1);
        }

        // Process queue every 30 seconds
        setInterval(() => processEmailQueue(pool), 30000);

        // Initial processing
        await processEmailQueue(pool);

    } catch (error) {
        process.exit(1);
    }
}

// Start the service
main(); 