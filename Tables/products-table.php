<?php
include('../connection.php');
// this creates a table for product infos
$sql = "CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    size VARCHAR(50),
    color VARCHAR(50),
    image_url VARCHAR(255   ),
    sku VARCHAR(50) UNIQUE,
    status ENUM('active','inactive','out_of_stock') DEFAULT 'active',
    low_stock_threshold INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
// message to see if the table was or is created or not
if(mysqli_query($con,$sql)){
    echo "Products table created";
}else{
    echo "Error";
}
?>