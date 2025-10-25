<?php
include './includes/conn.php';

if (DEVELOPMENT_MODE) {
    clear_query_logs();
    echo json_encode(['status' => 'success', 'message' => 'Query logs cleared']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not in development mode']);
}
?>