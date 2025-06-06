<?php
// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'port' => 3307, // Default XAMPP port
    'user' => 'root',
    'password' => '',
    'database' => 'homeland'
];

// Function to check if Node.js server is running
function isNodeServerRunning() {
    $output = [];
    exec("ps aux | grep 'node index.js' | grep -v grep", $output);
    return !empty($output);
}

// Function to start Node.js server
function startNodeServer() {
    $scriptPath = __DIR__ . '/start-server.sh';
    exec("nohup $scriptPath > /dev/null 2>&1 &");
}

// Function to check if MySQL is XAMPP's
function isXamppMySQL($conn) {
    try {
        // Check if it's MariaDB
        $result = $conn->query("SHOW VARIABLES LIKE 'version'");
        if (!$result || !($row = $result->fetch_assoc())) {
            return false;
        }
        if (strpos($row['Value'], 'MariaDB') === false) {
            return false;
        }

        // Check if it's running from XAMPP directory
        $result = $conn->query("SHOW VARIABLES LIKE 'datadir'");
        if (!$result || !($row = $result->fetch_assoc())) {
            return false;
        }
        return strpos($row['Value'], '/opt/lampp/') !== false;
    } catch (Exception $e) {
        return false;
    }
}

// Function to find MySQL port
function findMySQLPort() {
    // Try XAMPP's default port first
    $xamppPort = 3307;
    $conn = @mysqli_connect('localhost', 'root', '', '', $xamppPort);
    if ($conn && isXamppMySQL($conn)) {
        mysqli_close($conn);
        return $xamppPort;
    }
    if ($conn) {
        mysqli_close($conn);
    }

    // Try to get port from XAMPP configuration
    $xamppConfig = '/opt/lampp/etc/my.cnf';
    if (file_exists($xamppConfig)) {
        $config = file_get_contents($xamppConfig);
        if (preg_match('/port\s*=\s*(\d+)/', $config, $matches)) {
            $port = (int)$matches[1];
            $conn = @mysqli_connect('localhost', 'root', '', '', $port);
            if ($conn && isXamppMySQL($conn)) {
                mysqli_close($conn);
                return $port;
            }
            if ($conn) {
                mysqli_close($conn);
            }
        }
    }

    // If XAMPP port not found, try other common ports
    $ports = [3308, 3309, 3310];
    foreach ($ports as $port) {
        $conn = @mysqli_connect('localhost', 'root', '', '', $port);
        if ($conn && isXamppMySQL($conn)) {
            mysqli_close($conn);
            return $port;
        }
        if ($conn) {
            mysqli_close($conn);
        }
    }

    return null;
}

// Function to queue an email
function queueEmail($conn, $to, $subject, $message) {
    $stmt = $conn->prepare("INSERT INTO email_queue (to_email, subject, message, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("sss", $to, $subject, $message);
    return $stmt->execute();
}

// Find the correct MySQL port
$port = findMySQLPort();
if ($port) {
    $dbConfig['port'] = $port;
    echo "Found XAMPP MySQL running on port: $port\n";
} else {
    echo "Error: Could not find XAMPP MySQL port. Please make sure XAMPP is running.\n";
    exit(1);
}

try {
    // Connect to database
    $conn = new mysqli(
        $dbConfig['host'],
        $dbConfig['user'],
        $dbConfig['password'],
        $dbConfig['database'],
        $dbConfig['port']
    );

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get email data from POST request
    $to = $_POST['to'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($to) || empty($subject) || empty($message)) {
        throw new Exception("Missing required email fields");
    }

    // Queue the email
    if (queueEmail($conn, $to, $subject, $message)) {
        // If server is not running, start it
        if (!isNodeServerRunning()) {
            startNodeServer();
            echo "Email queued and Node.js server started\n";
        } else {
            echo "Email queued, Node.js server already running\n";
        }
    } else {
        throw new Exception("Failed to queue email");
    }

    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 