<?php
// Start the session
session_start();
include "db_conn.php";

// Retrieve the first name and last name from the URL if they are set add to the session
$first_name = isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : '';
$last_name = isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : '';
$applicant_id = isset($_GET['applicant_id']) ? htmlspecialchars($_GET['applicant_id']) : '';

$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['applicant_id'] = $applicant_id; // Add the application id into the session

// Concatenate first name and last name with a space between them
$full_name = $first_name . ' ' . $last_name;
$_SESSION['full_name'] = $full_name;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/searchpage.css">
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        Search social media's Links
    </nav>


    <div class="container">
        <!-- Dropdown Menu -->
        <div class="menu-bar">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle menu-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Menu
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="applicants.php"><i class="fas fa-users"></i> View Applicants</a></li>
                    <li><a class="dropdown-item" href="candidate_table.php"><i class="fas fa-user"></i> Prospective Candidate(s)</a></li>
                    <li><a class="dropdown-item" href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>

            </div>
        </div>
        <div class="d-flex align-items-right">

            <?php

            if (isset($_SESSION['username'])) {
                // Display an active dot and the username
                echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div class="row height d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <form action="result.php" method="POST">
                    <div class="search">
                        <i class="fa fa-search"></i>
                        <input type="text" name="searchKey" class="form-control" placeholder="View applicant table (Menu) or enter full name of a person to search" value="<?php echo $full_name; ?>">
                        <button class="btn btn-primary">Search</button>
                    </div>
                    <div class="mt-3">
                        <label for="linkedin" class="form-label">Please choose the social media platforms you'd like to search:</label><br>
                        <input type="checkbox" id="linkedin" name="linkedin" value="linkedin">
                        <label for="linkedin">LinkedIn</label><br>
                        <input type="checkbox" id="facebook" name="facebook" value="facebook">
                        <label for="facebook">Facebook</label><br>
                        <input type="checkbox" id="instagram" name="instagram" value="instagram">
                        <label for="instagram">Instagram</label><br>
                        <input type="checkbox" id="twitter" name="twitter" value="twitter">
                        <label for="twitter">Twitter</label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="assets/js/searchpage.js"></script>
</body>

</html>