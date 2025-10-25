<?php
include './includes/conn.php';

header('Content-Type: application/json');

if (DEVELOPMENT_MODE) {
    echo json_encode(get_query_logs());
} else {
    echo json_encode([]);
}
?>