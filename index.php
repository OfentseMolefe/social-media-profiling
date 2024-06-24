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
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .error-input {
            border: 2px solid red;
        }
    </style>
    <script>
        // Spinner function
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('captureForm').addEventListener('submit', function() {
                document.getElementById('spinner-overlay').style.display = 'flex';
            });
        });

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
                alert("Please enter a valid identity number");
                document.forms["registrationForm"]["identity_number"].classList.add("error-input");
                return false;
            }

            return true;
        }

        $(document).ready(function() {
            $('#application_position').change(function() {
                if ($(this).val() === 'Other') {
                    $('#other_position_div').show();
                    $('#other_position').attr('required', true);
                    $('#other_position').focus();
                    $(this).replaceWith($('#other_position'));
                } else {
                    $('#other_position_div').hide();
                    $('#other_position').removeAttr('required');
                }
            });
        });
    </script>
</head>

<body>
    <div id="spinner-overlay" class="spinner-overlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!--Add the Nav Bar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

            <div class="form-inline my-2 my-lg-0">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                <h3>Welcome</h3>
                <p>You are more than welcome to log in</p>
                <form action="index2.php">
                    <input type="submit" value="Login" class="btn btn-primary btn-lg" /><br />
                </form>
            </div>
            <div class="col-md-9 register-right">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="track_application.php" role="tab" aria-controls="home" aria-selected="true">Track Application</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Application form</h3>
                        <form id="captureForm" action="process_form.php" method="POST" name="registrationForm" onsubmit="return validateForm()"  enctype="multipart/form-data">
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
                                        <select class="form-control" name="application_position" id="application_position" required>
                                            <option class="hidden" selected disabled>Please select your Application position</option>
                                            <option value="Human Resource">Human Resource</option>
                                            <option value="Technician">Technician</option>
                                            <option value="Lecturer">Lecturer</option>
                                            <option value="General Worker">General Worker</option>
                                            <option value="Security">Security</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="other_position_div" style="display: none;">
                                        <input type="text" class="form-control" name="other_position" id="other_position" placeholder="Please specify your Application position">
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="identity_number" placeholder="Enter Your Identity number *" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" placeholder="Upload photo">
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