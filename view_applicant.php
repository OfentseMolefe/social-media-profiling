<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Applicant</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    nav {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 48px;
      background-color: blue;
    }

    nav span {
      color: black;
      text-decoration: none;
      font-size: 36px;
      font-weight: normal;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-light justify-content-center fs-3 mb-5n" style="background-color: #00ff5573;">
    <div class="text-center">
      <span style="font-size: 1.5rem;">View Applicant</span>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <?php
        include "db_conn.php";
        session_start();

        $identityNumber = $_GET['identity_number'];

        // Check if the identity number is 13 digits
        if (strlen($identityNumber) != 13) {
          die('Identity number should be 13 digits');
        }

        // Extract the date of birth
        $year = substr($identityNumber, 0, 2);
        $month = substr($identityNumber, 2, 2);
        $day = substr($identityNumber, 4, 2);
        $dateOfBirth = $day . '/' . $month . '/' . $year;


        $sql = "SELECT first_name, last_name,address  FROM `applicant` WHERE identity_number = $identityNumber";
        $result = mysqli_query($conn, $sql);
        $applicant = mysqli_fetch_assoc($result);


        // Extract the gender
        $genderDigit = substr($identityNumber, 6, 1);
        $gender = ($genderDigit < 5) ? 'Female' : 'Male';

        // Display the profile icon based on gender
        echo '<img src="' . ($gender == 'Female' ? 'assets/female-profile-icon.png' : 'assets/male-profile-icon.png') . '" alt="Profile Icon" class="img-fluid mb-3" width="250" height="250">';
      
        // Display the applicant's first and last name
        echo '<div class="mb-3">First Name: ' . $applicant['first_name'] . '</div>';
        echo '<div class="mb-3">Last Name: ' . $applicant['last_name'] . '</div>';


        // Display the date of birth and gender
        echo '<div class="mb-3">Identity Number: ' . $identityNumber . '</div>';
        echo '<div class="mb-3">Date of Birth: ' . $dateOfBirth . '</div>';
        echo '<div class="mb-3">Gender: ' . $gender . '</div>';



        echo '<div class="mb-3">Address: ' . $applicant['address'] . '</div>';

        ?>
      </div>
    </div>
    <a href="applicants.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Back</a>
  </div>

  <!-- Bootstrap JS and jQuery (Optional) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>