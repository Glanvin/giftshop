<?php
include('../connection.php');
// this creates a table for user along with their informations
$sql = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','staff','admin') DEFAULT 'student',
    phone VARCHAR(20),
    student_id VARCHAR(50),
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active','inactive') DEFAULT 'active'
)";
// message to see if the table was or is created or not
if(mysqli_query($con,$sql)){
    echo "Users table created";
}else{
    echo "Error: ".mysqli_error($con);
}
?>