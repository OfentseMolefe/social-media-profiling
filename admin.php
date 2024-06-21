<?php
include "db_conn.php";
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

  <title>Administrative Page</title>
</head>

<body>
  <nav class="navbar navbar-light justify-content-center fs-3 mb-5n" style="background-color: #00ff5573;">
    <div class="text-center">
      <span style="font-size: 1.5rem;">Administrative table for system users and admins</span>
    </div>
  </nav>

  <div class="container">
    <div class="mb-4 d-flex align-items-center">
      <?php

      // Check if the username session is set
      if (isset($_SESSION['username'])) {
        // Display an active dot and the username
        echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
      }
      ?>
    </div>
  </div>

  <div class="container">
    <?php
    if (isset($_GET["msg"])) {
      $msg = $_GET["msg"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <div class="mb-3 d-flex justify-content-between">
      <a href="add-new.php" class="btn btn-dark">Add New</a>

      <a href="search.php" class="btn btn-light">Go to Search page </a>
    </div>
    <table class="table table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th scope="col">Recruiter ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Email</th>
          <th scope="col">Password</th>
          <th scope="col">Occupation</th>

          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // SQL query to join recruiter and person tables
        $sql = "SELECT r.recruiter_ID, p.first_name, p.last_name, p.email, r.password, p.occupation 
        FROM recruiter r
        JOIN person p ON r.person_ID = p.person_ID
        WHERE p.first_name != 'system'";
        $result = mysqli_query($conn, $sql);
        $isLoggedIn = $_SESSION['recruiter_ID'];

        while ($row = mysqli_fetch_assoc($result)) {
          // check the current user
          $isCurrentUser = ($row['recruiter_ID'] == $isLoggedIn);
        ?>
          <tr>
            <td><?php echo $row["recruiter_ID"] ?></td>
            <td><?php echo $row["first_name"] ?></td>
            <td><?php echo $row["last_name"] ?></td>
            <td><?php echo $row["email"] ?></td>
            <td><?php echo str_repeat('*', strlen($row["password"])) ?></td>
            <td><?php echo $row["occupation"] ?></td>

            <td>
              <?php if ($isCurrentUser) : ?>
                <a href="edit.php?recruiter_ID=<?php echo $row["recruiter_ID"] ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
              <?php endif; ?>
              <a href="delete.php?recruiter_ID=<?php echo $row["recruiter_ID"] ?>" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
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