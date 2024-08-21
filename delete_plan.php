<?php
// Include database connection file
include 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || !isset($_POST['id'])) {
    header('Location: plan_index.php');
    exit();
}

$plan_id = intval($_POST['id']);

// Begin transaction
mysqli_begin_transaction($conn);

try {
    // Delete related records from child tables
    $queries = [
        "DELETE FROM activities WHERE plan_id = $plan_id",
        "DELETE FROM tasks WHERE plan_id = $plan_id",
        "DELETE FROM performance_ratings WHERE plan_id = $plan_id"
    ];

    foreach ($queries as $query) {
        if (!mysqli_query($conn, $query)) {
            throw new Exception('Error deleting related records: ' . mysqli_error($conn));
        }
    }

    // Delete the plan record
    $query = "DELETE FROM plans WHERE id = $plan_id";
    if (!mysqli_query($conn, $query)) {
        throw new Exception('Error deleting plan: ' . mysqli_error($conn));
    }

    // Commit transaction
    mysqli_commit($conn);

    // Redirect to the plans list with a success message
    header('Location: plan_index.php?message=Plan+deleted+successfully');
} catch (Exception $e) {
    // Rollback transaction in case of error
    mysqli_rollback($conn);

    // Redirect to the plans list with an error message
    header('Location: plan_index.php?message=Error+deleting+plan');
}

exit();
?>
