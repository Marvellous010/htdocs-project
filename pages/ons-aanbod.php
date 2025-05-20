<?php require "includes/header.php" ?>

<main class="aanbod-page">
    <div class="aanbod-container">
        <div class="filters-sidebar">
            <form id="filter-form" method="get" action="/ons-aanbod">
                <div class="filter-section">
                    <h3>TYPE</h3>
                    <div class="filter-options">
                        <?php 
                        // Haal de geselecteerde filters op
                        $selectedFilters = isset($_GET['filters']) ? $_GET['filters'] : [];
                        
                        // Als de bedrijfswagen filter is toegepast, zorg dat SUV automatisch is geselecteerd
                        if (isset($_GET['type']) && $_GET['type'] === 'bedrijfswagen') {
                            $selectedFilters[] = 'SUV';
                        }
                        // Als de reguliere auto filter is toegepast, selecteer alle types behalve SUV
                        elseif (isset($_GET['type']) && $_GET['type'] === 'regular') {
                            $selectedFilters = ['Sport', 'Sedan', 'Hatchback'];
                        }
                        
                        // Definieer alle beschikbare autotypes
                        $autoTypes = [
                            'Sport' => 'Sport',
                            'SUV' => 'SUV',
                            'Sedan' => 'Sedan',
                            'Hatchback' => 'Hatchback'
                        ];
                        
                        // Genereer checkboxes voor elk autotype
                        foreach ($autoTypes as $value => $label): ?>
                            <div class="filter-option">
                                <input type="checkbox" 
                                       id="type-<?= strtolower($value) ?>" 
                                       name="filters[]" 
                                       value="<?= $value ?>" 
                                       class="car-type-filter"
                                       <?= in_array($value, $selectedFilters) ? 'checked' : '' ?>>
                                <label for="type-<?= strtolower($value) ?>"><?= $label ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="filter-actions" style="margin-top: 20px;">
                    <button type="submit" class="button-primary">Filters toepassen</button>
                    <a href="/ons-aanbod" class="button-secondary" style="margin-top: 10px; display: inline-block;">Reset filters</a>
                </div>
            </form>
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
                    // Initiële status van checkboxes instellen op basis van query parameters
                    $selectedFilters = isset($_GET['filters']) ? $_GET['filters'] : [];
                    
                    // Check of we een type filter hebben vanuit de URL (voor de knoppen op de homepage)
                    $typeFilter = isset($_GET['type']) ? $_GET['type'] : null;
                    
                    // SQL samenstellen
                    if ($typeFilter === 'bedrijfswagen') {
                        // Voor bedrijfswagens, toon alleen SUVs
                        $stmt = $conn->prepare("SELECT * FROM cars WHERE type = 'SUV'");
                        $stmt->execute();
                    } else if ($typeFilter === 'regular') {
                        // Voor reguliere auto's, toon alles behalve SUVs
                        $stmt = $conn->prepare("SELECT * FROM cars WHERE type != 'SUV'");
                        $stmt->execute();
                    } else if (!empty($selectedFilters)) {
                        // Filters vanuit de checkbox sidebar
                        $placeholders = str_repeat('?,', count($selectedFilters) - 1) . '?';
                        $sql = "SELECT * FROM cars WHERE type IN ($placeholders)";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute($selectedFilters);
                    } else {
                        // Geen filter, toon alle auto's
                        $stmt = $conn->query("SELECT * FROM cars");
                    }
                    
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
        </div>
    </div>
</main>

<?php require "includes/footer.php" ?>
