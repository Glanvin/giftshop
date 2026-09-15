<?php
require __DIR__ . '/../connection.php';
// creating table for the inventory logs
$sql = "CREATE TABLE inventory_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    action ENUM('add','remove','update','reservation','fulfillment') NOT NULL,
    quantity_change INT NOT NULL,
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// message to see if the table was or is created or not
if(mysqli_query($con,$sql)){
    echo "Inventory logs table created";
}else{
    echo "Error";
}
?>