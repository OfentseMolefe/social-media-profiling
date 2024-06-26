<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        .pdf-container {
            width: 100%;
            height: 80vh;
            border: 1px solid #ccc;
            margin-top: 20px;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>

<body>
<img src="assets/Login_Background.jpg" class="w-100 fixed-top" style="z-index: -1;">
    <div class="container">
        <h1 class="mt-3 " style="color: #ccc;">About Us</h1>
        <div class="pdf-container">
            <embed src="assets/about_us.pdf" type="application/pdf" width="100%" height="100%">
        </div>
        <a href="index.php" class="btn btn-primary btn-back">Back to Home</a>
    </div>
</body>

</html>
