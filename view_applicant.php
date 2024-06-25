<?php
// Start the session
session_start();
include "db_conn.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Applicant</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmF/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="assets/css/header.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    nav {
      background-color: #007bff;
      color: white;
      padding: 10px 0;
    }

    nav .navbar-brand {
      font-size: 1.75rem;
      font-weight: bold;
      color: white;
      text-align: center;
      width: 100%;
    }

    .container {
      margin-top: 50px;
    }

    .profile-pic {
      max-width: 50%;
      padding-left: 19px;
      height: auto;
      border-radius: 90%;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .applicant-info,
    .application-details {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .table td {
      vertical-align: middle;
    }

    .btn-back {
      display: block;
      width: 100px;
      margin: 20px auto;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    View Applicant Details
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
      // Check if the username session is set
      if (isset($_SESSION['username'])) {
        // Display an active dot and the username
        echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
      }
      ?>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6 applicant-info">
          <!-- Applicant information -->
          <?php

          $identityNumber = $_GET['identity_number'];
          // $identityNumber = '0305042415086'; //Remove Me when done testing
          // Check if the identity number is 13 digits
          if (strlen($identityNumber) != 13) {
            die('Identity number should be 13 digits');
          }
          // Extract the date of birth
          $year = substr($identityNumber, 0, 2);
          $month = substr($identityNumber, 2, 2);
          $day = substr($identityNumber, 4, 2);

          // Convert year to full year format (assuming year 2000 and beyond for simplicity)
          $fullYear = ($year >= 00 && $year <= 99) ? '20' . $year : '19' . $year;

          // Create a DateTime object
          $date = DateTime::createFromFormat('Y-m-d', $fullYear . '-' . $month . '-' . $day);

          // Format the date to display month name
          $dateOfBirth = $date->format('d F Y');

          $sql = "SELECT p.first_name, p.last_name, c.identity_number, c.address, c.profile_picture
        FROM userandmediadb.person p, userandmediadb.candidate c
        WHERE c.identity_number = $identityNumber
        AND c.person_ID = p.person_ID";

          $result = mysqli_query($conn, $sql);
          $applicant = mysqli_fetch_assoc($result);

          // Extract the gender
          $genderDigit = substr($identityNumber, 6, 1);
          $gender = ($genderDigit < 5) ? 'Female' : 'Male';
          ?>
          <h4>Personal Details</h4>
          <img src="data:image/jpeg;base64,<?php echo base64_encode($applicant['profile_picture'] ?? ''); ?>" alt="Profile Picture" class="img-fluid mb-3" width="250" height="250">
          <table class="table table-bordered">
            <tr>
              <td><strong>First Name :</strong></td>
              <td><?php echo $applicant['first_name']; ?></td>
            </tr>
            <tr>
              <td><strong>Last Name:</strong></td>
              <td><?php echo $applicant['last_name'] ?? ''; ?></td>
            </tr>
            <tr>
              <td><strong>Identity Number:</strong></td>
              <td><?php echo  $identityNumber; ?></td>
            </tr>
            <tr>
              <td><strong>Date of Birth:</strong></td>
              <td><?php echo $dateOfBirth; ?></td>
            </tr>
            <tr>
              <td><strong>Gender:</strong></td>
              <td><?php echo $gender ?></td>
            </tr>
            <tr>
              <td><strong>Address:</strong></td>
              <td><?php echo $applicant['address'] ?></td>
            </tr>
          </table>
        </div>

        <div class="col-md-6 application-details">
          <?php
          $sql = "SELECT c.candidate_ID,c.status, c.application_date, c.captured_date, c.motivation
        FROM userandmediadb.candidate c
        WHERE c.identity_number = $identityNumber";
          $sresult = mysqli_query($conn, $sql);
          $applicants = mysqli_fetch_assoc($sresult);
          ?>

          <!-- Table with two columns -->
          <h4>Application Details</h4>
          <table class="table table-bordered">
            <tr>
              <td><strong>Status:</strong></td>
              <td><?php echo $applicants['status']; ?></td>
            </tr>
            <tr>
              <td><strong>Application Date:</strong></td>
              <td><?php echo $applicants['application_date'] ?? ''; ?></td>
            </tr>
            <tr>
              <td><strong>Motivation:</strong></td>
              <td><?php echo $applicants['motivation']; ?></td>
            </tr>
            <tr>
              <td><strong>Recruited By:</strong></td>
              <td>
                <?php
              $sqlRec = "Select p.first_name , p.last_name ,p.occupation 
              FROM person p ,candidate c 
              WHERE c.recruiter_ID = (select recruiter_ID FROM candidate WHERE identity_number =?)
              AND c.person_ID = p.person_ID";

                // Prepare and execute the statement
                if ($stmt = $conn->prepare($sqlRec)) {
                  $stmt->bind_param("s", $identityNumber);
                  $stmt->execute();
                  $stmt->bind_result($first_name, $last_name, $occupation);

                  // Fetch and display the result
                  if ($stmt->fetch()) {
                    echo htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . ' (' . htmlspecialchars($occupation) . ')';
                  } else {
                    echo "No recruiter found";
                  }

                  $stmt->close();
                } else {
                  echo "Error: Could not prepare SQL statement.";
                }
                ?>
              </td>

            </tr>
            <tr>
              <td><strong>Comment:</strong></td>
              <td><?php echo $applicant['comment'] ?? 'No Comment' ?></td>
            </tr>
            <tr>
              <td><strong>Media links:</strong></td>
              <td>
                <?php
                // SQL query to fetch social media links
                $sqlMedia = "SELECT f.profile_url as facebook, t.profile_url as twitter, l.profile_url as linkedin, i.profile_url as instagram
             FROM candidate c 
             JOIN socialmediaprofile s ON c.candidate_ID = s.candidate_ID 
             LEFT JOIN instagram_profile i ON s.socialMediaID = i.socialMediaID 
             LEFT JOIN twitter_profile t ON s.socialMediaID = t.socialMediaID 
             LEFT JOIN linkedin_profile l ON s.socialMediaID = l.socialMediaID 
             LEFT JOIN facebook_profile f ON s.socialMediaID = f.socialMediaID 
             WHERE c.identity_number = $identityNumber";

                $mediaresult = mysqli_query($conn, $sqlMedia);
                $social_media = mysqli_fetch_assoc($mediaresult);

                // Check if any social media links exist and are not empty
                if (!empty($social_media['facebook']) || !empty($social_media['twitter']) || !empty($social_media['linkedin']) || !empty($social_media['instagram'])) {
                ?>
                  <div class="card mb-3">
                    <div class="card-body text-center">
                      <ul class="list-unstyled mb-0">
                        <?php if (!empty($social_media['linkedin'])) { ?>
                          <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['linkedin']); ?>" target="_blank"><i class="fab fa-linkedin fa-lg me-2"></i> LinkedIn</a></li>
                        <?php } ?>
                        <?php if (!empty($social_media['twitter'])) { ?>
                          <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['twitter']); ?>" target="_blank"><i class="fab fa-twitter fa-lg me-2"></i> Twitter</a></li>
                        <?php } ?>
                        <?php if (!empty($social_media['instagram'])) { ?>
                          <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['instagram']); ?>" target="_blank"><i class="fab fa-instagram fa-lg me-2"></i> Instagram</a></li>
                        <?php } ?>
                        <?php if (!empty($social_media['facebook'])) { ?>
                          <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['facebook']); ?>" target="_blank"><i class="fab fa-facebook-f fa-lg me-2"></i> Facebook</a></li>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>
                <?php
                } else {
                  echo "No Available";
                }
                ?>
              </td>
            </tr>
            <tr>
              <td><strong>Capture Date:</strong></td>
              <td><?php echo $applicants['captured_date'] ?? 'Not Vetteded'; ?></td>
            </tr>
          </table>
          <div class="d-flex justify-content-between">
            <a href="search.php" class="btn btn-primary">Back</a>
            <a href="cand_Delete.php?candidate_ID=<?php echo $applicants["candidate_ID"] ?>" class="btn btn-danger">Delete</a>
            <a href="index.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS and jQuery (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>