<?php
session_start();
require "includes/header.php";

// Redirect to home if not logged in
if (!isset($_SESSION['id'])) {
    header('Location: /');
    exit();
}

// Get user's reservations
require_once "database/connection.php";

try {
    $stmt = $conn->prepare("
        SELECT r.*, c.brand, c.type, c.main_image 
        FROM reservations r
        JOIN cars c ON r.car_id = c.id
        WHERE r.user_id = :user_id
        ORDER BY r.created_at DESC
    ");
    $stmt->bindParam(":user_id", $_SESSION['id']);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Er is een fout opgetreden bij het ophalen van uw reserveringen: " . $e->getMessage();
}
?>

<!-- Deze pagina is gemaakt door: Kevin - MBO 4 Software Development 2025 -->
<!-- NIET AANPASSEN AUB! -->

<main style="background-color: #f0f0f0;">
    <marquee scrollamount="3" style="background-color: yellow; padding: 5px; font-weight: bold;">Welkom bij uw persoonlijke reserveringen! U bent ingelogd als: <?= $_SESSION['name'] ?? 'gebruiker' ?>!</marquee>

    <div class="my-reservations-container" style="padding: 15px; margin: 10px;">
        <!-- Dit is de koptekst van de pagina -->
        <div class="my-reservations-header" style="background: linear-gradient(to right, #e6f2ff, #ffffff); padding: 10px; border: 2px solid #cccccc; text-align: center;">
            <h1 style="color: blue; text-shadow: 1px 1px #ccc;">MIJN AUTO RESERVERINGEN!!!</h1>
            <p>Bekijk en beheer uw autoreserveringen bij <span style="font-weight: bold; color: #3563E9;">RYDR</span> - De beste autoverhuur van Nederland!</p>
            <img src="/assets/images/logo.png" alt="Logo" style="max-width: 100px;">  <!-- logo voor extra stijl -->
        </div>
        
        <div style="background-color: #f5f5f5; padding: 8px; border: 1px dashed #ccc; margin: 10px 0; text-align: center;">
            <small><b>Ingelogd als:</b> <?= $_SESSION['email'] ?? 'onbekend' ?> | <b>Datum:</b> <?= date('d-m-Y') ?></small>
        </div>
        
        <?php if (isset($_SESSION['reservation_success'])): ?>
            <div class="succes-message" style="background-color: #d4edda; color: green; padding: 10px; border: 1px solid green; margin: 10px 0; text-align: center; font-weight: bold;">
                ✅ <?= $_SESSION['reservation_success'] ?>
            </div>
            <?php unset($_SESSION['reservation_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['reservation_error'])): ?>
            <div class="message" style="background-color: #f8d7da; color: red; padding: 10px; border: 1px solid red; margin: 10px 0; text-align: center; font-weight: bold;">
                ❌ <?= $_SESSION['reservation_error'] ?>
            </div>
            <?php unset($_SESSION['reservation_error']); ?>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message" style="background-color: #f8d7da; color: red; padding: 10px; border: 1px solid red;"><?= $error ?></div>
        <?php else: ?>
            <?php if (count($reservations) > 0): ?>
                <!-- HIER KOM JE RESERVERINGEN TE STAAN!! -->
                <div style="text-align: center; background-color: #eaf7ff; padding: 5px; margin-bottom: 10px; border-bottom: 2px dotted blue;">
                    <p style="font-weight: bold; margin: 5px;">U heeft <?= count($reservations) ?> reservering(en)!</p>
                </div>
                
                <div class="reservations-list" style="display: block;">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-card" style="border: 2px solid #ddd; background-color: white; margin-bottom: 20px; padding: 10px; box-shadow: 3px 3px 8px #ccc; overflow: hidden;">
                            <div class="reservation-car-image" style="float: left; width: 40%; position: relative; text-align: center; border: 1px dashed #ccc; padding: 5px;">
                                <?php
                                // Hier bepalen we de status van de reservering!!
                                $status_class = '';
                                switch ($reservation['status']) {
                                    case 'pending':
                                        $status_text = '⏳ IN AFWACHTING';
                                        $status_class = 'background-color: #fff3cd; color: #856404; border: 2px solid orange;';
                                        break;
                                    case 'confirmed':
                                        $status_text = '✅ BEVESTIGD!';
                                        $status_class = 'background-color: #d4edda; color: green; border: 2px solid green;';
                                        break;
                                    case 'canceled':
                                        $status_text = '❌ GEANNULEERD';
                                        $status_class = 'background-color: #f8d7da; color: red; border: 2px solid red;';
                                        break;
                                    case 'completed':
                                        $status_text = '🎉 VOLTOOID!';
                                        $status_class = 'background-color: #d1ecf1; color: #0c5460; border: 2px solid blue;';
                                        break;
                                }
                                ?>
                                <!-- Dit is de status label -->
                                <div style="<?= $status_class ?> padding: 5px; font-weight: bold; margin-bottom: 5px; border-radius: 5px; font-size: 12px;"><?= $status_text ?></div>
                                <!-- Dit is de afbeelding van de auto -->
                                <img src="assets/images/products/<?= $reservation['main_image'] ?>" alt="<?= $reservation['brand'] ?> <?= $reservation['type'] ?>" style="max-width: 100%; height: auto; margin-top: 10px;">
                            </div>
                            <div class="reservation-details" style="float: right; width: 55%; padding: 5px;">
                                <!-- Auto naam en type -->
                                <div style="background-color: #f0f0f0; padding: 5px; border-radius: 5px; margin-bottom: 10px;">
                                    <div class="reservation-car-name" style="font-size: 18px; font-weight: bold; color: #3563E9;"><?= strtoupper($reservation['brand']) ?> <span style="color: #666;"><?= $reservation['type'] ?></span></div>
                                </div>
                                
                                <!-- Datums voor ophalen en terugbrengen -->
                                <div class="reservation-dates" style="border: 1px solid #ccc; padding: 8px; margin-bottom: 10px; background-color: #fafafa;">
                                    <div class="reservation-date" style="margin-bottom: 5px;">
                                        <span class="reservation-date-label" style="font-weight: bold; color: #666;">📅 Ophalen:</span>
                                        <span class="reservation-date-value" style="color: #333;"><?= date('d-m-Y', strtotime($reservation['start_date'])) ?></span>
                                    </div>
                                    <div class="reservation-date">
                                        <span class="reservation-date-label" style="font-weight: bold; color: #666;">🔄 Retour:</span>
                                        <span class="reservation-date-value" style="color: #333;"><?= date('d-m-Y', strtotime($reservation['end_date'])) ?></span>
                                    </div>
                                </div>
                                
                                <!-- Prijsinformatie -->
                                <div class="reservation-price" style="text-align: right;">
                                    <span class="reservation-price-label" style="display: block; color: #666;">Totale Prijs:</span>
                                    <span class="reservation-price-value" style="font-size: 20px; font-weight: bold; color: #28a745;">€<?= number_format($reservation['total_price'], 2, ',', '.') ?></span>
                                    <?php if($reservation['total_price'] > 500): ?>
                                        <span style="display: block; color: red; font-size: 12px;">Dure luxe auto!!! 💎</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="clear: both;"></div> <!-- Dit is belangrijk bij float layout -->
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-reservations">
                    <h2>U heeft nog geen reserveringen</h2>
                    <p>Bekijk ons aanbod en maak uw eerste autoreservering.</p>
                    <a href="/ons-aanbod" class="button-primary">Bekijk ons aanbod</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php require "includes/footer.php" ?>
