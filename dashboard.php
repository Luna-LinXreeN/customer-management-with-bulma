<!DOCTYPE html>
<html lang="eg">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>Dashboard</title>
</head>

<?php
include "databaseConnection.php";
session_start();

if (!isset($_SESSION["username"])){
    header("Location: index.php");
}

    function logout(){
        session_destroy();
        header("Location: index.php");
        exit();
    }

    function pushNewCompany()
    {
        global $conn;
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $companyName = $_POST["companyName"];
            $contactPerson = $_POST["contactPerson"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $createdBy = $_SESSION["username"];
            $msg = "";

            try {
                $stmt = $conn->prepare('INSERT INTO clients (company_name, contact_person, phone, address, created_by) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$companyName, $contactPerson, $phone, $address, $createdBy]);
                $msg = "<p>Client got added to the clients table</p>";


            }catch (PDOException $e) {
                $msg = "<p class='errorMsg'>Query failed: " . $e->getMessage()."</p>";
            }
            echo $msg;
        }
    }

?>
<body>
    <header>
        <h1 class="title">Dashboard</h1>
        <div class="block">
            <p>Logged in as <?php echo $_SESSION["username"]; ?></p>

            <form method="post">
                <div class="container">
                    <input class="button" type="submit" value="Logout" name="logout">
                </div>
            </form>
        </div>
    </header>

    <main>
        <h2 class="subtitle">Add new customers to database</h2>
        <form method="post">
            <div class="field">
                <label for="companyName" class="label">Company Name:</label>
                <div class="control">
                    <input type="text" name="companyName" id="companyName" class="input">
                </div>
            </div>
            <div class="field">
                <label for="contactPerson" class="label">Contact Person:</label>
                <div class="control">
                    <input class="input" type="text" name="contactPerson" id="contactPerson">
                </div>
            </div>
            <div class="field">
                <label for="phone" class="label">Phone Number:</label>
                <div class="control">
                    <input class="input" type="text" name="phone" id="phone">
                </div>
            </div>
            <div class="field">
                <label for="address" class="label">Address:</label>
                <div class="control">
                    <input class="input" type="text" name="address" id="address">
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <input class="button" type="submit" value="SUBMIT" name="submit">
                </div>
            </div>
        </form>
    </main>
    <nav class="navbar container">
        <div class="navbar-item">
            <a class="button" href="clients.php">To customer overview</a>
        </div>
    </nav>

<?php

    if (isset($_POST["logout"])){
        logout();
    }
    elseif (isset($_POST["submit"])){
        pushNewCompany();
    }
?>
</body>
</html>
