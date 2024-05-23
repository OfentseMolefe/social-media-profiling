<?php
include "db_conn.php";
$recruiterID = $_GET["recruiter_ID"];
$sql = "DELETE FROM `recruiter` WHERE recruiter_ID = $recruiterID";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: admin.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}
