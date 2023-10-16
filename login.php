<!DOCTYPE html>
<html lang="eg">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>Login</title>
</head>
<body>
<?php
session_start();
include 'databaseConnection.php';

function submitForm(){
    $sql = "SELECT name, password FROM users";
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pw = $_POST["password"];
        $username = $_POST["username"];

        try {
            $stmt = $conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($pw) && !empty($username)) {
                foreach ($results as $row) {
                    echo $pw. $row["password"];
                    if ($row['name'] == $username && password_verify($pw, $row['password']) ) {
                        $_SESSION["username"] = $username;
                        header("Location: dashboard.php");
                    }
                    else{
                        echo "<p class='errorMsg'>Wrong password or username</p>";
                    }
                }
            }
            else{
                session_destroy();
            }
        } catch (PDOException $e) {
            echo "<p class='errorMsg'>Query failed: " . $e->getMessage()."</p>";
        }
    }
}
?>
    <main>
        <h2 class="title">Login</h2>
        <form method="post" action="<?php submitForm(); ?>">
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