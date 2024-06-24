<?php
include "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST["first_name"]);
    $last_name = htmlspecialchars($_POST["last_name"]);
    $email = htmlspecialchars($_POST["email"]);
    $address = htmlspecialchars($_POST["address"]);
    $motivation = htmlspecialchars($_POST["motivation"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $application_position = htmlspecialchars($_POST["application_position"]);
    $identity_number = htmlspecialchars($_POST["identity_number"]);
    $recruiter_ID = null; // Replace this with the actual recruiter ID you want to use
    $captured_date = null; // Current date for captured_date
    $status = 'Pending'; // Example status, replace with actual value if needed
    $profilePicture = $_FILES['profile_picture'];

    // Check if identity number is unique
    $check_query = "SELECT * FROM candidate WHERE identity_number = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $identity_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Error: Identity number already exists.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // Check if file was uploaded without errors
    if ($profilePicture['error'] == 0) {
        $fileName = $profilePicture['name'];
        $fileTmpName = $profilePicture['tmp_name'];
        $fileSize = $profilePicture['size'];
        $fileType = $profilePicture['type'];
        $fileExt = strtolower(end(explode('.', $fileName)));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExt, $allowed)) {
            // Read the image file into a binary string
            $imageData = file_get_contents($fileTmpName);
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
            echo "<script>window.location.href = 'index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Error uploading file.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert data into the person table
        $occupation = $application_position; // Assuming occupation is same as application position
        $stmt_person = $conn->prepare("INSERT INTO `person` (`first_name`, `last_name`, `email`, `occupation`) VALUES (?, ?, ?, ?)");
        $stmt_person->bind_param("ssss", $first_name, $last_name, $email, $occupation);
        $stmt_person->execute();

        // Get the auto-generated person_ID
        $person_id = mysqli_insert_id($conn);

        // Insert data into the candidate table
        $insert_candidate_query = "INSERT INTO candidate (`address`, `motivation`, `cellphone_number`, `identity_number`, `person_ID`, `recruiter_ID`, `captured_date`, `status`, `profile_picture`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_candidate = $conn->prepare($insert_candidate_query);
        $stmt_candidate->bind_param("sssssssss", $address, $motivation, $phone, $identity_number, $person_id, $recruiter_ID, $captured_date, $status, $imageData);
        $stmt_candidate->execute();

        // Commit transaction
        mysqli_commit($conn);

        // Redirect to thank you page upon successful submission
        header("Location: thank_you.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo "<script>alert('Error: Registration failed.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $stmt_person->close();
    $stmt_candidate->close();
} else {
    echo "<script>alert('Error: Form submission method is not POST.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}

$conn->close();
?>
