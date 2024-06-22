<?php
// Start the session
session_start();
include "db_conn.php"; // Include your database connection file

// Retrieve values from the session
$first_name = isset($_SESSION['merged_first_middle_name']) ? $_SESSION['merged_first_middle_name'] : '';
$last_name = isset($_SESSION['merged_last_name']) ? $_SESSION['merged_last_name'] : '';

// Prepare the SQL statement using a prepared statement
$sql = "SELECT email, phone, applicant_id FROM applicant WHERE last_name = ? AND first_name = ?";
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "ss", $last_name, $first_name);

// Execute the statement
mysqli_stmt_execute($stmt);

// Store the result
$result = mysqli_stmt_get_result($stmt);

// Check if a row is returned
if ($applicant = mysqli_fetch_assoc($result)) {
    // Fetch email and phone if the applicant exists
    $email = $applicant['email'];
    $phone = $applicant['phone'];
    $applicantID = $applicant['applicant_id']; // Fetching applicant_id here
    
} else {
    // If the applicant does not exist, set email and phone to empty strings
    $email = '';
    $phone = '';
    $applicantID = ''; // Set applicantID to empty string if applicant does not exist
}

// Close the statement
mysqli_stmt_close($stmt);

$recruiterID = isset($_SESSION['recruiterID']) ? $_SESSION['recruiterID'] : '';

// Debug: Output session data
echo "Session Data:<br>";
var_dump($_SESSION);
echo "<br><br>";

// Use the retrieved values as needed
echo "First Name: $first_name <br>";
echo "Last Name: $last_name <br>";
echo "Email: $email <br>";
echo "Phone: $phone <br>";
echo "Recruiter ID: $recruiterID <br>";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['social_profiles'])) {
    //INSERT data into candidate
    $sql1 = "INSERT INTO Candidate (applicant_id, recruiter_ID) 
             VALUES ('$applicantID','$recruiterID')";

    if (mysqli_query($conn, $sql1)) {
        $candidateId = mysqli_insert_id($conn);
        $_SESSION['candidate_id'] = $candidateId;
        echo "New record inserted into Candidate successfully. Candidate ID: $candidateId<br>";
    } else {
        echo "Error inserting into Candidate: " . mysqli_error($conn) . "<br>";
    }

    //create socialmediaprofile
    $sql2 = "INSERT INTO SocialMediaProfile (candidate_ID, recruiter_ID) VALUES ('$candidateId', '$recruiterID')";

    if (mysqli_query($conn, $sql2)) {
        $socialMediaID = mysqli_insert_id($conn);
        $_SESSION['socialMediaID'] = $socialMediaID;
        echo "New record inserted into SocialMediaProfile successfully. Social Media ID: $socialMediaID<br>";
    } else {
        echo "Error inserting into SocialMediaProfile: " . mysqli_error($conn) . "<br>";
    }

 // Get the array of social profiles from the form data
 $socialProfiles = $_POST['social_profiles'];

 // Process each social profile
 foreach ($socialProfiles as $profileData) {
     // Split the profile data using the delimiter '|'
     $profileParts = explode('|', $profileData);
     
     // Extract platform, username, and profile URL
     $platform = $profileParts[0];
     $username = $profileParts[1];
     $profileURL = $profileParts[2];
     
     // Example: Insert the captured profile into the database based on platform
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
             break; // Skip to the next iteration
     }
     
     // Insert into the respective table
     $sql = "INSERT INTO $tableName (socialMediaID,user_name, profile_url) VALUES ('$socialMediaID','$username','$profileURL')";
     if (mysqli_query($conn, $sql)) {
         echo "Profile captured successfully: $username ($platform)<br>";
     } else {
         echo "Error inserting into $tableName: " . mysqli_error($conn) . "<br>";
     }
 }
 // Take to the report page 
 header("Location: report.php");

} else {
 // Redirect to the search page if the form is not submitted
 header("Location: search.php");
 exit();
}

// Close the database connection
mysqli_close($conn);

?>
