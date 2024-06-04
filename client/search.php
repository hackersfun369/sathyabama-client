<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "loginpage";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Get the entered search term
    $username = $_POST['username'];
    $searchTerm = $_POST['searchTerm'];

    // Query to search for files containing the search term
    $sql = "SELECT projectname,projectdesc,domain,link,username FROM paperdetails WHERE projectname LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

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
            echo "<p><span>Uploaded BY</span>: " . htmlspecialchars($row["username"], ENT_QUOTES, 'UTF-8') . "</a></p>";
            echo "<p><span>Paper Name</span>: " . $projectname . "</p>";
            echo "<p><span>Domain</span>: " . htmlspecialchars($row["domain"], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><span>Link</span>: <a href='" . htmlspecialchars($row["link"], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row["link"], ENT_QUOTES, 'UTF-8') . "</a></p>";
            echo "<p><span>Project Description</span>: " . htmlspecialchars($row["projectdesc"], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "No projects uploaded yet.";
    }
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&family=Salsa&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>PDF Upload</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        header{
    display: flex;
    position: absolute;
    align-items: center;
    justify-content: space-between;
    background-color:#831238;
    width: 100%;
    height: 130px;
    top: 0;
}
.sathyabama-img{
    float: left;
    margin: 0;
}
.profile-img{
    float: right;
    border-radius: 50px;
    margin-left: 10px;
}
.nav-username{
    color: white;
    font-size: 20px;
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
    float: right;
    margin-right: 30px;
    letter-spacing: 0.2rem;
    text-transform: capitalize;
}
.back{
    position: fixed;
    top: 120px;
    left: 20px;
}
.back button{
            position: fixed;
            left: 40px;
            top: 150px;
            border: 4px solid transparent;
            border-bottom-color: blue;
            outline: none;
            background: transparent;
            width: 100px;
            letter-spacing: 2px;
            font-size: 16px;
        }
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
            width: 70%;
            left: 130px;
            flex-direction: column;
            gap: 5px;
            top: 140px;
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
            background-color: lightgray;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            cursor: pointer;
        }
        header{
            position: fixed;
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
        body {
            background-color: #E5E4E2;
        }
    </style>
</head>
<body>
    
<header>
        <img class="sathyabama-img" src="https://sathyabama.cognibot.in/pluginfile.php/1/theme_klass/logo/1700589965/Sathyabama%20New%20Logo%20Nov%202023.jpeg" alt="sathyabama">
        <h3 class="nav-username"><?php echo $username?><i id="user" class="fa-regular fa-user"></i></h3>
    </header>
    <form method="post">
        <?php if (isset($_SESSION['username'])) : ?>
            <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" style="display: none;">
        <?php endif; ?>
    </form>

    <form class="back" method="POST" action="login.php" target="">
    <input style="display: none;" class="username-in" type="text" id="username" name="username" value="<?php echo $username; ?>" required><br><br>
    <button type="submit"><i class="fa-solid fa-backward"></i>  Back</button>
    </form>
    <div class="actions-container">
    <form action="upload.php" id="upload-btn">
        <button class="uploadbtn">Upload Paper</button>
        <input type="hidden" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>" name="username">
    </form>
</div>
<script>
    function toggleDescription(projectname) {
        const desc = document.getElementById('desc_' + projectname);
        if (desc.style.display === 'none' || desc.style.display === '') {
            desc.style.display = 'block';
        } else {
            desc.style.display = 'none';
        }
    }
</script>
</body>
</html>
