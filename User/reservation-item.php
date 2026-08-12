<?php
include('../connection.php');

// this creates a table for reservation items
$sql = "CREATE TABLE reservation_items (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    product_id     INT NOT NULL,
    quantity       INT NOT NULL,
    price          DECIMAL(10,2) NOT NULL,
    size           VARCHAR(20) DEFAULT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id)     REFERENCES products(id)     ON DELETE CASCADE
)";
// this shows a message whether the tables has been made or has notand shows an error
if (mysqli_query($con, $sql)) {
    echo "Reservation Items table created successfully.";
} else {
    echo "Error: " . mysqli_error($con);
}
?>
