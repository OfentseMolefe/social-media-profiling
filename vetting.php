<?php
// Start the session
session_start();
include "db_conn.php";

// Get the search key from the POST request
$searchKey = isset($_POST['searchKey']) ? $_POST['searchKey'] : '';
if (empty($searchKey)) {
    die("Search key is required.");
}

// Split the full name into parts using a space (' ') as the delimiter
$names = explode(' ', $searchKey);
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
    // The last part is considered the last name
    $last_name = $names[$name_parts_count - 1];
}
if ($name_parts_count >= 3) {
    // Parts in between are considered the middle name
    $middle_name_parts = array_slice($names, 1, $name_parts_count - 2);
    $middle_name = implode(' ', $middle_name_parts);
}

// Merge the first name and middle name together with a space between them
$merged_first_middle_name = trim($first_name . ' ' . $middle_name);
// Concatenate first name and last name with a space between them
$full_name = $merged_first_middle_name . ' ' . $last_name;

// Check if the person exists
$sql = "SELECT c.candidate_ID AS candidate_ID
        FROM candidate c 
        JOIN person p ON p.person_ID = c.person_ID 
        WHERE p.last_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $last_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Applicant not found.");
}

$row = $result->fetch_assoc();
$applicantID = $row['candidate_ID'];

if (!$applicantID) {
    die("Applicant not found.");
}

// Add applicant ID into the session
$_SESSION['applicant_id'] = $applicantID;

// Push the name to the session
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

if (empty($checkedNetworks)) {
    $checkedNetworks = ['facebook', 'instagram', 'twitter', 'linkedin'];
}

$_SESSION['profiles'] = $checkedNetworks;

// Construct the query string for social networks
$socialNetworksQuery = implode('%2C', $checkedNetworks);

// Construct the URL for the RapidAPI endpoint
$full_name_encoded = urlencode($full_name);
$rapidApiUrl = "https://social-links-search.p.rapidapi.com/search-social-links?query=" . $full_name_encoded . "&social_networks=" . $socialNetworksQuery;

// Set up RapidAPI request headers
$rapidApiHeaders = [
    "X-RapidAPI-Host: social-links-search.p.rapidapi.com",
    "X-RapidAPI-Key: f389caef1amsh56eb102cb5c5798p1862bfjsn2987ea5afeee"
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
    die("cURL Error: " . curl_error($curl));
}

// Close cURL session
curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);

// Check if the response status is OK
if (!isset($data['status']) || $data['status'] !== "OK") {
    die("No social media profiles found for the given query.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Profiles results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
    <div id="spinner-overlay" class="spinner-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-3" style="background-color: #00ff5573;">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 fs-3">Vetting Page</span>
            <div>
                <span class="navbar-brand mb-0">
                    <?php echo "Search Key: " . htmlspecialchars($searchKey); ?>
                </span>
                <span class="navbar-brand mb-0">
                    <?php
                    if (isset($_SESSION['username'])) {
                        // Display an active dot and the username
                        echo "Logged In: " . htmlspecialchars($_SESSION['username']);
                    }
                    ?>
                </span>
            </div>
        </div>
    </nav>

    <div class="mb-4 container">
        <form id="captureForm" action="view_candidate.php" method="POST">
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
                                
                                // Set correct icon for Twitter/X
                                $platform_icon = strtolower($platform) === 'twitter' ? 'x' : strtolower($platform);
                        ?>
                                <tr>
                                    <td><img src="assets/icons/<?php echo $platform_icon ?>.png" alt="<?php echo ucfirst($platform) ?>" class="social-icon" style="max-width: 90px;"></td>
                                    <td><?php echo htmlspecialchars($username); ?></td>
                                    <td><a href="<?php echo htmlspecialchars($profile); ?>" target="_blank"><?php echo htmlspecialchars($profile); ?></a></td>
                                    <td><input type="text" name="social_profiles[]" value="<?php echo htmlspecialchars($platform . '|' . $username . '|' . $profile); ?>"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4">
                <?php
                    // Show capture button only if applicant exists
                    echo '<button type="submit" class="btn btn-primary">Complete vetting</button>';
                ?>
            </div>
            <div class="mb-3 d-flex justify-content-between">
                <div>
                    <a href="search.php" class="btn btn-light me-2">Go Back</a>
                </div>
                <div>
                    <a href="index.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function removeRow(btn) {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        document.getElementById('captureForm').addEventListener('submit', function() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        });
    </script>
</body>
</html>
