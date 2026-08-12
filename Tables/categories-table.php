<?php
include('../connection.php');

// table for categories
$sql = "CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// message to see if the table was or is created or not
if(mysqli_query($con,$sql)){
    echo "Categories table created";
}else{
    echo "Error";
}
?>