<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "loginpage";

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $projectname = $conn->real_escape_string($_POST["projectname"]);
    $domain = $conn->real_escape_string($_POST["domain"]);
    $projectdesc = $conn->real_escape_string($_POST["projectdesc"]);
    $link = $conn->real_escape_string($_POST["link"]);
    $username = $conn->real_escape_string($_POST["username"]);

    // Check for duplicate project name
    $checkquery = $conn->prepare("SELECT COUNT(*) FROM paperdetails WHERE projectname = ? AND username = ?");
    $checkquery->bind_param("ss", $projectname, $username);
    $checkquery->execute();
    $checkquery->bind_result($count);
    $checkquery->fetch();
    $checkquery->close();

    if ($count > 0) {
        echo "<script>alert('Paper With Same Paper Name Is Already Uploaded.');</script>";
    } else {
        $insertquery = $conn->prepare("INSERT INTO paperdetails (projectname, projectdesc, domain, link, username) VALUES (?, ?, ?, ?, ?)");
        $insertquery->bind_param("sssss", $projectname, $projectdesc, $domain, $link, $username);

        if ($insertquery->execute()) {
            echo "<script>alert('Paper Uploaded Successfully.');</script>";
        } else {
            echo "<script>alert('Error In Uploading File');</script>";
        }

        $insertquery->close();
    }

    $conn->close();

    // Display the appropriate alert message
    echo "<script>
        var alertElement = document.getElementById('alert1');
        alertElement.textContent = 'Paper Details Uploaded successfully';
        alertElement.style.display = 'block';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caudex:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Sathyabama | Upload</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #E5E4E2;
        }
        .upload {
            position: absolute;
            top: 160px;
            left: 16%;
            display: flex;
            justify-content: center;
        }
        #upload-form {
            width: 700px;
            border: 2px solid white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            border-radius: 20px;
            box-shadow: 2px 2px 0 0 black;
        }
        #upload-form input,
        #upload-form textarea {
            width: 100%;
            border: 2px solid transparent;
            text-transform: capitalize;
            border-bottom-color: black;
            font-size: 18px;
            outline: none;
            height: 30px;
            background: white;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            margin-bottom: 20px;
        }
        #upload-form #submit {
            border: 2px solid black;
            text-align: center;
            width: 120px;
            height: 40px;
            color: black;
            font-size: 18px;
            text-transform: uppercase;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            border-radius: 10px;
            cursor: pointer;
        }
        #upload-form #submit:hover {
            background: green;
            color: white;
        }
        #upload-form #link-input {
            text-transform: none;
        }
        .alert1{
            position: fixed;
            top: 120px;
            width: 100%;
            background-color: green;
            color: white;
            font-size: 18px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-weight: bold;
            display: none;
        }
        .alert2{
            position: fixed;
            top: 120px;
            width: 100%;
            background-color: red;
            color: white;
            font-size: 18px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-weight: bold;
            display: none;
        }
        .back button{
            position: fixed;
            left: 40px;
            top: 170px;
            border: 4px solid transparent;
            border-bottom-color: blue;
            outline: none;
            background: transparent;
            width: 100px;
            letter-spacing: 1px;
            font-size: 18px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            text-transform: uppercase;
            cursor: pointer;
        }
    </style>
</head>
<body>
<header>
<form class="search" method="POST" action="search.php" target="">
    <input style="display: none;" class="username-in" type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
    <input type="text" class="search-in" id="searchInput" name="searchTerm" required>
    <button class="search-icon" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
    <img class="sathyabama-img" src="https://sathyabama.cognibot.in/pluginfile.php/1/theme_klass/logo/1700589965/Sathyabama%20New%20Logo%20Nov%202023.jpeg" alt="sathyabama">
    <h3 class="nav-username"><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?><i id="user" class="fa-regular fa-user"></i></h3>
</header>
<div class="alert1" id="alert1">
    <p class="success">Paper Details Uploaded successfully</p>
</div>
<div class="alert2" id="alert2">
    <p class="failure">Paper With Same Username And Paper Name Is Already Uploaded</p>
</div>
<div class="upload" id="upload">
    <form id="upload-form" method="post">
        <h3>Upload The Paper Details</h3>
        <input type="text" id="name-input" name="projectname" placeholder="Enter Paper Name" required>
        <input type="text" id="domain-input" name="domain" placeholder="Enter Domain" required>
        <textarea id="desc-input" name="projectdesc" placeholder="Enter Paper Description" rows="4" required></textarea>
        <input type="url" id="link-input" name="link" placeholder="Enter Link" required>
        <input type="hidden" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>">
        <input id="submit" type="submit" value="Upload">
    </form>
    <form class="back" method="POST" action="login.php" target="">
        <input style="display: none;" class="username-in" type="text" id="username" name="username" value="<?php echo $username; ?>" required><br><br>
        <button type="submit" class="back-button"><i class="fa-solid fa-backward"></i>  Back</button>
    </form>
</div>
</body>
</html>