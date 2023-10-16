<!DOCTYPE html>
<html lang="eg">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>Register</title>
</head>
<body>
<?php
include "databaseConnection.php";

function register()
{
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $mail = $_POST["email"];
        $pw = $_POST["password"];
        $repassword = $_POST["repassword"];
        $msg = "";

        try {
            $stmt = $conn->query("SELECT name FROM users");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($username) && !empty($mail) && !empty($pw) && !empty($repassword)){
                if ($pw == $repassword){
                    foreach ($results as $row){
                        if ($row["name"] == $username){
                            echo "<p class='errorMsg'>Username already exists</p>";
                            exit(100);
                        }
                    }
                    $pw = password_hash($pw, PASSWORD_BCRYPT);

                    $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
                    $stmt->execute([$username, $mail, $pw]);
                    $msg = "<p>User was created</p>";

                    session_start();
                    $_SESSION["username"] = $username;
                    header("Location: index.php");
                }
                else{
                    $msg = "<p>Passwords do not match</p>";
                }
            }

        } catch (PDOException $e) {
            $msg = "<p class='errorMsg'> Query failed: " . $e->getMessage()."</p>";
        }

        echo $msg;
    }
}

?>
    <main>
        <h2 class="title">Register</h2>
        <form method="post" action="<?php register(); ?>">
            <div class="field">
                <label for="username" class="label">Username:</label>
                <div class="control has-icons-left has-icons-right">
                    <input class="input is-success" type="text" name="username" id="username">
                    <span class="icon is-small is-left">
                        <i class="fas fa-user"></i>
                    </span>
                    <span class="icon is-small is-right">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                <p class="help is-success">This username is available</p>
            </div>
            <div class="field">
                <label for="email" class="label">E-Mail:</label>
                <div class="control has-icons-left has-icons-right">
                    <input class="input is-danger" type="email" name="email" id="email">
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon is-small is-right">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
            </div>
            <div class="field">
                <label for="password" class="label">Password:</label>
                <div class="control has-icons-left has-icons-right">
                    <input class="input is-danger" type="password" name="password" id="password">
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon is-small is-right">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
            </div>
            <div class="field">
                <label for="repassword" class="label">Confirm Password:</label>
                <div class="control has-icons-left has-icons-right">
                    <input class="input is-danger" type="password" name="repassword" id="repassword">
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon is-small is-right">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
            </div>
            <div class="field">
                <div class="control">
                    <input class="button is-success" type="submit" value="SUBMIT">
                </div>
            </div>
        </form>
    </main>

    <nav class="navbar container">
        <div class="navbar-item">
            <a class="button is-primary" href="login.php">Login</a>
            <a class="button is-light" href="register.php">Register</a>
        </div>
    </nav>
</body>
</html>
