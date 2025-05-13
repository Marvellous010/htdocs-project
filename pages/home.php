<?php require "includes/header.php" ?>
    <header>
        <div class="advertorials">
            <div class="advertorial">
                <h2>Hét platform om een auto te huren</h2>
                <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
                <a href="#" class="button-primary">Huur nu een auto</a>
                <img src="assets/images/car-rent-header-image-1.png" alt="">
                <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
            </div>
            <div class="advertorial">
                <h2>Wij verhuren ook bedrijfswagens</h2>
                <p>Voor een vaste lage prijs met prettig voordelen.</p>
                <a href="#" class="button-primary">Huur een bedrijfswagen</a>
                <img src="assets/images/car-rent-header-image-2.png" alt="">
                <img src="assets/images/header-block-background.svg" alt="" class="background-header-element">
            </div>
        </div>
    </header>

    <main>
        <div class="section-header">
            <h2 class="section-title">Popular Car</h2>
            <a href="/ons-aanbod" class="view-all">View All</a>
        </div>
        <div class="car-grid">
            <?php 
            $popularCars = [
                [
                    'brand' => 'Koenigsegg',
                    'type' => 'Sport',
                    'price' => '99.00',
                    'favorite' => true
                ],
                [
                    'brand' => 'Nissan GT - R',
                    'type' => 'Sport',
                    'price' => '80.00',
                    'favorite' => false
                ],
                [
                    'brand' => 'Rolls - Royce',
                    'type' => 'Sport',
                    'price' => '96.00',
                    'favorite' => true
                ],
                [
                    'brand' => 'Nissan GT - R',
                    'type' => 'Sport',
                    'price' => '80.00',
                    'favorite' => false
                ]
            ];
            
            for ($i = 0; $i < count($popularCars); $i++) : 
                $car = $popularCars[$i];
                $imgPath = ($i <= 1) ? "car ({$i}).svg" : "Car ({$i}).svg";
            ?>
                <div class="car-card">
                    <div class="car-header">
                        <div class="car-info">
                            <h3><?= $car['brand'] ?></h3>
                            <span class="car-type"><?= $car['type'] ?></span>
                        </div>
                        <div class="favorite-icon <?= $car['favorite'] ? 'active' : '' ?>">
                            <i class="fa <?= $car['favorite'] ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                        </div>
                    </div>
                    <div class="car-image">
                        <img src="assets/images/products/<?= $imgPath ?>" alt="<?= $car['brand'] ?>">
                    </div>
                    <div class="car-specs">
                        <div class="spec-item">
                            <img src="assets/images/icons/gas-station.svg" alt="Fuel">
                            <span>70L</span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/car.svg" alt="Manual">
                            <span>Manual</span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/profile-2user.svg" alt="People">
                            <span>2 People</span>
                        </div>
                    </div>
                    <div class="car-footer">
                        <div class="price">
                            <span class="amount">$<?= $car['price'] ?></span>
                            <span class="period">/day</span>
                        </div>
                        <a href="#" class="rent-now-btn">Rent Now</a>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="section-header">
            <h2 class="section-title">Recommended Car</h2>
        </div>
        <div class="car-grid">
            <?php 
            $recommendedCars = [
                [
                    'brand' => 'All New Rush',
                    'type' => 'SUV',
                    'price' => '72.00',
                    'favorite' => false
                ],
                [
                    'brand' => 'CR - V',
                    'type' => 'SUV',
                    'price' => '80.00',
                    'favorite' => true
                ],
                [
                    'brand' => 'All New Terios',
                    'type' => 'SUV',
                    'price' => '74.00',
                    'favorite' => false
                ],
                [
                    'brand' => 'CR - V',
                    'type' => 'SUV',
                    'price' => '80.00',
                    'favorite' => true
                ],
                [
                    'brand' => 'MG ZX Exclusive',
                    'type' => 'SUV',
                    'price' => '76.00',
                    'favorite' => true
                ],
                [
                    'brand' => 'New MG ZS',
                    'type' => 'SUV',
                    'price' => '80.00',
                    'favorite' => false
                ],
                [
                    'brand' => 'MG ZX Excite',
                    'type' => 'SUV',
                    'price' => '74.00',
                    'favorite' => true
                ],
                [
                    'brand' => 'New MG ZS',
                    'type' => 'SUV',
                    'price' => '80.00',
                    'favorite' => false
                ]
            ];
            
            for ($i = 0; $i < count($recommendedCars); $i++) : 
                $car = $recommendedCars[$i];
                $imgIndex = $i + 4;
                $imgPath = "Car ({$imgIndex}).svg";
            ?>
                <div class="car-card">
                    <div class="car-header">
                        <div class="car-info">
                            <h3><?= $car['brand'] ?></h3>
                            <span class="car-type"><?= $car['type'] ?></span>
                        </div>
                        <div class="favorite-icon <?= $car['favorite'] ? 'active' : '' ?>">
                            <i class="fa <?= $car['favorite'] ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                        </div>
                    </div>
                    <div class="car-image">
                        <img src="assets/images/products/<?= $imgPath ?>" alt="<?= $car['brand'] ?>">
                    </div>
                    <div class="car-specs">
                        <div class="spec-item">
                            <img src="assets/images/icons/gas-station.svg" alt="Fuel">
                            <span>70L</span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/car.svg" alt="Manual">
                            <span>Manual</span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/profile-2user.svg" alt="People">
                            <span>2 People</span>
                        </div>
                    </div>
                    <div class="car-footer">
                        <div class="price">
                            <span class="amount">$<?= $car['price'] ?></span>
                            <span class="period">/day</span>
                        </div>
                        <a href="#" class="rent-now-btn">Rent Now</a>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        
        <div class="show-more-cars">
            <a class="button-primary" href="/ons-aanbod">Show more cars</a>
        </div>
    </main>

<?php require "includes/footer.php" ?>