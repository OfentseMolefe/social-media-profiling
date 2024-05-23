<?php
session_start();
include "db_conn.php";

// Check if the necessary session variables are set
if (!isset($_SESSION['username'], $_SESSION['recruiterID'], $_SESSION['merged_first_middle_name'], $_SESSION['merged_last_name'], $_SESSION['profiles'])) {
    die('Necessary session variables are not set.');
}

// Assign session variables to local variables
$username = $_SESSION['username'];
$recruiterID = $_SESSION['recruiterID'];
$first_name = $_SESSION['merged_first_middle_name'];
$last_name = $_SESSION['merged_last_name'];
$profiles = $_SESSION['profiles'];

// Function to safely output data
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// SQL query to fetch applicant data
$sql = "SELECT * FROM applicant WHERE first_name = '$first_name' AND last_name = '$last_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); // Fetch the row data

    $email = $row['email'] ?? 'Not provided';
    $phone = $row['phone'] ?? 'Not provided';
    $address = $row['address'] ?? 'Not provided';
    $position = $row['application_position'] ?? 'Not provided';
    $appdate = $row['application_date'] ?? 'Not provided';
    $motivation = $row['motivation'] ?? 'Not provided';
} else {
    // Default values if no record is found
    $email = 'Not provided';
    $phone = 'Not provided';
    $address = 'Not provided';
    $position = 'Not provided';
    $appdate = 'Not provided';
    $motivation = 'Not provided';
}

// Function to save data to a CSV file
function save_to_csv($data, $filename = 'report.csv') {
    $file = fopen($filename, 'w');
    foreach ($data as $row) {
        fputcsv($file, $row);
    }
    fclose($file);
}

// Handle saving to CSV if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_csv'])) {
    $csv_data = [
        ['Recruiter Name', $username],
        ['Recruiter ID', $recruiterID],
        ['Name', "$first_name $last_name"],
        ['Email', $email],
        ['Phone', $phone],
        ['Address', $address],
        ['Position', $position],
        ['Motivation', $motivation],
        ['Application Date', $appdate]
    ];
    foreach ($profiles as $platform => $profile_username) {
        $csv_data[] = [ucfirst($platform), $profile_username];
    }

    $csv_filename = 'report.csv'; // Set a default filename
    save_to_csv($csv_data, $csv_filename);

    // Prompt file download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . $csv_filename);
    header('Pragma: no-cache');
    readfile($csv_filename);
   // header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        Report Page
    </nav>

    <div class="container">
        <h2 class="text-center">View Report</h2>
        <p><strong>Recruiter Name:</strong> <?php echo h($username); ?></p>
        <p><strong>Recruiter ID:</strong> <?php echo h($recruiterID); ?></p>
        <p><strong>Name:</strong> <?php echo h("$first_name $last_name"); ?></p>
        <p><strong>Email:</strong> <?php echo h($email); ?></p>
        <p><strong>Phone:</strong> <?php echo h($phone); ?></p>
        <p><strong>Address:</strong> <?php echo h($address); ?></p>
        <p><strong>Position:</strong> <?php echo h($position); ?></p>
        <p><strong>Motivation:</strong> <?php echo h($motivation); ?></p>
        <p><strong>Application Date:</strong> <?php echo h($appdate); ?></p>
        <p><strong>Profiles Captured:</strong></p>
        <ul>
            <?php foreach ($profiles as $platform => $username) : ?>
                <li><?php echo ucfirst($platform) . ": " . h($username); ?></li>
            <?php endforeach; ?>
        </ul>
        <div class="btn-container">
            <a href="candidate_table.php" class="btn btn-primary">Complete</a>
            <form method="post" class="d-inline">
                <button type="submit" name="save_csv" class="btn btn-success">Save to CSV</button>
            </form>
        </div>
    </div>
</body>

</html>
