<?php
// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'port' => 3307, 
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
    $ports = [3306,3308, 3309, 3310];
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

    // Check for pending emails
    $result = $conn->query("SELECT COUNT(*) as count FROM email_queue WHERE status = 'pending'");
    $row = $result->fetch_assoc();
    $pendingEmails = $row['count'];

    // If there are pending emails and server is not running, start it
    if ($pendingEmails > 0 && !isNodeServerRunning()) {
        startNodeServer();
        echo "Started Node.js server to process $pendingEmails pending emails\n";
    } else if ($pendingEmails > 0) {
        echo "Node.js server is already running, processing $pendingEmails pending emails\n";
    } else {
        echo "No pending emails found\n";
    }

    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 