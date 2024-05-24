<?php
// Start the session
session_start();
include "db_conn.php";

$searchKey = isset($_POST['searchKey']) ? $_POST['searchKey'] : '';
// Split the full name into an array of parts using a space (' ') as the delimiter
$names = explode(' ', $searchKey);
// Determine the number of parts in the name
$name_parts_count = count($names);

// Initialize variables for first name, middle name, and last name
$first_name = '';
$middle_name = '';
$last_name = '';

// Assign values based on the number of parts in the name
if ($name_parts_count >= 1) {
    $first_name = $names[0];
}
if ($name_parts_count >= 2) {
    // If there are at least two parts, the last part is considered the last name
    $last_name = $names[$name_parts_count - 1];
}
if ($name_parts_count >= 3) {
    // If there are at least three parts, the parts in between are considered the middle name
    $middle_name_parts = array_slice($names, 1, $name_parts_count - 2);
    $middle_name = implode(' ', $middle_name_parts);
}

// Merge the first name and middle name together with a space between them
$merged_first_middle_name = trim($first_name . ' ' . $middle_name);
// Concatenate first name and last name with a space between them
$full_name = $merged_first_middle_name . '%20' . $last_name;

// check if the person exist
$sql = "SELECT * FROM applicant WHERE first_name = '$merged_first_middle_name' AND last_name = '$last_name'";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $applicantID = $row['applicant_id'];
    $sql2 = "SELECT * FROM candidate WHERE applicant_id = '$applicantID'";

    //add variables into a session 
    $_SESSION['applicant_id'] = $applicantID;
   
   // $_SESSION['candidate_id'] = $row['candidate_id']; 
  //  $_SESSION['candidate_id'] = $row['candidate_id']; 
    $result2 = mysqli_query($conn, $sql2);

    if (mysqli_num_rows($result2) > 0) {
         
        echo "<script>alert('Candidate already exist in the database.Please view candidate page in menu '); window.location='search.php';</script>";
    }
}
//push the name to the session
$_SESSION['merged_first_middle_name'] = $merged_first_middle_name;
$_SESSION['merged_last_name'] = $last_name;

// Initialize an array to store checked social networks
$checkedNetworks = [];

// Check if each checkbox is checked, and if so, add it to the array
if (isset($_POST['linkedin'])) {
    $checkedNetworks[] = 'linkedin';
}
if (isset($_POST['facebook'])) {
    $checkedNetworks[] = 'facebook';
}
if (isset($_POST['instagram'])) {
    $checkedNetworks[] = 'instagram';
}
if (isset($_POST['twitter'])) {
    $checkedNetworks[] = 'twitter';
}

$_SESSION['profiles']= $checkedNetworks;
// Construct the query string for social networks
$socialNetworksQuery = implode('%2C', $checkedNetworks);

// Construct the URL for the RapidAPI endpoint
$rapidApiUrl = "https://social-links-search.p.rapidapi.com/search-social-links?query=" . $full_name . "&social_networks=" . $socialNetworksQuery;

// Set up RapidAPI request headers
$rapidApiHeaders = [
    "X-RapidAPI-Host: social-links-search.p.rapidapi.com",
    "X-RapidAPI-Key: your here Key"
];

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => $rapidApiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => $rapidApiHeaders,
]);

// Execute cURL request and store the response
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    echo "cURL Error: " . curl_error($curl);
    exit(); // Terminate script if there's an error
}

// Close cURL session
curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);

// Check if the response status is OK
if ($data['status'] === "OK") {
    // Proceed with displaying the data
} else {
    echo "No social media profiles found for the given query.";
    exit(); // Terminate script if there are no profiles found
}



?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Profiles results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .social-icon {
            width: 60px;
            height: 60px;
        }

        .table {
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-3" style="background-color: #00ff5573;">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 ">Social Media Profiles</span>
            <div>
                <span class="navbar-text me-2">
                    <?php echo "Search Key: " . $searchKey . ""; ?>
                </span>
                <span class="navbar-text">Logged In:
                    <?php

                    if (isset($_SESSION['username'])) {
                        // Display an active dot and the username
                        echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
                    }
                    ?></span>
            </div>
        </div>
    </nav>

    <?php
    if ($data['status'] === "OK") {
    ?> <div class="mb-4  container">
            <form id="captureForm" action="capture.php" method="POST">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Media Icon</th>
                                <th>Username</th>
                                <th>URL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $platform => $profiles) {
                                if (count($profiles) > 0) {
                                    $profile = $profiles[0]; // Selecting the first profile
                                    $username = substr($profile, strrpos($profile, '/') + 1); // Assuming username comes after the last '/'
                            ?>
                                    <tr>
                                        <td><img src="assets/icons/<?php echo strtolower($platform) ?>.png" alt="<?php echo ucfirst($platform) ?>" class="social-icon" style="max-width: 90px;"></td>
                                        <td><?php echo $username; ?></td>
                                        <td><a href="<?php echo $profile ?>" target="_blank"><?php echo $profile ?></a></td>
                                        <td><input type="hidden" name="social_profiles[]" value="<?php echo $platform . '|' . $username . '|' . $profile; ?>"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                            <?php
                                } else {
                                    echo "<tr><td colspan='4'>No profiles found for $platform.</td></tr>";
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-4">
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        // Show capture button only if applicant exists
                        echo '<button type="submit" class="btn btn-primary">Capture</button>';
                    }
                    ?>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div>
                        <a href="search.php" class="btn btn-light me-2">Go to Search page</a>
                    </div>
                    <div>
                        <a href="index.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
        </div>
        </form>

        </div>
    <?php
    } else {
        echo "No social media profiles found for the given query.";
    }
    ?>



    <script>
        function removeRow(btn) {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
</body>

</html>
