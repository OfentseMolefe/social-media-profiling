<?php
include "db_conn.php";

$recruiterID = $_GET["recruiter_ID"];

if ($recruiterID) {
    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch person_ID associated with the recruiter
        $sqlFetch = "SELECT person_ID FROM recruiter WHERE recruiter_ID = ?";
        $stmtFetch = $conn->prepare($sqlFetch);
        $stmtFetch->bind_param("i", $recruiterID);
        $stmtFetch->execute();
        $resultFetch = $stmtFetch->get_result();
        $row = $resultFetch->fetch_assoc();
        $personID = $row['person_ID'];

        // Delete from recruiter table
        $sqlDeleteRecruiter = "DELETE FROM recruiter WHERE recruiter_ID = ?";
        $stmtDeleteRecruiter = $conn->prepare($sqlDeleteRecruiter);
        $stmtDeleteRecruiter->bind_param("i", $recruiterID);
        $stmtDeleteRecruiter->execute();

        // Delete from person table
        $sqlDeletePerson = "DELETE FROM person WHERE person_ID = ?";
        $stmtDeletePerson = $conn->prepare($sqlDeletePerson);
        $stmtDeletePerson->bind_param("i", $personID);
        $stmtDeletePerson->execute();

        // Commit transaction
        mysqli_commit($conn);
        header("Location: admin.php?msg=Record deleted successfully");
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo "Failed: " . $e->getMessage();
    }

    // Close statements
    $stmtFetch->close();
    $stmtDeleteRecruiter->close();
    $stmtDeletePerson->close();
}

// Close connection
$conn->close();
?>
s
