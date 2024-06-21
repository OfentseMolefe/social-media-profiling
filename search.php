<?php
// Start the session
session_start();
include "db_conn.php";

// Retrieve the first name and last name from the URL if they are set and add to the session
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmF/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/searchpage.css">
    <style>
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
                    <div class="mt-2">
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
            <!-- Add the Table for applicants -->
            <div class="table-wrapper mt-2">
                <table class="table table-hover text-center" id="applicantTable">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" data-column="applicant_id">Applicant ID</th>
                            <th scope="col" data-column="first_name">First Name</th>
                            <th scope="col" data-column="last_name">Last Name</th>
                            <th scope="col" data-column="email">Email</th>
                            <th scope="col" data-column="phone">Phone</th>
                            <th scope="col" data-column="application_position">Position</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `applicant`";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr onclick="populateSearchBar('<?php echo $row["first_name"] ?>', '<?php echo $row["last_name"] ?>')">
                                <td><?php echo $row["applicant_id"] ?></td>
                                <td><?php echo $row["first_name"] ?></td>
                                <td><?php echo $row["last_name"] ?></td>
                                <td><?php echo $row["email"] ?></td>
                                <td><?php echo $row["phone"] ?></td>
                                <td><?php echo $row["application_position"] ?></td>
                                <td>
                                    <a href="view_applicant.php?identity_number=<?php echo $row["applicant_id"] ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Vetting</a>
                                    <a href="search.php?first_name=<?php echo $row["first_name"]; ?>&last_name=<?php echo $row["last_name"]; ?>&applicant_id=<?php echo $row["applicant_id"]; ?>" class="btn btn-info btn-sm"><i class="fas fa-search"></i> Search</a>
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

            if (!linkedin && !facebook && !instagram && !twitter) {
                alert('Please select at least one social media platform to search.');
                event.preventDefault();
                return;
            }

            document.getElementById('spinner-overlay').style.display = 'flex';
        });
    </script>
</body>

</html>
