<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['recruiter_ID'])) {
    $_SESSION['recruiter_ID'] = $data['recruiter_ID'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}