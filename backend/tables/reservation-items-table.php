<?php
require __DIR__ . '/../connection.php';
// this creates a table for reservation item
$sql = "CREATE TABLE reservation_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
)";
// message to see if the table was or is created or not
if(mysqli_query($con, $sql)){
    echo "Reservation Items table created";
} else {
    echo "Error: " . mysqli_error($con);
}
?>