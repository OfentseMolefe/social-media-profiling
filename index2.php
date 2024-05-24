<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <script src="MyFrameworks/jquery-3.5.1.min.js" type="text/javascript"></script>
  <style>
    .card-header {
      font-size: 24px; /* Increase font size */
      text-align: center; /* Align text to the center */
    }
  </style>
</head>

<body>
  <div class="position-relative">
    <img src="assets/Login_Background.jpg" class="w-100 fixed-top" style="z-index: -1;">
    <div class="float-right position-absolute" style="left:100px; top:150px;">
      <div class="card" style="width:500px;">
        <div class="card-header"> Enter Your Login Details</div>
        <div class="card-body">
          <?php
          if (isset($_GET["msg"])) {
            $msg = $_GET["msg"];
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" style="background-color: #ffcccc; border-color: #ff0000; color: #ff0000;">
               ' . $msg . '
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
          }
          ?>
        
          <form method="POST" action="login.php">
            <div class="p-1">
              <div>Email </div>
              <div><input name="username" required class="form-control" placeholder="name@example.com"></div>
            </div>
            <div class="p-1">
              <div>Password</div>
              <div><input type="password" name="password" required class="form-control" placeholder="12345"></div>
            </div>
            <div class="text-center p-1">
              <div class="d-inline-block">
                <input type="submit" name="IDSubmit" value="Log in" class="btn btn-primary">
              </div>
            </div>
            <div class="text-center text-danger"><label id="wrongpasswordlbl"><?php echo isset($error) ? $error : ""; ?></label></div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
  echo "<script>document.getElementById('wrongpasswordlbl').style.display = 'none';</script>";
  if (isset($_GET["Error"])) {
    echo "<script>document.getElementById('wrongpasswordlbl').style.display = 'block';</script>";
  }
  ?>
  
</body>

</html>