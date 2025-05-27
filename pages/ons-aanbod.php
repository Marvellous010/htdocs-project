<?php require __DIR__ . "/../includes/header.php" ?>

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
                <div class="search-container">
                    <form action="" method="get" class="search-form">
                        <input type="text" name="search" placeholder="Zoek op merk, type..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
                        <?php 
                        // Als er filters zijn geselecteerd, behoud deze in de zoekopdracht
                        if (isset($_GET['filters']) && is_array($_GET['filters'])) {
                            foreach ($_GET['filters'] as $filter) {
                                echo "<input type='hidden' name='filters[]' value='" . htmlspecialchars($filter) . "'>";
                            }
                        }
                        // Behoud type filter als die aanwezig is
                        if (isset($_GET['type'])) {
                            echo "<input type='hidden' name='type' value='" . htmlspecialchars($_GET['type']) . "'>";
                        }
                        ?>
                    </form>
                </div>
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
                    
                    // Zoekterm ophalen
                    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
                    
                    // Parameterarray voor prepared statements
                    $params = [];
                    $conditions = [];
                    
                    // SQL samenstellen
                    if ($typeFilter === 'bedrijfswagen') {
                        // Voor bedrijfswagens, toon alleen SUVs
                        $conditions[] = "type = 'SUV'";
                    } else if ($typeFilter === 'regular') {
                        // Voor reguliere auto's, toon alles behalve SUVs
                        $conditions[] = "type != 'SUV'";
                    } else if (!empty($selectedFilters)) {
                        // Filters vanuit de checkbox sidebar
                        $placeholders = str_repeat('?,', count($selectedFilters) - 1) . '?';
                        $conditions[] = "type IN ($placeholders)";
                        $params = array_merge($params, $selectedFilters);
                    }
                    
                    // Zoekconditie toevoegen als er een zoekterm is
                    if (!empty($searchTerm)) {
                        $searchCondition = "(brand LIKE ? OR type LIKE ? OR description LIKE ?)";
                        $conditions[] = $searchCondition;
                        $params[] = "%$searchTerm%";
                        $params[] = "%$searchTerm%";
                        $params[] = "%$searchTerm%";
                    }
                    
                    // Query samenstellen
                    if (!empty($conditions)) {
                        $whereClause = " WHERE " . implode(" AND ", $conditions);
                        $sql = "SELECT * FROM cars" . $whereClause;
                        $stmt = $conn->prepare($sql);
                        $stmt->execute($params);
                    } else {
                        // Geen filter of zoekterm, toon alle auto's
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

<style>
    /* Zoekbalk styling */
    .listings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .search-container {
        margin-left: auto;
    }
    
    .search-form {
        display: flex;
        position: relative;
    }
    
    .search-form input[type="text"] {
        width: 280px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .search-form input[type="text"]:focus {
        border-color: #3563E9;
        box-shadow: 0 0 0 2px rgba(53, 99, 233, 0.2);
        outline: none;
    }
    
    .search-button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .search-button:hover {
        color: #3563E9;
    }
    
    /* Responsieve styling */
    @media (max-width: 768px) {
        .listings-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .search-container {
            width: 100%;
            margin-top: 15px;
            margin-left: 0;
        }
        
        .search-form input[type="text"] {
            width: 100%;
        }
    }
</style>

<?php require __DIR__ . "/../includes/footer.php" ?>
