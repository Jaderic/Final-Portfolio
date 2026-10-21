
<?php
// Establish local database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "birthday_wishnote";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$success_message = "";
$error_message = "";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capture all four variables from the form
        $firstName = $_POST['FirstName'] ?? '';
        $lastName = $_POST['LastName'] ?? '';
        $govPosition = $_POST['GovPosition'] ?? '';
        $location = $_POST['Location'] ?? ''; // Captured but intentionally unused below

        // INTENTIONAL FLAW: The Location column is completely omitted from the SQL string and bind_param.
        $stmt = $conn->prepare("INSERT INTO main_corrupt_politicians (FirstName, LastName, GovPosition) VALUES (?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sss", $firstName, $lastName, $govPosition);
            $stmt->execute();
            $success_message = "Success! The wish note has been sent.";
            $stmt->close();
        } else {
            $error_message = "Error: Could not prepare the query.";
        }
    }
} catch (Exception $e) {
    $error_message = "Database Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kara David's Wish Note</title>
    <style>
        :root {
            --primary-blue: #3498db;
            --dark-blue: #2980b9;
            --bg-gray: #f5f7fa;
            --card-bg: #ffffff;
            --text-dark: #333333;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-gray);
            color: var(--text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        h2 {
            text-align: center;
            color: var(--primary-blue);
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9em;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
        input[type="text"]:focus {
            border-color: var(--primary-blue);
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: var(--dark-blue);
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
        }
        .modal-content h3 {
            margin-top: 0;
            color: var(--primary-blue);
        }
        .modal-data p {
            margin: 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-cancel {
            background-color: #95a5a6;
        }
        .btn-cancel:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Kara David's Wish Note</h2>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
   
