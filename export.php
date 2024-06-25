<?php
session_start();
include "db_conn.php";

// Ensure Composer autoload file is included
require 'vendor/autoload.php';

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
$sql = "SELECT 
            c.candidate_ID, 
            p.first_name AS candidate_first_name, 
            p.last_name AS candidate_last_name, 
            p.email AS candidate_email, 
            c.identity_number, 
            c.application_date, 
            r.recruiter_ID,
            CONCAT(pr.first_name, ' ', pr.last_name) AS recruiter_name 
        FROM 
            userandmediadb.candidate c
        JOIN 
            userandmediadb.recruiter r ON c.recruiter_ID = r.recruiter_ID
        JOIN 
            userandmediadb.person p ON c.person_ID = p.person_ID
        LEFT JOIN 
            userandmediadb.person pr ON r.person_ID = pr.person_ID
        WHERE 
            r.recruiter_ID = ?";

$params = [$recruiter_ID];
$types = 'i';

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
    $sql .= " AND c.application_date = ?";
    $types .= 's';
    $params[] = $application_date;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No candidates found.";
    exit;
}

// Prepare data for export
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Export logic
$export_type = isset($_GET['export_type']) ? $_GET['export_type'] : 'csv';

if ($export_type == 'csv') {
    // Prepare CSV headers and data
    $filename = 'export.csv';
    $csv_headers = ['Candidate ID', 'First Name', 'Last Name', 'Email', 'Identity Number', 'Application Date', 'Recruiter Name'];

    // Output CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');
    fputcsv($output, $csv_headers);

    // Output CSV data rows
    foreach ($data as $row) {
        fputcsv($output, [
            $row['candidate_ID'],
            $row['candidate_first_name'],
            $row['candidate_last_name'],
            $row['candidate_email'],
            $row['identity_number'],
            $row['application_date'],
            $row['recruiter_name']
        ]);
    }

    fclose($output);
    exit;
}

/* elseif ($export_type == 'pdf') {
    // Prepare PDF output using MPDF library
    $mpdf = new \Mpdf\Mpdf();

    // Build HTML content for PDF
    $html = '<h1>Recruiter Summary</h1>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= '<thead><tr>';
    $html .= '<th>Candidate ID</th>';
    $html .= '<th>First Name</th>';
    $html .= '<th>Last Name</th>';
    $html .= '<th>Email</th>';
    $html .= '<th>Identity Number</th>';
    $html .= '<th>Application Date</th>';
    $html .= '<th>Recruiter Name</th>';
    $html .= '</tr></thead>';
    $html .= '<tbody>';

    // Output PDF table rows
    foreach ($data as $row) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['candidate_ID']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['candidate_first_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['candidate_last_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['candidate_email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['identity_number']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['application_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['recruiter_name']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Write HTML content to PDF
    $mpdf->WriteHTML($html);
    $mpdf->Output('export.pdf', 'D');
    exit;
}
 */

// Default redirection if export_type is not recognized
header("Location: super_user.php");
exit;

