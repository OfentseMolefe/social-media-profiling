<?php
include "db_conn.php";

if (isset($_POST["submit"])) {
    // Check if connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get and sanitize input data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password =$_POST['password']; // Hash the password
    $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Prepare SQL statement for person table
        $stmtp = $conn->prepare("INSERT INTO `person`(`person_ID`, `first_name`, `last_name`, `email`, `occupation`) VALUES (NULL, ?, ?, ?, ?)");
        
        // Bind parameters for person table
        $stmtp->bind_param("ssss", $first_name, $last_name, $email, $occupation);
        
        // Execute statement for person table
        $stmtp->execute();
        
        // Get the auto-generated person_ID
        $person_id = mysqli_insert_id($conn);
        
        // Prepare SQL statement for recruiter table
        $stmtr = $conn->prepare("INSERT INTO `recruiter`(`recruiter_ID`, `username`, `password`, `person_ID`) VALUES (NULL, ?, ?, ?)");
        
        // Bind parameters for recruiter table
        $stmtr->bind_param("ssi", $email, $password, $person_id);
        
        // Execute statement for recruiter table
        $stmtr->execute();
        
        // Commit transaction
        mysqli_commit($conn);
        
        header("Location: admin.php?msg=New record created successfully");
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo "Failed: " . $e->getMessage();
    }

    // Close statements and connection
    $stmtp->close();
    $stmtr->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Add new User</title>
    <style>
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .error-input {
            border: 2px solid red;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        Add HR
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Add New Employee</h3>
            <p class="text-muted">Complete the form below to add a new user</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form id="captureForm" action="" method="post" style="width:50vw; min-width:300px;" onsubmit="return validateForm()">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="first_name" placeholder="your name" required>
                    </div>

                    <div class="col">
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="last_name" placeholder="your surname" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password :</label>
                    <input type="password" class="form-control" name="password" value="12345" readonly required>
                </div>

                <div class="form-group mb-3">
                    <label>Occupation:</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="occupation" id="admin" value="Admin" required>
                    <label for="admin" class="form-input-label">ADMIN</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="occupation" id="hr" value="Hr" required>
                    <label for="hr" class="form-input-label">HR</label>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="super_user.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div id="spinner-overlay" class="spinner-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        function validateForm() {
            var form = document.getElementById("captureForm");
            var inputs = form.getElementsByTagName("input");
            var valid = true;

            // Reset borders of all input fields
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].classList.remove("error-input");
            }

            // Check if all fields are attended
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].hasAttribute("required") && inputs[i].value === "") {
                    alert("Please fill in all fields");
                    inputs[i].classList.add("error-input");
                    valid = false;
                }
            }

            // If form is valid, show the spinner
            if (valid) {
                document.getElementById('spinner-overlay').style.display = 'flex';
            }

            return valid;
        }
    </script>
</body>

</html>
