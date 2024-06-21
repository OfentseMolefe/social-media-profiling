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


    // Insert data into the database using prepared statements
    $insert_query = "INSERT INTO person (first_name, last_name, person_ID, occcupation, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $address, $motivation, $phone, $application_position, $identity_number);

    if ($stmt->execute()) {
        // Redirect to thank you page upon successful submission
        header("Location: thank_you.php");
        exit;
    } else {
        echo "<script>alert('Error: Registration failed.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Error: Form submission method is not POST.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}

$conn->close();
?>