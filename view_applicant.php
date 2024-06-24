<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Applicant</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
  <nav class="navbar navbar-light">
    <div class="navbar-brand">View Applicant</div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-6 applicant-info">
        <!-- Applicant information -->
        <?php
        include "db_conn.php";
        session_start();

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
        <img src="data:image/jpeg;base64,<?php echo base64_encode($applicant['profile_picture']??''); ?>" alt="Profile Picture" class="img-fluid mb-3" width="250" height="250">
        <table class="table table-bordered">
           <tr>
            <td><strong>First Name :</strong></td>
            <td><?php echo $applicant['first_name']; ?></td>
          </tr>
          <tr>
            <td><strong>Last Name:</strong></td>
            <td><?php echo $applicants['last_name'] ?? ''; ?></td>
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
            <td><?php echo $gender?></td>
          </tr>
          <tr>
          <td><strong>Address:</strong></td>
          <td><?php echo $applicant['address']?></td>
          </tr>
        </table>
      </div>

      <div class="col-md-6 application-details">
        <?php
        $sql = "SELECT c.status, c.application_date, c.captured_date, c.motivation
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
            <td><?php echo $applicants['comment'] ?? 'No Comment' ?></td>
          </tr>
          <tr>
            <td><strong>Comment:</strong></td>
            <td><?php echo $applicants['comment'] ?? 'No Comment' ?></td>
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

              // Check if any social media links exist
              if ($social_media['facebook'] || $social_media['twitter'] || $social_media['linkedin'] || $social_media['instagram']) {
              ?>
                <div class="card mb-3">
                  <div class="card-body text-center">
                    <ul class="list-unstyled mb-0">
                      <?php if ($social_media['linkedin']) { ?>
                        <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['linkedin']); ?>" target="_blank"><i class="fab fa-linkedin fa-lg me-2"></i> LinkedIn</a></li>
                      <?php } ?>
                      <?php if ($social_media['twitter']) { ?>
                        <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['twitter']); ?>" target="_blank"><i class="fab fa-twitter fa-lg me-2"></i> Twitter</a></li>
                      <?php } ?>
                      <?php if ($social_media['instagram']) { ?>
                        <li class="mb-2"><a href="<?php echo htmlspecialchars($social_media['instagram']); ?>" target="_blank"><i class="fab fa-instagram fa-lg me-2"></i> Instagram</a></li>
                      <?php } ?>
                      <?php if ($social_media['facebook']) { ?>
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
          <a href="candidate_table.php" class="btn btn-primary">Back</a>
          <a href="cand_Delete.php" class="btn btn-danger">Delete</a>
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