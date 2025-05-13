<?php require "includes/header.php" ?>

<?php
// Include database connection
require_once __DIR__ . "/../database/connection.php";

// Get car ID from URL parameter
$carId = isset($_GET['id']) ? intval($_GET['id']) : 1;

try {
    // Fetch car data from database
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = :id");
    $stmt->bindParam(':id', $carId);
    $stmt->execute();
    
    // Check if car exists
    if ($stmt->rowCount() === 0) {
        // If car not found, get the first car
        $stmt = $conn->query("SELECT * FROM cars ORDER BY id LIMIT 1");
    }
    
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If no cars in database, redirect to setup page
    if (!$car) {
        echo "<div class='message'>No cars found in database. Please <a href='/setup_database.php'>setup the database</a> first.</div>";
        exit;
    }
    
    // Fetch reviews for this car
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE car_id = :car_id ORDER BY date DESC");
    $stmt->bindParam(':car_id', $car['id']);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "<div class='message'>Database error: " . $e->getMessage() . "</div>";
    exit;
}
?>

<?php
// Display success or error messages if they exist
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="succes-message">Your review has been submitted successfully!</div>';
}

if (isset($_GET['error'])) {
    echo '<div class="message">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>

<main class="car-detail-page">
    <div class="car-detail-container">
        <div class="car-detail-left">
            <div class="car-detail-card">
                <div class="car-detail-hero">
                    <h2>Sports car with the best design and acceleration</h2>
                    <p>Safety and comfort while driving a futuristic and elegant sports car</p>
                    <div class="car-hero-image">
                        <?php if(isset($car['main_image'])): ?>
                            <img src="assets/images/products/<?= $car['main_image'] ?>" alt="<?= $car['brand'] ?>">
                        <?php else: ?>
                            <img src="assets/images/products/car (0).svg" alt="Car Image">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="car-thumbnails">
                <?php foreach(range(1, 3) as $index): ?>
                <div class="car-thumbnail <?= $index === 1 ? 'active' : '' ?>">
                    <?php if(isset($car['main_image'])): ?>
                        <img src="assets/images/products/<?= $car['main_image'] ?>" alt="<?= $car['brand'] ?> view <?= $index ?>">
                    <?php else: ?>
                        <img src="assets/images/products/car (0).svg" alt="Car Image view <?= $index ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="car-detail-right">
            <div class="car-detail-info">
                <div class="car-detail-header">
                    <div class="car-title-rating">
                        <h1><?= $car['brand'] ?> <?= isset($car['type']) ? '- ' . $car['type'] : '' ?></h1>
                        <div class="car-rating">
                            <div class="stars stars-<?= count($reviews) > 0 ? min(5, round(array_sum(array_column($reviews, 'rating')) / count($reviews))) : 0 ?>"></div>
                            <span class="review-count"><?= count($reviews) ?> Reviews</span>
                        </div>
                    </div>
                    <!-- Like button removed as requested -->
                </div>
                
                <div class="car-description">
                    <p><?= isset($car['description']) ? $car['description'] : 'No description available.' ?></p>
                </div>
                
                <div class="car-specs-grid">
                    <div class="car-spec">
                        <span class="spec-label">Type Car</span>
                        <span class="spec-value"><?= isset($car['type']) ? $car['type'] : 'N/A' ?></span>
                    </div>
                    <div class="car-spec">
                        <span class="spec-label">Capacity</span>
                        <span class="spec-value"><?= isset($car['capacity']) ? $car['capacity'] : 'N/A' ?></span>
                    </div>
                    <div class="car-spec">
                        <span class="spec-label">Steering</span>
                        <span class="spec-value"><?= isset($car['steering']) ? $car['steering'] : 'N/A' ?></span>
                    </div>
                    <div class="car-spec">
                        <span class="spec-label">Gasoline</span>
                        <span class="spec-value"><?= isset($car['gasoline']) ? $car['gasoline'] : 'N/A' ?></span>
                    </div>
                </div>
                
                <div class="car-pricing">
                    <div class="price-info">
                        <div class="current-price">$<?= isset($car['price']) ? $car['price'] : '0.00' ?><span class="price-period">/day</span></div>
                        <div class="old-price">$<?= isset($car['old_price']) ? $car['old_price'] : '0.00' ?></div>
                    </div>
                    <a href="#" class="rent-now-button">Rent Now</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="reviews-section">
        <div class="reviews-header">
            <h2>Reviews</h2>
            <span class="review-count"><?= count($reviews) ?></span>
        </div>
        
        <div class="reviews-list">
            <?php if(count($reviews) > 0): ?>
                <?php foreach($reviews as $review): ?>
                <div class="review-item">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            <img src="assets/images/avatars/avatar-placeholder.jpg" alt="<?= $review['name'] ?>">
                        </div>
                        <div class="reviewer-details">
                            <h3><?= $review['name'] ?></h3>
                            <p><?= $review['position'] ?></p>
                        </div>
                        <div class="review-date"><?= $review['date'] ?></div>
                    </div>
                    <div class="review-rating">
                        <div class="stars stars-<?= $review['rating'] ?>"></div>
                    </div>
                    <div class="review-content">
                        <p><?= $review['comment'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-reviews">No reviews yet. Be the first to review this car!</div>
            <?php endif; ?>
        </div>
        
        <?php if(count($reviews) > 3): ?>
        <div class="show-all-reviews">
            <button class="show-all-button">Show All <i class="fa fa-chevron-down"></i></button>
        </div>
        <?php endif; ?>
        
        <div class="add-review-section">
            <h3>Add Your Review</h3>
            <form action="/add-review" method="post" class="review-form">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="position">Your Position</label>
                    <input type="text" id="position" name="position" placeholder="e.g. CEO at Company" required>
                </div>
                
                <div class="form-group">
                    <label>Rating</label>
                    <div class="rating-input">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                        <label for="star<?= $i ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Review</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>
                
                <button type="submit" class="submit-review-btn">Submit Review</button>
            </form>
        </div>
    </div>
</main>

<!-- JavaScript for like functionality removed as requested -->

<?php require "includes/footer.php" ?>
