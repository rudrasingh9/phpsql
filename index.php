<?php
// Database connection parameters
$host = "localhost"; // Change this if your MySQL host is different
$username = "root"; // Change this if your MySQL username is different
$password = ""; // Change this if your MySQL password is different
$database = "my_database"; // Change this to your desired database name

// Establish a database connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize variables for registration form
$name = $email = $address = $password = "";

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $password = $_POST["password"];

    // Validate form data (you can add more validation if needed)
    if (empty($name) || empty($email) || empty($address) || empty($password)) {
        $registrationError = "Please fill in all the fields.";
    } else {
        // Check if the email is already registered
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            $registrationError = "Email already registered.";
        } else {
            // Insert new user into the database
            $query = "INSERT INTO users (name, email, address, password) VALUES ('$name', '$email', '$address', '$password')";
            $result = mysqli_query($connection, $query);
            if ($result) {
                $registrationSuccess = "Registration successful. You can now log in.";
            } else {
                $registrationError = "Registration failed. Please try again.";
            }
        }
    }
}

// Initialize variables for login form
$emailLogin = $passwordLogin = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Retrieve form data
    $emailLogin = $_POST["emailLogin"];
    $passwordLogin = $_POST["passwordLogin"];

    // Validate form data (you can add more validation if needed)
    if (empty($emailLogin) || empty($passwordLogin)) {
        $loginError = "Please enter your email and password.";
    } else {
        // Check if the email and password match a registered user
        $query = "SELECT * FROM users WHERE email = '$emailLogin' AND password = '$passwordLogin'";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) == 1) {
            $loginSuccess = "Login successful. Welcome!";
        } else {
            $loginError = "Invalid email or password. Please try again.";
        }
    }
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Registration and Login Example</title>
    <style>
        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Registration</h1>
    <?php if (isset($registrationSuccess)) : ?>
        <p class="success"><?php echo $registrationSuccess; ?></p>
    <?php endif; ?>
    <?php if (isset($registrationError)) : ?>
        <p class="error"><?php echo $registrationError; ?></p>
    <?php endif; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $name; ?>">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>">
        <br>
        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $address; ?>">
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password">
        <br>
        <input type="submit" name="register" value="Register">
    </form>

    <h1>Login</h1>
    <?php if (isset($loginSuccess)) : ?>
        <p class="success"><?php echo $loginSuccess; ?></p>
    <?php endif; ?>
    <?php if (isset($loginError)) : ?>
        <p class="error"><?php echo $loginError; ?></p>
    <?php endif; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="emailLogin">Email:</label>
        <input type="email" name="emailLogin" value="<?php echo $emailLogin; ?>">
        <br>
        <label for="passwordLogin">Password:</label>
        <input type="password" name="passwordLogin">
        <br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>
