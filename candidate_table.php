<?php
include "db_conn.php";

// Check if the username session is set
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <title>Candidate Table Page</title>
</head>

<body>
  <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
    Candidate Table
  </nav>
  <div class="container">
    <div class="d-flex align-items-center">
      <?php
      if (isset($_SESSION['username'])) {
        // Display an active dot and the username
        echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
      }
      ?>
    </div>
  </div>

  <div class=" mb-4  container">
    <div class="mb-4 d-flex justify-content-between">
      <?php
      if (isset($_GET["msg"])) {
        $msg = $_GET["msg"];
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
      }
      ?>
    </div>
    <div class="mb-4 d-flex justify-content-between">
      <a href="search.php" class="btn btn-light">Go to Search page </a>
    </div>
    <table class="table table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th scope="col">Candidate ID</th>
          <th scope="col">Recruiter_ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Email</th>
          <th scope="col">Cellphone</th>
          <th scope="col">Captured date</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT candidate.candidate_ID, candidate.recruiter_ID, applicant.first_name, applicant.last_name, applicant.email, applicant.phone, candidate.captured_date 
            FROM candidate 
            INNER JOIN applicant ON candidate.applicant_ID = applicant.applicant_ID";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $row["candidate_ID"] ?></td>
            <td><?php echo $row["recruiter_ID"] ?></td>
            <td><?php echo $row["first_name"] ?></td>
            <td><?php echo $row["last_name"] ?></td>
            <td><?php echo $row["email"] ?></td>
            <td><?php echo $row["phone"] ?></td>
            <td><?php echo $row["captured_date"] ?></td>
            <td>
              <a href="view_candidate.php?candidate_ID=<?php echo $row["candidate_ID"] ?>&first_name=<?php echo $row["first_name"] ?>&last_name=<?php echo $row["last_name"] ?>&email=<?php echo $row["email"] ?>&cell_no=<?php echo $row["phone"] ?>" class="link-dark"><i class="fa-solid fa-eye me-3"></i></a>
              <a href="cand_Delete.php?candidate_ID=<?php echo $row["candidate_ID"] ?>" class="link-dark"><i class="fa-solid fa-trash fs-5 me-3"></i></a>
            </td>
          </tr>
        <?php
        }
        ?>

      </tbody>
    </table>
    <a href="index.php" class="btn btn-danger logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>

  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>