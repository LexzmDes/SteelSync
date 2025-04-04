<?php
session_start();

// If user is already logged in, redirect to their respective dashboard
if (isset($_SESSION["user"])) {
    redirectBasedOnRole($_SESSION["role"]);
    exit();
}

$host = "localhost";
$dbname = "steelsync";
$dbuser = "root";
$dbpass = "";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function redirectBasedOnRole($role)
{
    switch ($role) {
        case 'super_admin':
            header("Location: index.php");
            break;
        case 'hr_admin':
            header("Location: hradmin/hr_admin.php");
            break;
        case 'support_admin':
            header("Location: supportadmin/index.php");
            break;
        case 'employee':
            header("Location: employee/index.php");
            break;
        default:
            header("Location: login.php");
    }
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        if (md5($password) === $hashed_password) {
            $_SESSION["user"] = $username;
            $_SESSION["user_id"] = $user_id;
            $_SESSION["role"] = $role;

            // Update last login for the specific role
            updateLastLogin($conn, $user_id, $role);

            redirectBasedOnRole($role);
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }

    $stmt->close();
}

function updateLastLogin($conn, $user_id, $role)
{
    $table_map = [
        'super_admin' => 'super_admin',
        'hr_admin' => 'hr_admin',
        'support_admin' => 'support_admin',
        'employee' => 'employee'
    ];

    if (isset($table_map[$role])) {
        $table = $table_map[$role];

        // Debug: Verify table exists
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            error_log("Table $table does not exist");
            return false;
        }

        $query = "UPDATE $table SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }

        if (!$stmt->bind_param("i", $user_id)) {
            error_log("Bind failed: " . $stmt->error);
            return false;
        }

        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }

        $stmt->close();
        return true;
    }
    return false;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SteelSync - Login</title>
    <link rel="stylesheet" href="css.css/login.css">

</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="../images/Group 70.png" alt="SteelSync Logo">
        </div>
        <h2>SteelSync</h2>
        <p>A WEB-BASED SYSTEM FOR METAL & STEEL FABRICATION</p>
        <form method="POST" action="">
            <div class="input-container">
                <input type="text" name="username" placeholder="Enter Your Username" required>
                <img src="../images/user.png" alt="User Icon" class="icon">
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Enter Your Password" required>
                <img src="../images/key.png" alt="Key Icon" class="icon">
            </div>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

            <button type="submit">Login</button>
        </form>
        <a href="#" class="forgot-password">Forgot Password?</a>
    </div>
</body>

</html>