<?php
include "db_conn.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Applicant Page</title>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        <div class="col text-center">
            <span>Applicant Table</span>
        </div>
        <div class="menu-bar ms-5">
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
    </nav>

    <div class="container">
        <div class="mb-4 d-flex align-items-right">
            <?php
            if (isset($_SESSION['username'])) {
                echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
            }
            ?>
        </div>
    </div>

    <div class="container">
        <?php
        if (isset($_GET["msg"])) {
            $msg = $_GET["msg"];
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        }
        ?>

        <!-- Search Form -->
        <form action="search.php" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="searchKey" class="form-control" placeholder="Search by name..." aria-label="Search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <table class="table table-hover text-center" id="applicantTable">
            <thead class="table-dark">
                <tr>
                    <th scope="col" data-column="applicant_id">Candidate ID</th>
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
                /*
                             for the candidate
                             $sql = "SELECT c.candidate_ID, p.first_name, p.last_name, p.email, c.cellphone_number,p.occupation 
                              FROM candidate c
                              JOIN person p ON r.person_ID = p.person_ID
                              WHERE p.first_name != 'system'";
                              $result = mysqli_query($conn, $sql);
                              $isLoggedIn = $_SESSION['recruiter_ID'];



                */



                $sql = "SELECT c.candidate_ID, p.first_name, p.last_name, p.email, c.cellphone_number, p.occupation
                FROM candidate c
                JOIN person p ON c.person_ID = p.person_ID";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                    <td><?php echo $row["candidate_ID"]; ?></td>
                            <td><?php echo $row["first_name"]; ?></td>
                            <td><?php echo $row["last_name"]; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td><?php echo $row["cellphone_number"]; ?></td>
                            <td><?php echo $row["occupation"]; ?></td>
                        <td>
                            <a href="view_applicant.php?identity_number=<?php echo $row["identity_number"] ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Vetting</a>
                            <a href="search.php?first_name=<?php echo $row["first_name"]; ?>&last_name=<?php echo $row["last_name"]; ?>&candidate_ID=<?php echo $row["candidate_ID"]; ?>" class="btn btn-info btn-sm"><i class="fas fa-search"></i> Search</a>
                            <a href="view_applicant.php?identity_number=<?php echo $row["identity_number"] ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <a href="search.php" class="btn btn-light me-2">Go to Search page</a>
            </div>
            <div>
                <a href="index.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Sorting Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const getCellValue = (row, index) => row.children[index].innerText || row.children[index].textContent;

            const comparer = (index, asc) => (a, b) => ((v1, v2) => 
                v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            )(getCellValue(asc ? a : b, index), getCellValue(asc ? b : a, index));

            document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
                const table = th.closest('table');
                Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
                    .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                    .forEach(tr => table.appendChild(tr));
            })));
        });
    </script>

</body>

</html>
