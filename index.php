<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="assets/css/indexcss.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        /* CSS for highlighting error input fields */
        .error-input {
            border: 1px solid red !important;
        }
    </style>
    <script>
        function validateForm() {
            var address = document.forms["registrationForm"]["address"].value;
            var motivation = document.forms["registrationForm"]["motivation"].value;
            var identityNumber = document.forms["registrationForm"]["identity_number"].value;
            var inputs = document.getElementsByTagName("input");

            // Reset borders of all input fields
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].classList.remove("error-input");
            }

            // Check if all fields are attended
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].hasAttribute("required") && inputs[i].value === "") {
                    alert("Please fill in all fields");
                    inputs[i].classList.add("error-input");
                    return false;
                }
            }

            // Check if address is provided
            if (address === "") {
                alert("Please provide your address");
                document.forms["registrationForm"]["address"].classList.add("error-input");
                return false;
            }

            // Check if motivation is provided
            if (motivation === "") {
                alert("Please provide your motivation for applying");
                document.forms["registrationForm"]["motivation"].classList.add("error-input");
                return false;
            }

            // Check if identity number is exactly 13 digits
            if (identityNumber.length !== 13 || isNaN(identityNumber)) {
                alert("Please enter a valid 13-digit identity number");
                document.forms["registrationForm"]["identity_number"].classList.add("error-input");
                return false;
            }

            // Extract birthdate from identity number (YYMMDD)
            var birthdateString = identityNumber.substring(0, 6);
            var year = parseInt(birthdateString.substring(0, 2));
            var month = parseInt(birthdateString.substring(2, 4));
            var day = parseInt(birthdateString.substring(4, 6));

            // Check if the day part is valid for the given month
            var lastDaysOfMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            if (day < 1 || day > lastDaysOfMonth[month - 1]) {
                alert("Please enter a valid identy number");
                document.forms["registrationForm"]["identity_number"].classList.add("error-input");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                <h3>Welcome</h3>
                <p>You are more than welcome to log in </p>
                <form action="index2.php">
                    <input type="submit" value="Login" class="btn btn-primary btn-lg" /><br />
                </form>
            </div>
            <div class="col-md-9 register-right">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Employee</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Application form</h3>

                        <form action="process_form.php" method="POST" name="registrationForm" onsubmit="return validateForm()">
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="first_name" placeholder="First Name *" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="last_name" placeholder="Last Name *" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address" placeholder="Address *" required />
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="motivation" placeholder="Motivation for Applying *" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email *" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="phone" minlength="10" maxlength="10" placeholder="Your Phone *" required />
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name="application_position" required>
                                            <option class="hidden" selected disabled>Please select your Application position</option>
                                            <option value="Human Resource">Human Resource</option>
                                            <option value="Technician">Technician</option>
                                            <option value="Lecturer">Lecturer</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="identity_number" placeholder="Enter Your Identity number*" required />
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-lg" value="APPLY" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

