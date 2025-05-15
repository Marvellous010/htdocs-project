<?php require "includes/header.php" ?>

<main class="aanbod-page">
    <div class="aanbod-container">
        <div class="filters-sidebar">
            <div class="filter-section">
                <h3>TYPE</h3>
                <div class="filter-options">
                    <div class="filter-option">
                        <input type="checkbox" id="sport" name="type" value="sport" checked>
                        <label for="sport">Sport (10)</label>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <h3>PRIJS</h3>
                <div class="price-slider">
                    <input type="range" min="0" max="500" value="300" class="slider" id="price-range">
                    <div class="price-range-labels">
                        <span>€0</span>
                        <span>Max. €300,00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="car-listings">
            <div class="listings-header">
                <h2>Ons Aanbod</h2>
            </div>

            <div class="car-grid">
                <?php
                // Include database connection
                require_once __DIR__ . "/../database/connection.php";
                
                try {
                    // Fetch all cars from database
                    $stmt = $conn->query("SELECT * FROM cars");
                    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "<div class='message'>Database error: " . $e->getMessage() . "</div>";
                    $cars = [];
                }

                foreach ($cars as $car) :
                ?>
                <div class="car-card">
                    <div class="car-header">
                        <div class="car-info">
                            <h3><?= $car['brand'] ?></h3>
                            <span class="car-type"><?= $car['type'] ?></span>
                        </div>
                        <div class="favorite-icon <?= $car['is_favorite'] ? 'active' : '' ?>" data-car-id="<?= $car['id'] ?>">
                            <i class="fa fa-heart"></i>
                        </div>
                    </div>
                    <div class="car-image">
                        <img src="assets/images/products/<?= $car['main_image'] ?>" alt="<?= $car['brand'] ?>">
                    </div>
                    <div class="car-specs">
                        <div class="spec-item">
                            <img src="assets/images/icons/gas-station.svg" alt="Fuel">
                            <span><?= $car['gasoline'] ?></span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/car.svg" alt="Manual">
                            <span><?= $car['steering'] ?></span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/profile-2user.svg" alt="People">
                            <span><?= $car['capacity'] ?></span>
                        </div>
                    </div>
                    <div class="car-footer">
                        <div class="price">
                            <span class="amount">€<?= number_format((float)$car['price'], 2, ',', '.') ?></span>
                            <span class="period">/dag</span>
                        </div>
                        <a href="/car-detail?id=<?= $car['id'] ?>" class="rent-now-btn">Rent Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="pagination">
                <div class="page-indicator">10/10</div>
            </div>
        </div>
    </div>
</main>

<?php require "includes/footer.php" ?>
