<?php
include "db_conn.php";
// Start the session before getting the variables
session_start();
// Retrieve candidate details from URL parameters
$candidate_ID = $_GET["candidate_ID"];
$first_name = $_GET["first_name"];
$last_name = $_GET["last_name"];
$email = $_GET["email"];
$cell_no = $_GET["cell_no"];

// put the select statement to retrieve links from the socialMediaProfile for that candidate_ID
$sql = "SELECT i.profile_url AS instagram_url, t.profile_url AS twitter_url, l.profile_url AS linkedin_url, f.profile_url AS facebook_url
       FROM candidate c
       INNER JOIN socialmediaprofile s ON c.candidate_id = s.candidate_id
       LEFT JOIN twitter_profile t ON s.socialMediaID = t.socialMediaID
       LEFT JOIN linkedin_profile l ON s.socialMediaID = l.socialMediaID
       LEFT JOIN facebook_profile f ON s.socialMediaID = f.socialMediaID
       LEFT JOIN instagram_profile i ON s.socialMediaID = i.socialMediaID
       WHERE c.candidate_id = $candidate_ID";
$result = mysqli_query($conn, $sql);

// Fetch the social media profile URLs
$row = mysqli_fetch_assoc($result);
$instagram_url = $row['instagram_url'];
$twitter_url = $row['twitter_url'];
$linkedin_url = $row['linkedin_url'];
$facebook_url = $row['facebook_url'];

// Check if "ACCEPT" button is clicked
if(isset($_POST['accept'])) {
    // Send email
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://mail-sender-api1.p.rapidapi.com/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'sendto' => $email,
            'name' => 'Jokers Organization', // Replace with your company name
            'replyTo' => 'molefeofentse@hotmail.com', // Replace with your email
            'ishtml' => 'false',
            'title' => 'Application Accepted',
            'body' => "Dear $first_name $last_name,<br><br>Your application has been accepted.<br><br>Regards,<br>Jokers Organization" // Customize the email message
        ]),
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: mail-sender-api1.p.rapidapi.com",
            "X-RapidAPI-Key: f389caef1amsh56eb102cb5c5798p1862bfjsn2987ea5afeee",
            "content-type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }

    // Additional logic for accepting the application
}

// Check if "DECLINE" button is clicked
if(isset($_POST['decline'])) {
    // Send email
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://mail-sender-api1.p.rapidapi.com/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'sendto' => $email,
            'name' => 'Your Company', // Replace with your company name
            'replyTo' => 'molefeofentse@hotmail.com', // Replace with your email
            'ishtml' => 'false',
            'title' => 'Application Declined',
            'body' => "Dear $first_name $last_name,<br><br>We regret to inform you that your application has been declined.<br><br>Regards,<br>Jokers Organization" // Customize the email message
        ]),
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: mail-sender-api1.p.rapidapi.com",
            "X-RapidAPI-Key: f389caef1amsh56eb102cb5c5798p1862bfjsn2987ea5afeee",
            "content-type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-image: url('assets/selected candidate2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            height: 100vh;
            justify-content: center;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;

        }

        h1 {
            color: SlateBlue;
            font-size: 3rem;
            text-align: center;
            font-style: oblique;
        }

        .back-button {
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-3" style="background-color: #00ff5573;">
        Candidate Details
    </nav>
    <div class="container d-flex align-items-right">

        <?php

        if (isset($_SESSION['username'])) {
            // Display an active dot and the username
            echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
        }
        ?>
    </div>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h2 class="mt-3"> <?php echo $first_name; ?> <?php echo $last_name; ?></h2>
                        <p class="mb-2">Email: <?php echo $email; ?></p>
                        <p class="mb-2">Cell Number: <?php echo $cell_no; ?></p>
                        <div class="d-flex justify-content-center mt-3">
                            <form method="post">
                                <button type="submit" class="btn btn-primary me-2" name="accept">ACCEPT</button>
                                <button type="submit" name="decline" class="btn btn-outline-primary">DECLINE</button>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- URLs -->
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="<?php echo $linkedin_url; ?>" target="_blank"><i class="fab fa-linkedin fa-lg me-2"></i> LinkedIn</a></li>
                            <li class="mb-2"><a href="<?php echo $twitter_url; ?>" target="_blank"><i class="fab fa-twitter fa-lg me-2"></i> Twitter</a></li>
                            <li class="mb-2"><a href="<?php echo $instagram_url; ?>" target="_blank"><i class="fab fa-instagram fa-lg me-2"></i> Instagram</a></li>
                            <li class="mb-2"><a href="<?php echo $facebook_url; ?>" target="_blank"><i class="fab fa-facebook-f fa-lg me-2"></i> Facebook</a></li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="candidate_table.php" class="btn btn-primary">Back</a>
                    <a href="index.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>