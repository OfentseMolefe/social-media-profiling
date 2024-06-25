<?php
include "db_conn.php";

$CandidateID = $_GET["candidate_ID"];

// Start a transaction
mysqli_begin_transaction($conn);

try {
    // Retrieve the person_ID associated with the candidate_ID
    $sql = "SELECT person_ID FROM candidate WHERE candidate_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $CandidateID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("No candidate found with the given ID.");
    }

    $row = $result->fetch_assoc();
    $personID = $row['person_ID'];

    // Delete from the candidate table
    $sql = "DELETE FROM candidate WHERE candidate_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $CandidateID);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to delete candidate.");
    }

    // Delete from the person table
    $sql = "DELETE FROM person WHERE person_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $personID);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to delete person.");
    }

    // Commit the transaction
    mysqli_commit($conn);

    header("Location: search.php?msg=Candidate and person deleted successfully");
} catch (Exception $e) {
    // Rollback the transaction in case of error
    mysqli_rollback($conn);
    echo "Failed: " . $e->getMessage();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
