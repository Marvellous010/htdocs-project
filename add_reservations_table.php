<?php
// Include database connection
require_once 'database/connection.php';

try {
    // Create reservations table
    $conn->exec("CREATE TABLE IF NOT EXISTS reservations (
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      car_id INT(11) NOT NULL,
      user_id INT(11) NOT NULL,
      start_date DATE NOT NULL,
      end_date DATE NOT NULL,
      total_price DECIMAL(10,2) NOT NULL,
      status ENUM('pending', 'confirmed', 'canceled', 'completed') DEFAULT 'pending',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
      FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE CASCADE
    )");
    
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background-color: #f5f5f5; border-radius: 5px;'>";
    echo "<h2 style='color: #4CAF50;'>Reservations Table Created Successfully</h2>";
    echo "<p>The reservations table has been added to the database.</p>";
    echo "<p><a href='/' style='color: #2196F3; text-decoration: none;'>Return to Home</a></p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background-color: #ffebee; border-radius: 5px;'>";
    echo "<h2 style='color: #f44336;'>Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    echo "<p><a href='/' style='color: #2196F3; text-decoration: none;'>Return to Home</a></p>";
    echo "</div>";
}
?>
