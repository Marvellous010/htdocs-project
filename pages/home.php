<?php require "includes/header.php" ?>
    <header>
        <div class="advertorials">
            <div class="advertorial">
                <h2>Hét platform om een auto te huren</h2>
                <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
                <a href="<?php echo isset($_SESSION['id']) ? '/ons-aanbod?type=regular' : '/register-form'; ?>" class="button-primary">Huur nu een auto</a>
                <img src="assets/images/car-rent-header-image-1.png" alt="">
                <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
            </div>
            <div class="advertorial">
                <h2>Wij verhuren ook bedrijfswagens</h2>
                <p>Voor een vaste lage prijs met prettig voordelen.</p>
                <a href="<?php echo isset($_SESSION['id']) ? '/ons-aanbod?type=bedrijfswagen' : '/register-form'; ?>" class="button-primary">Huur een bedrijfswagen</a>
                <img src="assets/images/products/Car (5).svg" alt="SUV" style="transform: scale(1.4); width: 80%; max-width: 225px; height: auto; margin-top: 15px; margin-bottom: 15px;">
                <img src="assets/images/header-block-background.svg" alt="" class="background-header-element">
            </div>
        </div>
    </header>

    <main>
        <div class="section-header">
            <h2 class="section-title">Populaire Auto's</h2>
            <a href="/ons-aanbod" class="view-all">Bekijk Alles</a>
        </div>
        <div class="car-grid">
            <?php 
            // Include database connection
            require_once __DIR__ . "/../database/connection.php";
            
            try {
                // Fetch popular cars from database (limit to 4 for the homepage)
                $stmt = $conn->query("SELECT * FROM cars LIMIT 4");
                $popularCars = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<div class='message'>Database error: " . $e->getMessage() . "</div>";
                $popularCars = [];
            }
            
            // Display cars
            foreach ($popularCars as $i => $car) :
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
                            <img src="assets/images/icons/gas-station.svg" alt="Brandstof">
                            <span><?= $car['gasoline'] ?></span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/car.svg" alt="Handmatig">
                            <span><?= $car['steering'] ?></span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/profile-2user.svg" alt="Personen">
                            <span><?= $car['capacity'] ?></span>
                        </div>
                    </div>
                    <div class="car-footer">
                        <div class="price">
                            <span class="amount">€<?= number_format((float)$car['price'], 2, ',', '.') ?></span>
                            <span class="period">/dag</span>
                        </div>
                        <a href="/car-detail?id=<?= $car['id'] ?>" class="rent-now-btn">Huur Nu</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>


        
        <div class="show-more-cars">
            <a class="button-primary" href="/ons-aanbod">Meer auto's bekijken</a>
        </div>
    </main>

<?php require "includes/footer.php" ?>