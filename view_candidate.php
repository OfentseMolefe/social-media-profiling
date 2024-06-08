<?php
include "db_conn.php";
session_start();
//Get the Details using a applicant_id from the session
$applicant_id = $_SESSION['applicant_id'];
$applicant_id =17;
$hr_onDuty = $_SESSION['username'];
//Select from the Application table
$stmt = $conn->prepare("SELECT *
                        FROM applicant a WHERE a.applicant_id = ?");
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();
// Retrieve candidate details from URL parameters
$row = $result->fetch_assoc();
$candidate_ID = $row["applicant_id"];
$first_name = $row["first_name"];
$last_name = $row["last_name"];
$email = $row["email"];
$cell_no = $row["phone"];
$application_position = $row["application_position"];

require 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

function send_email($to, $subject, $body)
{
    // Configure the Symfony Mailer with Gmail SMTP
    $transport = Transport::fromDsn('smtp://swp316dproject@gmail.com:uobgvdxwwevaiakn@smtp.gmail.com:587?encryption=tls');
    $mailer = new Mailer($transport);

    $email = (new Email())
        ->from('swp316dproject@gmail.com')
        ->to($to)
        ->subject($subject)
        ->html($body);

    // JavaScript to show a confirmation dialog
    $confirmationScript = "<script>
        var confirmation = confirm('Are you sure you want to send this email?');
        if (confirmation) {
            window.location.href = 'search.php';
        }
    </script>";

    try {
        // Send the email
        $mailer->send($email);

        // Return success message with the confirmation script
        return 'Message has been sent' . $confirmationScript;
    } catch (Exception $e) {
        // Return error message with the confirmation script
        return "Message could not be sent. Mailer Error: {$e->getMessage()}" . $confirmationScript;
    }
}

// Check if "ACCEPT" button is clicked
if (isset($_POST['accept'])) {
    $interview_date = $_POST['interview_date']; // highlighted cause still waiting for a way how can a recruiter enter 
    $interview_time = $_POST['interview_time']; // this details manually
    // $interview_location = $_POST['interview_location'];
    $subject = 'Application Accepted';

    $body = "Dear $first_name $last_name,<br><br>
    I hope this email finds you well.<br><br>
I am writing to invite you for an interview for the position of <b>$application_position</b> at Tshwane University of Technology. We were impressed by your application and believe that you would be a great fit for our team.<br><br>

The interview will be conducted in person and will take approximately <b>30 - 45 minutes</b>. We will discuss your work experience, skills, and qualifications in-depth and provide you with more information about the position and our company.<br><br>

<strong>Interview Details:</strong><br>
Date: $interview_date<br>
Time: $interview_time AM<br>
Location:
<ul style='list-style-type: none; padding-left: 20px;'>
    <li>Building 20-212</li>
    <li>Block K</li>
    <li>2 Aubrey Matlakala St</li>
    <li>Soshanguve - K</li>
    <li>Soshanguve</li>
    <li>0152</li>
</ul>
<br>
Thank you for your interest in our company, and we look forward to meeting you soon.<br><br>

Sincerely,<br>
$hr_onDuty ( From HR Department) ";

    echo send_email($email, $subject, $body);

    //Store the comment  and update details into a applicant database


}

// Check if "DECLINE" button is clicked
if (isset($_POST['decline'])) {
    $subject = 'Application Declined';
    $body = "Dear $first_name $last_name,<br><br>
    Thank you for your interest in the position at Jokers Organization. After careful consideration, we regret to inform you that we have decided to move forward with other candidates who more closely match our needs at this time.<br><br>
    We were impressed with your qualifications and encourage you to apply for future openings that align with your skills and experiences.<br><br>
    We wish you all the best in your job search and future professional endeavors.<br><br>
    Regards,<br>
    Jokers Organization";
    echo send_email($email, $subject, $body);

    //update the candidate and store the comment

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
            height: 90vh;
            justify-content: center;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 20vh;
        }

        .table-wrapper {
            max-height: 400px;
            overflow-y: auto;
        }

        h1 {
            color: SlateBlue;
            font-size: 3rem;
            text-align: center;
            font-style: oblique;
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

    <!-- Add the spinner to the page -->
    <div id="spinner-overlay" class="spinner-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-3" style="background-color: #00ff5573;">
        Response to a Candidate
    </nav>
    <div class="container d-flex align-items-right">
        <?php
        if (isset($_SESSION['username'])) {
            // Display an active dot and the username
            echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
        }
        ?>
    </div>
    <div class="container mt-1">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                       <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h2 class="mt-3"><?php echo htmlspecialchars($first_name); ?> <?php echo htmlspecialchars($last_name); ?></h2>
                        <p class="mb-2">Email: <?php echo htmlspecialchars($email); ?></p>
                        <p class="mb-2">Cell Number: <?php echo htmlspecialchars($cell_no); ?></p>
                        <div class="d-flex justify-content-center mt-3">

                            <form id="submitEmail" method="post">
                                <div class="form-group">
                                    <textarea class="form-control mb-3" id="recruiterComment" placeholder="Leave a comment" name="recruiterComment" rows="3" style="border: 1px solid #ced4da; padding: .375rem .75rem;"></textarea>
                                    <div class="row mb-4">
                                        <label class="col-12">
                                            <u><strong>Interview Details</strong></u>
                                        </label>
                                        <div class="col-6">
                                            <label for="interview_date" class="form-label">Date:</label>
                                            <input type="date" id="interview_date" name="interview_date" class="form-control">
                                        </div>
                                        <div class="col-6">
                                            <label for="interview_time" class="form-label">Time:</label>
                                            <input type="time" id="interview_time" name="interview_time" class="form-control">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-5" name="accept">ACCEPT</button>
                                    <button type="submit" name="decline" class="btn btn-outline-primary">DECLINE</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- URLs 
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="<?php echo htmlspecialchars($linkedin_url); ?>" target="_blank"><i class="fab fa-linkedin fa-lg me-2"></i> LinkedIn</a></li>
                            <li class="mb-2"><a href="<?php echo htmlspecialchars($twitter_url); ?>" target="_blank"><i class="fab fa-twitter fa-lg me-2"></i> Twitter</a></li>
                            <li class="mb-2"><a href="<?php echo htmlspecialchars($instagram_url); ?>" target="_blank"><i class="fab fa-instagram fa-lg me-2"></i> Instagram</a></li>
                            <li class="mb-2"><a href="<?php echo htmlspecialchars($facebook_url); ?>" target="_blank"><i class="fab fa-facebook-f fa-lg me-2"></i> Facebook</a></li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="candidate_table.php" class="btn btn-primary">Back</a>
                    <a href="index.php" class="btn btn-danger">Logout</a>
                </div> -->
            </div>
        </div>
    </div>
    <script>
        document.getElementById('submitEmail').addEventListener('submit', function () {
            document.getElementById('spinner-overlay').style.display = 'flex';
        });
    </script>
</body>

</html>
