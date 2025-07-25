<?php require __DIR__ . "/../includes/header.php" ?>

<?php
// Yo deze functies gebruik ik voor deze pagina
function getFilters() {
    // Haal filters uit URL
    $selectedFilters = isset($_GET['filters']) ? $_GET['filters'] : [];
    
    // Check ff of je een speciaal type auto wil
    if (isset($_GET['type'])) {
        if ($_GET['type'] === 'bedrijfswagen') {
            $selectedFilters = ['SUV'];
        } elseif ($_GET['type'] === 'regular') {
            $selectedFilters = ['Sport', 'Sedan', 'Hatchback'];
        }
    }
    
    return $selectedFilters;
}

function getCars($conn) {
    // Hier pak ik de filters die je hebt aangeklikt
    $selectedFilters = getFilters();
    $typeFilter = isset($_GET['type']) ? $_GET['type'] : null;
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    $params = [];
    $conditions = [];
    
    // Zoek op wat voor auto je wil
    if ($typeFilter === 'bedrijfswagen') {
        $conditions[] = "type = 'SUV'";
    } elseif ($typeFilter === 'regular') {
        $conditions[] = "type != 'SUV'";
    } elseif (!empty($selectedFilters)) {
        $placeholders = str_repeat('?,', count($selectedFilters) - 1) . '?';
        $conditions[] = "type IN ($placeholders)";
        $params = array_merge($params, $selectedFilters);
    }
    
    // Zoek op wat je hebt getypt
    if (!empty($searchTerm)) {
        $conditions[] = "(brand LIKE ? OR type LIKE ? OR description LIKE ?)";
        $params[] = "%$searchTerm%";
        $params[] = "%$searchTerm%";
        $params[] = "%$searchTerm%";
    }
    
    // Haal alle auto's uit de database
    if (!empty($conditions)) {
        $whereClause = " WHERE " . implode(" AND ", $conditions);
        $sql = "SELECT * FROM cars" . $whereClause;
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
    } else {
        $stmt = $conn->query("SELECT * FROM cars");
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Connect met de database
require_once __DIR__ . "/../database/connection.php";

// Pak alle info die we nodig hebben
try {
    $selectedFilters = getFilters();
    $cars = getCars($conn);
    $autoTypes = [  // Dit zijn alle soorten auto's die we hebben
        'Sport' => 'Sport',
        'SUV' => 'SUV',
        'Sedan' => 'Sedan',
        'Hatchback' => 'Hatchback'
    ];
} catch (PDOException $e) {
    echo "<div class='message'>Database error: " . $e->getMessage() . "</div>";
    $cars = [];
    $selectedFilters = [];
}
?>



<div class="announcement-bar">Welkom bij Rydr Autoverhuur! Bekijk hier al onze auto's!</div>

<main class="aanbod-page">
    <!-- Container voor alles -->
    <div class="aanbod-container">
        <!-- Dit is de linkerkant -->
        <div class="filters-sidebar">
            <h2 class="filter-title">Filters</h2>
            <form id="filter-form" method="get" action="/ons-aanbod">
                <!-- hier staan de type filters -->
                <div class="filter-section">
                    <h3>Type auto's</h3>
                    <div class="filter-options">
                        <?php 
                        // Hier laat ik alle autotypes zien
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
                        
                        <!-- TODO: later komen hier meer filters zoals kleur en prijs! -->
                    </div>
                </div>
                
                <div style="clear:both;"></div> <!-- Zorgt dat alles netjes onder elkaar staat -->
                
                <div class="filter-actions">
                    <button type="submit" class="button-primary">Filteren</button>
                    <div style="height: 10px; clear: both;"></div>
                    <a href="/ons-aanbod" class="button-secondary">Begin opnieuw</a>
                </div>
            </form>
            <div class="copyright-footer">
                <p>© Copyright 2025</p>
            </div>
        </div>

        <!-- Auto overzicht (rechts) -->
        <div class="car-listings">
            <div class="info-alert"><span class="info-icon">*</span> Alle auto's altijd met volle tank!</div>
            
            <div class="listings-header">
                <h2 class="aanbod-title">Ons auto-aanbod</h2>
                
                <div class="search-container">
                    <!-- Zoekbalk voor auto's -->
                    <form action="" method="get" class="search-form">
                        <input type="text" name="search" placeholder="&#128269; Zoek auto's..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button type="submit" class="search-button"><i class="fa fa-search"></i> Zoeken</button>
                        
                        <!-- Bewaar de gekozen filters -->
                        <?php 
                        // Onthoud welke filters je al hebt aangeklikt
                        if (!empty($selectedFilters)) {
                            foreach ($selectedFilters as $filter) {
                                echo "<input type='hidden' name='filters[]' value='" . htmlspecialchars($filter) . "'>";
                            }
                        }
                        
                        // Dit is voor als je een bedrijfswagen of normale auto wil
                        if (isset($_GET['type'])) {
                            echo "<input type='hidden' name='type' value='" . htmlspecialchars($_GET['type']) . "'>";
                        }
                        ?>
                    </form>
                </div>
            </div>
            
            <!-- Teller voor aantal gevonden auto's -->
            <div class="results-counter">
                <small><b>Aantal auto's gevonden:</b> <?php echo count($cars); ?></small>
            </div>

            <!-- Hier staan alle auto's -->
            <div class="car-grid">
                <?php if(count($cars) == 0): ?>
                    <div style="text-align: center; padding: 20px; background: #ffeeee; border: 1px solid #ffcccc;">
                        <h3>😢 Geen auto's gevonden!</h3>
                        <p>Probeer andere filters of zoekterm</p>
                    </div>
                <?php endif; ?>
                
                <?php foreach ($cars as $car): ?>
                <div class="car-card">
                    <!-- Naam van de auto -->
                    <div class="car-header">
                        <div class="car-info">
                            <h3><?= $car['brand'] ?> <span class="car-type-label"><?= $car['type'] ?></span></h3>
                            <!-- <span class="car-type"><?= $car['type'] ?></span> -->
                        </div>
                    </div>
                    
                    <!-- Foto van de auto -->
                    <div class="car-image">
                        <img src="assets/images/products/<?= $car['main_image'] ?>" alt="<?= $car['brand'] ?>">
                    </div>
                    
                    <!-- Kenmerken van de auto -->
                    <div class="car-specs">
                        <div class="spec-item">
                            <img src="assets/images/icons/gas-station.svg" alt="Brandstof">
                            <span><?= $car['gasoline'] ?> BRANDSTOF</span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/car.svg" alt="Handmatig">
                            <span><?= $car['steering'] ?></span>
                        </div>
                        <div class="spec-item">
                            <img src="assets/images/icons/profile-2user.svg" alt="Personen">
                            <span><?= $car['capacity'] ?> personen</span>
                        </div>
                    </div>
                    
                    <!-- Prijs en huurknop -->
                    <div class="car-footer">
                        <?php if(!empty($car['old_price']) && $car['old_price'] > $car['price']): ?>
                            <del class="old-price">€<?= number_format((float)$car['old_price'], 2, ',', '.') ?></del>
                        <?php endif; ?>
                        <div class="price">
                            <span class="amount">€<?= number_format((float)$car['price'], 2, ',', '.') ?></span>
                            <span class="period">/dag</span>
                            <?php if($car['price'] < 50): ?>
                                <span class="discount-tag">Goedkoop!</span>
                            <?php endif; ?>
                        </div>
                        <a href="/car-detail?id=<?= $car['id'] ?>" class="rent-now-btn">Huur nu</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<link rel="stylesheet" href="/assets/css/aanbod.css">

<?php require __DIR__ . "/../includes/footer.php" ?>
