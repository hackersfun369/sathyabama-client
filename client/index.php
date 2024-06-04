<?php
session_start();
$error_flag = isset($_SESSION['login_error_message']);
unset($_SESSION['login_error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url("https://www.sathyabama.ac.in/sites/default/files/2020-01/LIBERAY.jpg") no-repeat;
            background-size: cover;
        }
        form {
            width: 400px;
            height: fit-content;
            border: 2px solid white;
            border-radius: 20px;
            text-align: center;
            position: relative;
            background: white;
            opacity: 0.7;
        }
        h2 {
            position: absolute;
            top: 0;
            left: 0;
        }
        label, input {
            margin: 20px;
        }
        label {
            float: left;
            margin: 20px;
            font-size: 18px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        .username {
            margin-top: 30px;
            font-weight: bold;
        }
        .password {
            margin-top: 10px;
            font-weight: bold;
        }
        .username-in {
            margin-top: 2px;
            width: 80%;
            height: 30px;
            border: 3px solid transparent;
            border-bottom-color: grey;
            outline: none;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            background: transparent;
        }
        .password-in {
            margin-top: -10px;
            width: 80%;
            height: 30px;
            border: 3px solid transparent;
            border-bottom-color: grey;
            outline: none;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            background: transparent;
        }
        .login {
            margin-top: -25px;
            width: 200px;
            height: 35px;
            border: 2px solid black;
            border-radius: 20px;
            background: black;
            color: white;
            font-size: 18px;
            font-weight: bold;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        .error {
            background-color: red;
            color: white;
            font-size: 18px;
            margin-top: 0;
            height: 40px;
            display: <?php echo $error_flag ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <div class="error">
            <?php if ($error_flag) echo "Invalid username or password."; ?>
        </div>
        <label class="username" for="username">Username</label>
        <br>
        <input class="username-in" type="text" id="username" name="username" placeholder="Enter Valid Username" required><br><br>
        <label class="password" for="password">Password</label>
        <br>
        <input class="password-in" type="password" name="password" id="password" placeholder="Enter Valid Password" required><br><br>
        <input class="login" type="submit" value="Login">
        <?php
        if (isset($_GET['success'])) {
            echo "<p>Login successful. Welcome!</p>";
        }
        ?>
    </form>
</body>
</html>
