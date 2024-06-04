<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "loginpage";
    $conn = new mysqli($host, $user, $password, $database);
    if($conn->connect_error) {
        die("connection failed: " . $conn->connect_error);
    }
    $username = $_POST["username"];
    $passkey = $_POST["password"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND userpass = ?");
    $stmt->bind_param("ss", $username, $passkey);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1) {
        $_SESSION['username'] = $username;
        echo "<h2>login successful</h2>";
        echo "<h2>logged as $username</h2>";
    } else {
        header("Location:index.php");
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caudex:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>PDF Upload</title>
    <style>
        .result-container{
            width: 95%;
            display: flex;
            flex-direction: column;
            margin-left: 50px;
            background-color: lightgrey;
            border-radius: 30px;
        }
        .result-container .title{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            background-color: lightblue;
            padding-right: 20px;
            padding-left: 20px;
            text-transform: capitalize;
            height: fit-content;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            cursor: pointer;
            border-radius: 20px;
            font-size: 13px;
            color: black;
        }
        .result-container .domain{
            border-left: 5px solid gray;
            border-radius: 5px;
            padding: 20px;
            width: 200px;
        }
        .result-container .desc{
            display: flex;
            text-align: center;
            flex-direction: column;
            justify-content: center;
            padding-right: 20px;
            padding-left: 20px;
            text-transform: capitalize;
            height: fit-content;
            font-family: Georgia, 'Times New Roman', Times, serif;
            display: none;
        }
        .result-container .desc a{
            text-transform: lowercase;
            text-decoration: none;
            cursor: pointer;
            color: blue;
        }
        .results{
            position: absolute;
            display: flex;
            width: 95%;
            flex-direction: column;
            gap: 5px;
        }
        .viewpapers{
            position: absolute;
            top: 200px;
            width: 80%;
            height: 80%;
        }
        span{
            font-weight: bold;
            margin-right:20px;
            position: relative;
            left: 0px;
        }
        .result-container .desc button{
            position: absolute;
            right: 50px;
            margin-top: -40px;
            width: 150px;
            border: none;
            outline: none;
            height: 30px;
            color: white;
            background-color: red;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            font-family: Georgia, 'Times New Roman', Times, serif;
            cursor: pointer;
        }
        .actions-container button{
            height: 40px;
            font-size: 17px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-weight: bolder;
            border: 4px solid transparent;
            border-bottom-color: blue;
            background-color: transparent;
            cursor: pointer;
            position: fixed;
            top: 150px;
            color: black;
            letter-spacing: 1px;
        }
        .actions-container{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            position: fixed;
            top: 140px;
            width: 80%;
            left: 50px;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #E5E4E2;
        }
    </style>
</head>
<body>
<form class="search" method="POST" action="search.php" target="">
    <input style="display: none;" class="username-in" type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>
    <input type="text" class="search-in" id="searchInput" name="searchTerm" required>
    <button class="search-icon" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
<header>
    <img class="sathyabama-img" src="https://sathyabama.cognibot.in/pluginfile.php/1/theme_klass/logo/1700589965/Sathyabama%20New%20Logo%20Nov%202023.jpeg" alt="sathyabama">
    <h3 class="nav-username"><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?><i id="user" class="fa-regular fa-user"></i></h3>
</header>
<div class="actions-container">
    <h2 class="heading">My Papers</h2>
    <form action="upload.php" id="upload-btn">
        <button class="uploadbtn">Upload Paper</button>
        <input type="hidden" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>" name="username">
    </form>
</div>
<div class="viewpapers" id="viewpapers">
    <?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "loginpage";
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM paperdetails WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<div class='results'>";
        while ($row = $result->fetch_assoc()) {
            $projectname = htmlspecialchars($row["projectname"], ENT_QUOTES, 'UTF-8');
            echo "<div class='result-container'>";
            echo "<div class='title' onclick=\"toggleDescription('$projectname')\">";
            echo "<h2>" . $projectname . "</h2>";
            echo "<p class='domain'>" . htmlspecialchars($row["domain"], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";
            echo "<div class='desc' id='desc_$projectname'>";
            echo "<p><span>Paper Name</span>: " . $projectname . "</p>";
            echo "<p><span>Domain</span>: " . htmlspecialchars($row["domain"], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><span>Link</span>: <a href='" . htmlspecialchars($row["link"], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row["link"], ENT_QUOTES, 'UTF-8') . "</a></p>";
            echo "<p><span>Project Description</span>: " . htmlspecialchars($row["projectdesc"], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<button onclick=\"deleteFile('$projectname')\">DELETE</button>";
            echo "<form id='deleteForm_$projectname' method='post' style='display: none;'>
                <input type='hidden' name='projectname' value='$projectname'>
            </form>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "No projects uploaded yet.";
    }
    $conn->close();
    ?>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["projectname"])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "loginpage";
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $projectname = $_POST["projectname"];
    $deleteQuery = $conn->prepare("DELETE FROM paperdetails WHERE projectname = ?");
    $deleteQuery->bind_param("s", $projectname);
    if ($deleteQuery->execute()) {
    } else {
        echo "<script>alert('Error deleting file');</script>";
    }
    $deleteQuery->close();
    $conn->close();
}
?>
<script>
    function toggleDescription(projectname) {
        const desc = document.getElementById('desc_' + projectname);
        if (desc.style.display === 'none' || desc.style.display === '') {
            desc.style.display = 'block';
        } else {
            desc.style.display = 'none';
        }
    }

    function deleteFile(projectname) {
        var confirmDelete = confirm("Are you sure you want to delete this project?");
        if (confirmDelete) {
            document.getElementById('deleteForm_' + projectname).submit();
        }
    }

</script>
</body>
</html>
