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

    <title>Employee Page</title>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        <div class="col text-center">
            <span>Employee Table</span>
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

     <!-- Search Form 
        <form action="search.php" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="searchKey" class="form-control" placeholder="Search by name..." aria-label="Search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
     -->   

        <table class="table table-hover text-center" id="applicantTable">
            <thead class="table-dark">
                <tr>
                    <th scope="col" data-column="recruiter_ID">Employee ID</th>
                    <th scope="col" data-column="first_name">First Name</th>
                    <th scope="col" data-column="last_name">Last Name</th>
                    <th scope="col" data-column="email">Email</th>
                    <th scope="col" data-column="application_position">Position</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT r.recruiter_ID, p.first_name, p.last_name, p.email, r.username, p.occupation
                FROM recruiter r
                JOIN person p ON r.person_ID = p.person_ID";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr ondblclick="redirectToSummary(<?php echo $row['recruiter_ID']; ?>)">
                        <td><?php echo $row["recruiter_ID"]; ?></td>
                        <td><?php echo $row["first_name"]; ?></td>
                        <td><?php echo $row["last_name"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["occupation"]; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <a href="super_user.php" class="btn btn-light me-2">Back</a>
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

       /* document.addEventListener('DOMContentLoaded', () => {
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
        */

        function redirectToSummary(recruiterId) {
            fetch('set_recruiter_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ recruiter_ID: recruiterId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'recruiter_summary.php';
                } else {
                    alert('Failed to set session');
                }
            });
        }
    </script>

</body>

</html>
