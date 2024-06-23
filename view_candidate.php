<?php
include "db_conn.php";
session_start();

// Check if the applicant_id session variable is set
if (!isset($_SESSION['applicant_id'])) {
    die("Applicant ID not found in session.");
}
$socialProfiles;
//get the links
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['social_profiles']) && is_array($_POST['social_profiles'])) {
        $_SESSION['social_profiles'] = $_POST['social_profiles'];
        // Get the array of social profiles from the form data
        $socialProfiles = $_POST['social_profiles'];
    }
}

$applicant_id = $_SESSION['applicant_id'];
$hr_onDuty = $_SESSION['username'];
$recruiterID = $_SESSION['recruiterID'];
//$applicant_id = 22;
// Retrieve candidate details from the database
$stmt = $conn->prepare("SELECT c.candidate_ID, p.first_name, p.last_name, p.email, c.cellphone_number, p.occupation
                        FROM candidate c 
                        JOIN person p ON p.person_ID = c.person_ID 
                        WHERE c.candidate_ID = ?");
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Applicant details not found.");
}

$candidate_ID = $row["candidate_ID"];
$first_name = $row["first_name"];
$last_name = $row["last_name"];
$email = $row["email"];
$cell_no = $row["cellphone_number"];
$application_position = $row["occupation"];

require 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

function send_email($to, $subject, $body)
{
    $transport = Transport::fromDsn('smtp://swp316dproject@gmail.com:uobgvdxwwevaiakn@smtp.gmail.com:587?encryption=tls');
    $mailer = new Mailer($transport);

    $email = (new Email())
        ->from('swp316dproject@gmail.com')
        ->to($to)
        ->subject($subject)
        ->html($body);

    try {
        $mailer->send($email);
        return 'Message has been sent';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$e->getMessage()}";
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   

    // Check if "ACCEPT" button is clicked
    if (isset($_GET['accept'])) {
        $status = 'Accepted';
        $subject = 'Application Accepted';
        $interview_date = $_POST['interview_date'] ?? '';
        $interview_time = $_POST['interview_time'] ?? '';
        $comment = $_POST['recruiterComment'] ?? 'No comment';
        $body = "Dear $first_name $last_name,<br><br>
        I hope this email finds you well.<br><br>
        I am writing to invite you for an interview for the position of <b>$application_position</b> at Tshwane University of Technology. We were impressed by your application and believe that you would be a great fit for our team.<br><br>
        The interview will be conducted in person and will take approximately <b>30 - 45 minutes</b>. We will discuss your work experience, skills, and qualifications in-depth and provide you with more information about the position and our company.<br><br>
        <strong>Interview Details:</strong><br>
        Date: $interview_date<br>
        Time: $interview_time<br>
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
        $hr_onDuty (From HR Department)";

        echo send_email($email, $subject, $body);

        // Update candidate table for "ACCEPT"
        $sqlCandidate = "UPDATE candidate 
                         SET captured_date = NOW(), status = ?, recruiter_ID = ?, comment = ?
                         WHERE candidate_ID = ?";
        $stmtCandidate = $conn->prepare($sqlCandidate);
        $stmtCandidate->bind_param("sisi", $status, $recruiterID, $comment, $applicant_id);
        $stmtCandidate->execute();
        $stmtCandidate->close();

        // Add the links to the database
        $sql2 = "INSERT INTO SocialMediaProfile (candidate_ID, recruiter_ID) VALUES ('$applicant_id', '$recruiterID')";

        if (mysqli_query($conn, $sql2)) {
            $socialMediaID = mysqli_insert_id($conn);
            $_SESSION['socialMediaID'] = $socialMediaID;
            echo "New record inserted into SocialMediaProfile successfully. Social Media ID: $socialMediaID<br>";
        } else {
            echo "Error inserting into SocialMediaProfile: " . mysqli_error($conn) . "<br>";
        }

        // Process each social profile
        //get the links from the session
        $socialProfiles = $_SESSION['social_profiles'];

        foreach ($socialProfiles as $profileData) {
            $profileParts = explode('|', $profileData);
            $platform = $profileParts[0];
            $username = $profileParts[1];
            $profileURL = $profileParts[2];

            switch ($platform) {
                case 'facebook':
                    $tableName = 'facebook_profile';
                    break;
                case 'instagram':
                    $tableName = 'instagram_profile';
                    break;
                case 'twitter':
                    $tableName = 'twitter_profile';
                    break;
                case 'linkedin':
                    $tableName = 'linkedin_profile';
                    break;
                default:
                    echo "Unknown platform: $platform<br>";
                    break;
            }

            $sql = "INSERT INTO $tableName (socialMediaID, user_name, profile_url) VALUES ('$socialMediaID', '$username', '$profileURL')";
            if (mysqli_query($conn, $sql)) {
                echo "Profile captured successfully: $username ($platform)<br>";
            } else {
                echo "Error inserting into $tableName: " . mysqli_error($conn) . "<br>";
            }
        }
    }

    // Check if "DECLINE" button is clicked
    if (isset($_GET['decline'])) {
        $status = 'Declined';
        $subject = 'Application Declined';

        $body = "Dear $first_name $last_name,<br><br>
        Thank you for your interest in the position at Jokers Organization. After careful consideration, we regret to inform you that we have decided to move forward with other candidates who more closely match our needs at this time.<br><br>
        We were impressed with your qualifications and encourage you to apply for future openings that align with your skills and experiences.<br><br>
        We wish you all the best in your job search and future professional endeavors.<br><br>
        Regards,<br>
        Jokers Organization";

        echo send_email($email, $subject, $body);

        $sqlCandidate = "UPDATE candidate 
                         SET captured_date = NOW(), status = ?, recruiter_ID = ?, comment = ?
                         WHERE candidate_ID = ?";
        $stmtCandidate = $conn->prepare($sqlCandidate);
        $stmtCandidate->bind_param("sisi", $status, $recruiterID, $comment, $applicant_id);
        $stmtCandidate->execute();
        $stmtCandidate->close();
    }

    //redirect back to search page
    
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

                            <form id="submitEmail" method="get">
                                <div class="form-group">
                                    <textarea class="form-control mb-3" name="recruiterComment" id="recruiterComment" placeholder="Leave a comment" name="recruiterComment" rows="3" style="border: 1px solid #ced4da; padding: .375rem .75rem;"></textarea>
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
                                    <button type="submit" name="decline" class="btn btn-danger">DECLINE</button>
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
        document.getElementById('submitEmail').addEventListener('submit', function() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        });
    </script>
</body>

</html>