<?php
// Start the session
session_start();
include "db_conn.php";

// Retrieve the first name and last name from the URL if they are set and add to the session
$first_name = isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : '';
$last_name = isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : '';
$candidate_ID = isset($_GET['candidate_ID']) ? htmlspecialchars($_GET['candidate_ID']) : '';

$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['candidate_ID'] = $candidate_ID; // Add the application id into the session

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmF/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/searchpage.css">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
            All Applicants
        </nav>
        <!-- Add the Table for applicants -->
        <div class="table-wrapper mt-2">
            <table class="table table-hover text-center" id="applicantTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" data-column="candidate_id">Candidate ID</th>
                        <th scope="col" data-column="first_name">First Name</th>
                        <th scope="col" data-column="last_name">Last Name</th>
                        <th scope="col" data-column="email">Email</th>
                        <th scope="col" data-column="phone">Phone</th>
                        <th scope="col" data-column="occupation">Occupation</th>
                        <th scope="col" data-column="status">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "db_conn.php";
                    $sql = "SELECT c.candidate_ID, c.identity_number, p.first_name, p.last_name, p.email, c.cellphone_number, c.status, p.occupation
                            FROM candidate c
                            JOIN person p ON c.person_ID = p.person_ID";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr onclick="populateSearchBar('<?php echo $row['first_name']; ?>', '<?php echo $row['last_name']; ?>')">
                            <td><?php echo $row["candidate_ID"]; ?></td>
                            <td><?php echo $row["first_name"]; ?></td>
                            <td><?php echo $row["last_name"]; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td><?php echo $row["cellphone_number"]; ?></td>
                            <td><?php echo $row["occupation"]; ?></td>
                            <td><?php echo $row["status"]; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Back Button -->
        <div class="text-center mt-3">
            <a href="super_user.php" onclick="history.back();" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="assets/js/searchpage.js"></script>
</body>

</html>
