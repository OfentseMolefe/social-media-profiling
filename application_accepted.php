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
    <style>
.status-item {
    display: flex;
    align-items: center;
}

.status-button {
    width: 60px; /* Adjust width to fit your content */
    height: 60px; /* Adjust height to fit your content */
    border-radius: 50%; /* Makes the button circular */
    border: none;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    margin-right: 10px; /* Adjust spacing between button and name */
}

.status-name {
    font-size: 14px; /* Adjust font size of status names */
    white-space: nowrap; /* Prevents wrapping of long names */
}

.accepted { background-color: #28a745; color: white; }
.declined { background-color: #dc3545; color: white; }
.in-progress { background-color: #ffc107; color: black; }
.completed { background-color: #007bff; color: white; }
.yellow-circle { background-color: yellow; color: black; }

.container {
    margin-top: 20px;
}

.row {
    display: flex;
    justify-content: space-between;
}

.col {
    flex: 1;
}



        
        .table-wrapper {
            max-height: 400px;
            overflow-y: auto;
        }
        .table-hover tbody tr:hover {
            cursor: pointer;
            background-color: #f5f5f5;
        }
        .btn-vetting-default {
            background-color: grey;
            border-color: grey;
        }
        .btn-vetting-clicked {
            background-color: blue;
            border-color: blue;
        }

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
        .status-circle {
        width: 50px; /* Adjust width as needed */
        height: 50px; /* Adjust height as needed */
        border-radius: 50%;
        text-align: center;
        line-height: 50px; /* Center text vertically */
        font-size: 18px;
        font-weight: bold;
    }

    .completed {
        background-color: green; /* Default color (example: green) */
        color: white; /* Text color (example: white) */
    }

    .yellow-circle {
        background-color: yellow !important; /* Yellow background color */
    }
    </style>
</head>

<body>

    
    </div>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        Declined Applicants
    </nav>
        <br>
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
                    $sql = "SELECT c.candidate_ID,c.identity_number, p.first_name, p.last_name, p.email, c.cellphone_number, c.status,  p.occupation
                            FROM candidate c
                            JOIN person p ON c.person_ID = p.person_ID
                            WHERE c.status = 'accepted' ";
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
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            </div>
        <!-- Back Button -->
        <div class="text-center mt-3">
            <a href="super_user.php" onclick="history.back();" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        </div>
    </div>
</div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="assets/js/searchpage.js"></script>
   
</body>

</html>
