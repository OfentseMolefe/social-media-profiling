<?php
include "db_conn.php";
$CandidateID = $_GET["candidate_ID"];
$sql = "DELETE FROM `candidate` WHERE candidate_ID = $CandidateID";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: search.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}

?>