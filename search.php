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


// Count applicants based on their status
$accepted_count = 0;
$declined_count = 0;
$completed_count = 0;

//$sql_count = "SELECT status_id, COUNT(*) as count FROM applicant GROUP BY status_id";

   $sql_count = "SELECT c.status, c.candidate_ID, p.first_name, p.last_name, p.email, c.cellphone_number, p.occupation, count(*) as count
   FROM candidate c
   JOIN person p ON c.person_ID = p.person_ID
   GROUP BY c.status,c.candidate_ID, p.first_name, p.last_name, p.email, c.cellphone_number, p.occupation ";
   
   //dynamically fetch rows according to status



        // Combined SQL query
        $sql = "
    SELECT 
        COUNT(*) as total, 
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_total,
        SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_total,
        SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted_total,
        SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined_total
    FROM 
        Candidate
";

        // Declare the variables
        $total_candidates;
        $pending_total;
        $in_progress_count;

        $accepted_count;
        $declined_count;
        $completed_count;

        // Execute the query
        if ($result = $conn->query($sql)) {
        // Fetch the result
        if ($row = $result->fetch_assoc()) {
            $total_candidates = $row['total'];
            $pending_total = $row['pending_total'];
            $in_progress_count = $row['in_progress_total'];
            $declined_count = $row['declined_total'];
            $accepted_count = $row['accepted_total'];
         }
        // Free the result set
        $result->free();
        }
   

 
$result_count = mysqli_query($conn, $sql_count);
while ($row_count = mysqli_fetch_assoc($result_count)) {
    if ($row_count['status'] == 'accepted') {
        $accepted_count = $row_count['count'];
    } elseif ($row_count['status'] == 'declined') {
        $declined_count = $row_count['count'];
    } elseif ($row_count['status'] == 'in_progress') {
        $in_progress_count = $row_count['count'];
    } elseif ($row_count['status'] == 'completed') {
        $completed_count = $row_count['count'];
    } 
    
}


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
    <!-- Add the spinner to the page-->
    <div id="spinner-overlay" class="spinner-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

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
                <form id="searchForm" action="vetting.php" method="POST">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" name="searchKey" id="searchKey" class="form-control" placeholder="View applicant table (Menu) or enter full name of a person to search" value="<?php echo $full_name; ?>">
                <button class="btn btn-primary">Search</button>
            </div>
            
            <div class="mt-2 mb-4">
                <label for="socialMedia" class="form-label">Please choose the social media platforms you'd like to search:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="linkedin" name="linkedin" value="linkedin">
                    <label class="form-check-label" for="linkedin">LinkedIn</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="facebook" name="facebook" value="facebook">
                    <label class="form-check-label" for="facebook">Facebook</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="instagram" name="instagram" value="instagram">
                    <label class="form-check-label" for="instagram">Instagram</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="twitter" name="twitter" value="twitter">
                    <label class="form-check-label" for="twitter">Twitter</label>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
    <div class="row">
        <div class="col">
            <div class="status-item">
                <button class="status-button accepted"><?php echo $accepted_count; ?></button>
                <span class="status-name">Accepted</span>
            </div>
        </div>
        <div class="col">
            <div class="status-item">
                <button class="status-button declined"><?php echo $declined_count; ?></button>
                <span class="status-name">Declined</span>
            </div>
        </div>
        <div class="col">
            <div class="status-item">
                <button class="status-button in-progress"><?php echo $pending_total; ?></button>
                <span class="status-name">In Progress</span>
            </div>
        </div>
        <div class="col">
            <div class="status-item">
                <button class="status-button completed"><?php echo $completed_count; ?></button>
                <span class="status-name">Vetted</span>
            </div>
        </div>
        <div class="col">
            <div class="status-item">
                <button class="status-button yellow-circle"><?php echo $total_candidates; ?></button>
                <span class="status-name">Total Candidates</span>
            </div>
        </div>
    </div>
</div>



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
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    include "db_conn.php";
                    $sql = "SELECT c.candidate_ID, p.first_name, p.last_name, p.email, c.cellphone_number, c.status,  p.occupation
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

                                <td>
                                <a href="view_applicant.php?identity_number=<?php echo $row["candidate_ID"]; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Vetting</a>
                                <a href="search.php?first_name=<?php echo $row["first_name"]; ?>&last_name=<?php echo $row["last_name"]; ?>&candidate_ID=<?php echo $row["candidate_ID"]; ?>" class="btn btn-info btn-sm"><i class="fas fa-search"></i> View</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="assets/js/searchpage.js"></script>
    <script>
        function populateSearchBar(firstName, lastName) {
            document.getElementById('searchKey').value = firstName + ' ' + lastName;
        }

        function changeVettingColor(btn) {
            var vettingButtons = document.querySelectorAll('.btn-vetting-default, .btn-vetting-clicked');
            vettingButtons.forEach(function(button) {
                button.classList.remove('btn-vetting-clicked');
                button.classList.add('btn-vetting-default');
            });
            btn.classList.remove('btn-vetting-default');
            btn.classList.add('btn-vetting-clicked');
        }
         //Add the functionality  for spinner be visible
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            var searchKey = document.getElementById('searchKey').value.trim();
            var linkedin = document.getElementById('linkedin').checked;
            var facebook = document.getElementById('facebook').checked;
            var instagram = document.getElementById('instagram').checked;
            var twitter = document.getElementById('twitter').checked;

            if (!searchKey) {
                alert('Please enter a full name to search.');
                event.preventDefault();
                return;
            }
            document.getElementById('spinner-overlay').style.display = 'flex';
        });
    </script>
</body>

</html>