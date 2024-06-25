<?php
include "db_conn.php";
$recruiterID = $_GET["recruiter_ID"];

if (isset($_POST["submit"])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $occupation = $_POST['occupation'];

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($occupation)) {
        // Begin transaction
        mysqli_begin_transaction($conn);

        try {
            // Update person table
            $sqlPerson = "UPDATE `person` p
                          JOIN `recruiter` r ON p.person_ID = r.person_ID
                          SET p.first_name = ?, p.last_name = ?, p.email = ?, r.username = ?, p.occupation = ?
                          WHERE r.recruiter_ID = ?";
            $stmtPerson = $conn->prepare($sqlPerson);
            $stmtPerson->bind_param("sssssi", $first_name, $last_name, $email, $email, $occupation, $recruiterID);
            $stmtPerson->execute();

            // Update recruiter table
            if (!empty($password)) {
                $sqlRecruiter = "UPDATE `recruiter` SET `password` = ? WHERE `recruiter_ID` = ?";
                $stmtRecruiter = $conn->prepare($sqlRecruiter);
                $stmtRecruiter->bind_param("si", $password, $recruiterID);
                $stmtRecruiter->execute();
            }

            // Commit transaction
            mysqli_commit($conn);
            header("Location: admin.php?msg=Data updated successfully");
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            echo "Failed: " . $e->getMessage();
        }
    } else {
        echo "Please fill out all fields.";
    }
}

// Fetch existing data for the form
$sql = "SELECT r.username, r.password, p.first_name, p.last_name, p.email, p.occupation 
        FROM recruiter r
        JOIN person p ON r.person_ID = p.person_ID
        WHERE r.recruiter_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recruiterID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
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

    <title>Edit Application</title>
    <style>
        #spinner {
            display: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        Edit page
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Edit User Information</h3>
            <p class="text-muted">Click update after changing any information</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;" onsubmit="showSpinner()">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo $row['first_name'] ?>" required>
                    </div>

                    <div class="col">
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo $row['last_name'] ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $row['email'] ?>" required>
                </div>

                <div class="col">
                    <label class="form-label">Password :</label>
                    <input type="password" class="form-control" name="password" value="<?php echo $row['password'] ?>">
                </div>
                <br>
                <div class="form-group mb-3">
                    <label>Occupation:</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="occupation" id="admin" value="Admin" <?php echo ($row['occupation'] == 'Admin') ? 'checked' : '' ?> readonly>
                    <label for="admin" class="form-input-label">ADMIN</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="occupation" id="hr" value="Hr" <?php echo ($row['occupation'] == 'Hr') ? 'checked' : '' ?> readonly>
                    <label for="hr" class="form-input-label">HR</label>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="admin.php" class="btn btn-danger">Cancel</a>
                </div>
                <div id="spinner" class="spinner-border text-primary mt-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
        function showSpinner() {
            document.getElementById('spinner').style.display = 'block';
        }
    </script>

</body>

</html>
