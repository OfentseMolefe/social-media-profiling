<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        h2 {
            margin-top: 20px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Track Your Application</h1>
        <form action="track_application.php" method="GET">
            <div class="form-group">
                <label for="email">Enter your email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Track Application</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
        <br>
        <div id="application-details">
            <?php
            // Include database connection
            include "db_conn.php";

            // Check if email parameter is provided
            if (isset($_GET['email'])) {
                $email = $_GET['email'];

                // Prepare SQL statement to retrieve candidate details
                $sql = "SELECT c.status, p.first_name, p.last_name, p.email, c.cellphone_number, p.occupation
                        FROM candidate c
                        JOIN person p ON c.person_ID = p.person_ID
                        WHERE p.email = ?";

                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<h2>Candidate Details</h2>";
                        echo "<p><strong>Name:</strong> " . $row['first_name'] . " " . $row['last_name'] . "</p>";
                        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
                        echo "<p><strong>Cellphone Number:</strong> " . $row['cellphone_number'] . "</p>";
                        echo "<p><strong>Occupation:</strong> " . $row['occupation'] . "</p>";
                        // Add more fields as needed
                    }
                } else {
                    echo "<p class='error'>No candidate found for the email: " . $email . "</p>";
                }

                // Close statement and database connection
                $stmt->close();
                $conn->close();

            } else {
                echo "<p class='error'>Please provide an email to retrieve candidate details.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
