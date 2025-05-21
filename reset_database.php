<?php
// Include database connection
require_once 'database/connection.php';

try {
    // Disable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop existing tables
    $conn->exec("DROP TABLE IF EXISTS reviews");
    $conn->exec("DROP TABLE IF EXISTS car_likes");
    $conn->exec("DROP TABLE IF EXISTS cars");
    
    // Re-enable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Create cars table
    $conn->exec("CREATE TABLE cars (
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      brand VARCHAR(100) NOT NULL,
      type VARCHAR(50) NOT NULL,
      capacity VARCHAR(50) NOT NULL,
      steering VARCHAR(50) NOT NULL,
      gasoline VARCHAR(50) NOT NULL,
      price DECIMAL(10,2) NOT NULL,
      old_price DECIMAL(10,2) NOT NULL,
      rating INT(11) DEFAULT NULL,
      reviews_count VARCHAR(50) DEFAULT NULL,
      description TEXT,
      main_image VARCHAR(255) NOT NULL,
      is_favorite TINYINT(1) DEFAULT 0,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create reviews table
    $conn->exec("CREATE TABLE reviews (
      id INT AUTO_INCREMENT PRIMARY KEY,
      car_id INT NOT NULL,
      name VARCHAR(100) NOT NULL,
      position VARCHAR(100),
      date VARCHAR(50) NOT NULL,
      rating INT NOT NULL,
      comment TEXT,
      FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
    )");
    
    // Create car_likes table
    $conn->exec("CREATE TABLE car_likes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      car_id INT NOT NULL,
      user_ip VARCHAR(45) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      UNIQUE KEY unique_like (car_id, user_ip),
      FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
    )");
    
    // Insert 12 different cars with unique SVG images - EXACTLY matching the screenshot
    $conn->exec("INSERT INTO cars (id, brand, type, capacity, steering, gasoline, price, old_price, rating, reviews_count, main_image, is_favorite) VALUES
    (1, 'Koenigsegg', 'Sport', '2 People', 'Manual', '70L', 99.00, 120.00, 0, '0', 'car (0).svg', 1),
    (2, 'Nissan GT - R', 'Sport', '2 People', 'Manual', '70L', 80.00, 100.00, 0, '0', 'car (1).svg', 0),
    (3, 'Rolls - Royce - Dawn', 'Sedan', '4 People', 'Manual', '70L', 96.00, 120.00, 0, '0', 'Car (2).svg', 1),
    (4, 'Nissan GT - R', 'Sport', '2 People', 'Manual', '70L', 80.00, 100.00, 0, '0', 'Car (3).svg', 0),
    (5, 'All New Rush', 'SUV', '6 People', 'Manual', '70L', 72.00, 80.00, 0, '0', 'Car (4).svg', 0),
    (6, 'CR - V', 'SUV', '6 People', 'Manual', '80L', 80.00, 100.00, 0, '0', 'Car (5).svg', 1),
    (7, 'All New Terios', 'SUV', '6 People', 'Manual', '90L', 74.00, 90.00, 0, '0', 'Car (6).svg', 0),
    (8, 'CR - V', 'SUV', '6 People', 'Manual', '80L', 80.00, 100.00, 0, '0', 'Car (7).svg', 1),
    (9, 'MG ZX Exclusive', 'Hatchback', '4 People', 'Electric', '70L', 76.00, 80.00, 0, '0', 'Car (8).svg', 1),
    (10, 'New MG ZS', 'SUV', '6 People', 'Manual', '80L', 80.00, 100.00, 0, '0', 'Car (9).svg', 0),
    (11, 'MG ZX Excite', 'Hatchback', '4 People', 'Electric', '90L', 74.00, 80.00, 0, '0', 'Car (10).svg', 1),
    (12, 'New MG ZS', 'SUV', '6 People', 'Manual', '80L', 80.00, 100.00, 0, '0', 'Car (11).svg', 0)");
    
    // Add descriptions for each car
    $conn->exec("UPDATE cars SET description = 'The Koenigsegg is a high-performance sports car known for its speed and luxury.' WHERE id = 1");
    $conn->exec("UPDATE cars SET description = 'The Nissan GT-R is a high-performance sports car with advanced technology and impressive speed.' WHERE id = 2");
    $conn->exec("UPDATE cars SET description = 'The Rolls-Royce is a luxury vehicle known for its comfort, elegance, and premium features.' WHERE id = 3");
    $conn->exec("UPDATE cars SET description = 'The Nissan GT-R is a high-performance sports car with advanced technology and impressive speed.' WHERE id = 4");
    $conn->exec("UPDATE cars SET description = 'The All New Rush is a versatile SUV with ample space and modern features for families.' WHERE id = 5");
    $conn->exec("UPDATE cars SET description = 'The CR-V is a reliable and spacious SUV perfect for both city driving and adventures.' WHERE id = 6");
    $conn->exec("UPDATE cars SET description = 'The All New Terios is a compact SUV with excellent handling and fuel efficiency.' WHERE id = 7");
    $conn->exec("UPDATE cars SET description = 'The CR-V is a reliable and spacious SUV perfect for both city driving and adventures.' WHERE id = 8");
    $conn->exec("UPDATE cars SET description = 'The MG ZX Exclusive is a stylish hatchback with premium features and excellent fuel economy.' WHERE id = 9");
    $conn->exec("UPDATE cars SET description = 'The New MG ZS is a modern SUV with advanced safety features and comfortable interior.' WHERE id = 10");
    $conn->exec("UPDATE cars SET description = 'The MG ZX Excite is a sporty hatchback with dynamic performance and modern technology.' WHERE id = 11");
    $conn->exec("UPDATE cars SET description = 'The New MG ZS is a modern SUV with advanced safety features and comfortable interior.' WHERE id = 12");
    
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background-color: #f5f5f5; border-radius: 5px;'>";
    echo "<h2 style='color: #4CAF50;'>Database Reset Successfully</h2>";
    echo "<p>All tables have been recreated and 12 cars with unique SVG images have been added.</p>";
    echo "<p><a href='/ons-aanbod' style='color: #2196F3; text-decoration: none;'>View All Cars</a> | <a href='/' style='color: #2196F3; text-decoration: none;'>Return to Home</a></p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background-color: #ffebee; border-radius: 5px;'>";
    echo "<h2 style='color: #f44336;'>Error</h2>";
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    echo "<p><a href='/' style='color: #2196F3; text-decoration: none;'>Return to Home</a></p>";
    echo "</div>";
}
?>
