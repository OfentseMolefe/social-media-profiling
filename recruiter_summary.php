<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['recruiter_ID'])) {
    echo "No recruiter ID set in session.";
    exit;
}

$recruiter_ID = $_SESSION['recruiter_ID'];

// Initialize filter variables
$status = isset($_GET['status']) ? $_GET['status'] : '';
$first_name = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$last_name = isset($_GET['last_name']) ? $_GET['last_name'] : '';
$application_date = isset($_GET['application_date']) ? $_GET['application_date'] : '';

// Build the SQL query with dynamic filters
$sql = "SELECT c.candidate_ID, p.first_name, p.last_name, p.email, c.identity_number, c.application_date, c.comment
FROM userandmediadb.candidate c
JOIN userandmediadb.recruiter r ON c.recruiter_ID = r.recruiter_ID
JOIN userandmediadb.person p ON c.person_ID = p.person_ID
WHERE r.recruiter_ID = ?";

$params = [];
$types = 'i';
$params[] = $recruiter_ID;

// Add dynamic filters
if ($status) {
    $sql .= " AND c.status = ?";
    $types .= 's';
    $params[] = $status;
}

if ($first_name) {
    $sql .= " AND p.first_name LIKE ?";
    $types .= 's';
    $params[] = '%' . $first_name . '%';
}

if ($last_name) {
    $sql .= " AND p.last_name LIKE ?";
    $types .= 's';
    $params[] = '%' . $last_name . '%';
}

if ($application_date) {
    $sql .= " AND DATE(c.application_date) = ?";
    $types .= 's';
    $params[] = $application_date;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Additional styles can be added here if needed */
        /* Ensure consistency with employee page layout */
    </style>
    <script>
        function toggleFilterInput(checkbox, inputId) {
            var inputField = document.getElementById(inputId);
            if (checkbox.checked) {
                inputField.style.display = 'block';
            } else {
                inputField.style.display = 'none';
            }
        }
    </script>
</head>

<body>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        <div class="col text-center">
            <span>Recruiter Summary</span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="mb-4 d-flex align-items-right">
            <?php
            if (isset($_SESSION['username'])) {
                echo '<span><i class="fas fa-circle text-success me-1"></i>' . $_SESSION['username'] . '</span>';
            }
            ?>
        </div>

        <form method="get" action="">
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="statusCheckbox" onclick="toggleFilterInput(this, 'statusInput')" <?php if ($status) echo 'checked'; ?>>
                <label for="statusCheckbox" class="form-check-label">Filter by Status</label>
            </div>
            <div class="mb-3" id="statusInput" style="<?php if ($status) echo 'display:block;'; else echo 'display:none;'; ?>">
                <label for="status" class="form-label">Status</label>
                <input type="text" name="status" id="status" class="form-control" value="<?php echo htmlspecialchars($status); ?>">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="firstNameCheckbox" onclick="toggleFilterInput(this, 'firstNameInput')" <?php if ($first_name) echo 'checked'; ?>>
                <label for="firstNameCheckbox" class="form-check-label">Filter by First Name</label>
            </div>
            <div class="mb-3" id="firstNameInput" style="<?php if ($first_name) echo 'display:block;'; else echo 'display:none;'; ?>">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($first_name); ?>">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="lastNameCheckbox" onclick="toggleFilterInput(this, 'lastNameInput')" <?php if ($last_name) echo 'checked'; ?>>
                <label for="lastNameCheckbox" class="form-check-label">Filter by Last Name</label>
            </div>
            <div class="mb-3" id="lastNameInput" style="<?php if ($last_name) echo 'display:block;'; else echo 'display:none;'; ?>">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($last_name); ?>">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="applicationDateCheckbox" onclick="toggleFilterInput(this, 'applicationDateInput')" <?php if ($application_date) echo 'checked'; ?>>
                <label for="applicationDateCheckbox" class="form-check-label">Filter by Application Date</label>
            </div>
            <div class="mb-3" id="applicationDateInput" style="<?php if ($application_date) echo 'display:block;'; else echo 'display:none;'; ?>">
                <label for="application_date" class="form-label">Application Date</label>
                <input type="date" name="application_date" id="application_date" class="form-control" value="<?php echo htmlspecialchars($application_date); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>Candidate ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Identity Number</th>
                        <th>Application Date</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['candidate_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['identity_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['application_date']); ?></td>
                            <td><?php echo empty($row['comment']) ? "NOT YET Vetted" : htmlspecialchars($row['comment']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No candidates found.</p>
        <?php endif; ?>

        <form method="get" action="export.php">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
            <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
            <input type="hidden" name="application_date" value="<?php echo htmlspecialchars($application_date); ?>">
            <input type="hidden" name="recruiter_ID" value="<?php echo htmlspecialchars($recruiter_ID); ?>">
            <div class="d-flex">
                <a href="recruiters.php" class="btn btn-primary me-5">Back to Employee Page</a>
                <button type="submit" name="export_type" value="csv" class="btn btn-secondary">Export to CSV</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and additional scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Your custom scripts -->
    <script>
        // Add any custom scripts needed here
    </script>
</body>

</html>
