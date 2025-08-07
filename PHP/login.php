<?php
    session_start();
    include 'connection.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT id, password FROM register_info WHERE email = ?";
    
    $check_user_query = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($check_user_query, "s", $email);
    mysqli_stmt_execute($check_user_query);
    $query_result = mysqli_stmt_get_result($check_user_query);

    if (mysqli_num_rows($query_result) > 0) {
        $user_data = mysqli_fetch_assoc($query_result);
        $db_password_hash = $user_data['password'];

        // Verify the hashed password
        if (password_verify($password, $db_password_hash)) {
            // Password is correct, set session and redirect
            $_SESSION['uid'] = $user_data['id'];
            header("Location: ../HTML/dashboard.php");
            exit();

        } else {
            // Invalid password, redirect back with an error code
            header("Location: ../HTML/login.html?error=invalidpassword");
            exit();
        }
    } else {
        // User does not exist, redirect back with an error code
        header("Location: ../HTML/login.html?error=nouser");
        exit();
    }

    mysqli_stmt_close($check_user_query);
    mysqli_close($con);
?>
