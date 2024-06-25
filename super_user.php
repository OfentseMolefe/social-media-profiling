<?php
include "db_conn.php";
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Navbar style */
        .navbar {
            background-color: #00ff5573;
            color: #fff;
            border-bottom: 1px solid #666;
        }

        .navbar-brand {
            color: #333;
            font-weight: bold;
            font-size: 24px;
        }

        .navbar-toggler-icon {
            background-color: #fff;
        }

        .navbar-nav .nav-link {
            color: #333;
        }

        .navbar-nav .nav-link:hover {
            color: #00ff55;
        }

        /* Sidebar style */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 50px;
            margin-top: 4%;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        /* Page content style */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .card:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        footer {
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        <div class="container">
            <a class="navbar-brand" href="super_user.php">System Details</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="applicants.php"><i class="fas fa-users"></i> View Applicants</a>
        <a href="candidate_table.php"><i class="fas fa-user"></i> Prospective Candidate(s)</a>
        <a class="dropdown-item" href="search.php"><i  class="fas fa-search"></i> Go to Search Page</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Page content -->
    <div class="content">
    <div class="row mt-4">
            <!-- Example card for a booking -->
            <div class="col-md-6">
                <a href="recruiters.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-list-alt"></i> Employees Details</h5>
                          
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="all_applicantions.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-list-alt"></i> View All Applications</h5>
                           
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="application_accepted.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-list-alt"></i> Accepted Applicants</h5>
                           
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="applicant_declined.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-list-alt"></i> Declined Applicants</h5>
                          
                        </div>
                    </div>
                </a>
            </div>
        
    </div>


    <!-- Bootstrap JS and Font Awesome JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    <!-- Sorting Script
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
