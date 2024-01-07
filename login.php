
<?php
session_start();

// Database connection parameters for HeidiSQL
$host = 'localhost';
$port = '3306'; // Default MySQL port is 3306
$dbname = 'projet1';
$usernameDB = 'root';
$passwordDB = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $id_user = -1;

    // Query the database to fetch the user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE UPPER(name_users) = UPPER(?)");
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the username exists
    if ($result) {
        $id_user = $result['id_users'];
        $hashedPassword = $result['password_users'];
        $is_connected = $result['is_connected'];

        // Test if the user is already connected
        if ($is_connected) {
            echo "User is already connected";
            exit();
        }

       // $apass = password_hash('mimi', PASSWORD_DEFAULT);
       // $updateStmt = $pdo->prepare("UPDATE users SET password_users = ?  WHERE id_users = 1");
       // $updateStmt->execute([$apass]);


        // Check if the entered password is correct
        if (password_verify($password, $hashedPassword)) {
            // User exists and password is correct

            // create session data
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['id_user'] = $id_user;
            $_SESSION['is_connected'] = $is_connected;

            // Update user information in the database
            $updateStmt = $pdo->prepare("UPDATE users SET last_connect = CURRENT_TIMESTAMP, is_connected = 1 WHERE id_users = ?");
            $updateStmt->execute([$id_user]);

            // Retrieve the registration time from the database
            $timeStmt = $pdo->prepare("SELECT last_connect FROM users WHERE id_users = ?");
            $timeStmt->execute([$id_user]);
            $registrationTime = $timeStmt->fetchColumn();

            // Format the registration time for display (adjust the format as needed)
            $formattedRegistrationTime = date('Y-m-d H:i:s', strtotime($registrationTime));

            echo "User registered successfully at $formattedRegistrationTime!";
        } else {
            echo "Incorrect password! $hashedPassword";
        }
    } else {
        // Username does not exist in the database
        echo "User does not exist in the database!";
    }
}

$pdo = null;
?>






