<?php
require __DIR__ . '/../connection.php';
// this creates a table for reservation
$sql = "CREATE TABLE reservations (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    reservation_code VARCHAR(20) UNIQUE NOT NULL,
    total_amount     DECIMAL(10,2) NOT NULL,
    status           ENUM('pending','confirmed','ready','completed','cancelled') DEFAULT 'pending',
    notes            TEXT,
    expiry_date      DATETIME NOT NULL,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// message to see if the table was or is created or not
if (mysqli_query($con, $sql)) {
    echo "Reservations table created successfully.";
} else {
    echo "Error: " . mysqli_error($con);
}
?>
