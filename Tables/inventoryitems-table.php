<?php
include('../connection.php');

// this creates a table for reservation products
$sql = "CREATE TABLE reservation_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_reservation DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// message to see if the table was or is created or not
if(mysqli_query($con,$sql)){
    echo "Reservation Items table created";
}else{
    echo "Error";
}
?>