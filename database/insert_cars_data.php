<?php
require_once 'connection.php';

// Check if cars table exists
$stmt = $conn->query("SHOW TABLES LIKE 'cars'");
$tableExists = $stmt->rowCount() > 0;

// Create cars table if it doesn't exist
if (!$tableExists) {
    $sql = file_get_contents(__DIR__ . '/create_cars_table.sql');
    $conn->exec($sql);
    echo "Cars table created successfully.<br>";
}

// Sample car data
$cars = [
    [
        'brand' => 'Nissan GT - R',
        'type' => 'Sport',
        'capacity' => '2 Person',
        'steering' => 'Manual',
        'gasoline' => '70L',
        'price' => 80.00,
        'old_price' => 100.00,
        'rating' => 4,
        'reviews_count' => '0 Reviews',
        'description' => 'NISMO has become the embodiment of Nissan\'s outstanding performance, inspired by the most unforgiving proving ground, the "race track".',
        'main_image' => 'car (1).svg',
        'is_favorite' => 0
    ],
    [
        'brand' => 'Koenigsegg',
        'type' => 'Sport',
        'capacity' => '2 Person',
        'steering' => 'Manual',
        'gasoline' => '70L',
        'price' => 99.00,
        'old_price' => 120.00,
        'rating' => 5,
        'reviews_count' => '0 Reviews',
        'description' => 'Sports car with the best design and acceleration. Safety and comfort while driving a futuristic and elegant sports car.',
        'main_image' => 'car (0).svg',
        'is_favorite' => 1
    ],
    [
        'brand' => 'Rolls - Royce',
        'type' => 'Sport',
        'capacity' => '4 Person',
        'steering' => 'Automatic',
        'gasoline' => '80L',
        'price' => 96.00,
        'old_price' => 120.00,
        'rating' => 5,
        'reviews_count' => '0 Reviews',
        'description' => 'Luxury car with exceptional comfort and elegance. Experience the pinnacle of automotive luxury with the iconic Rolls-Royce.',
        'main_image' => 'Car (2).svg',
        'is_favorite' => 1
    ],
    [
        'brand' => 'All New Rush',
        'type' => 'SUV',
        'capacity' => '6 Person',
        'steering' => 'Manual',
        'gasoline' => '70L',
        'price' => 72.00,
        'old_price' => 90.00,
        'rating' => 4,
        'reviews_count' => '0 Reviews',
        'description' => 'Spacious SUV with comfortable seating for the whole family. Perfect for both city driving and weekend adventures.',
        'main_image' => 'Car (4).svg',
        'is_favorite' => 0
    ],
    [
        'brand' => 'CR - V',
        'type' => 'SUV',
        'capacity' => '6 Person',
        'steering' => 'Automatic',
        'gasoline' => '80L',
        'price' => 80.00,
        'old_price' => 100.00,
        'rating' => 4,
        'reviews_count' => '0 Reviews',
        'description' => 'Reliable and efficient SUV with advanced safety features and ample cargo space for all your needs.',
        'main_image' => 'Car (5).svg',
        'is_favorite' => 1
    ],
    [
        'brand' => 'All New Terios',
        'type' => 'SUV',
        'capacity' => '6 Person',
        'steering' => 'Manual',
        'gasoline' => '90L',
        'price' => 74.00,
        'old_price' => 90.00,
        'rating' => 4,
        'reviews_count' => '0 Reviews',
        'description' => 'Compact SUV with impressive off-road capabilities. Ideal for adventurous drivers who need versatility.',
        'main_image' => 'Car (6).svg',
        'is_favorite' => 0
    ]
];

// Check if data already exists
$stmt = $conn->query("SELECT COUNT(*) FROM cars");
$count = $stmt->fetchColumn();

// Only insert data if the table is empty
if ($count == 0) {
    // Prepare SQL statement
    $sql = "INSERT INTO cars (brand, type, capacity, steering, gasoline, price, old_price, rating, reviews_count, description, main_image, is_favorite) 
            VALUES (:brand, :type, :capacity, :steering, :gasoline, :price, :old_price, :rating, :reviews_count, :description, :main_image, :is_favorite)";
    
    $stmt = $conn->prepare($sql);
    
    // Insert each car
    foreach ($cars as $car) {
        $stmt->execute($car);
    }
    
    echo "Sample car data inserted successfully.<br>";
} else {
    echo "Data already exists in the cars table. No new data inserted.<br>";
}

// Create reviews table if it doesn't exist
$stmt = $conn->query("SHOW TABLES LIKE 'reviews'");
$reviewsTableExists = $stmt->rowCount() > 0;

// Create car_likes table if it doesn't exist
$stmt = $conn->query("SHOW TABLES LIKE 'car_likes'");
$likesTableExists = $stmt->rowCount() > 0;

if (!$likesTableExists) {
    $sql = file_get_contents(__DIR__ . '/create_car_likes_table.sql');
    $conn->exec($sql);
    echo "Car likes table created successfully.<br>";
}

if (!$reviewsTableExists) {
    $sql = "CREATE TABLE reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        car_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        position VARCHAR(100) NOT NULL,
        date VARCHAR(50) NOT NULL,
        rating INT NOT NULL,
        comment TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql);
    echo "Reviews table created successfully.<br>";
    
    // Sample reviews
    $reviews = [
        [
            'car_id' => 1,
            'name' => 'Alex Stanton',
            'position' => 'CEO at Bukalapak',
            'date' => '21 July 2022',
            'rating' => 4,
            'comment' => 'We are very happy with the service from the MORENT App. Morent has a low price and also a large variety of cars with good and comfortable facilities. In addition, the service provided by the officers is also very friendly and very polite.'
        ],
        [
            'car_id' => 1,
            'name' => 'Skylar Dias',
            'position' => 'CEO at Amazon',
            'date' => '20 July 2022',
            'rating' => 4,
            'comment' => 'We are greatly helped by the services of the MORENT Application. Morent has low prices and also a wide variety of cars with good and comfortable facilities. In addition, the service provided by the officers is also very friendly and very polite.'
        ],
        [
            'car_id' => 2,
            'name' => 'Thomas Wayne',
            'position' => 'CEO at Wayne Enterprises',
            'date' => '15 July 2022',
            'rating' => 5,
            'comment' => 'Exceptional service and outstanding vehicle quality. The Koenigsegg was in pristine condition and performed flawlessly during my business trip.'
        ],
        [
            'car_id' => 3,
            'name' => 'Emily Johnson',
            'position' => 'Marketing Director at Global Inc',
            'date' => '10 July 2022',
            'rating' => 5,
            'comment' => 'The Rolls-Royce was the perfect choice for our corporate event. The luxury and comfort exceeded our expectations, and the rental process was seamless.'
        ]
    ];
    
    // Prepare SQL statement for reviews
    $sql = "INSERT INTO reviews (car_id, name, position, date, rating, comment) 
            VALUES (:car_id, :name, :position, :date, :rating, :comment)";
    
    $stmt = $conn->prepare($sql);
    
    // Insert each review
    foreach ($reviews as $review) {
        $stmt->execute($review);
    }
    
    echo "Sample review data inserted successfully.<br>";
} else {
    // Always reset reviews table to start fresh with 0 reviews
    $conn->exec("TRUNCATE TABLE reviews");
    echo "Reviews table has been reset. All cars now have 0 reviews.<br>";
}

echo "Database setup complete!";
