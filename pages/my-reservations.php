<?php
session_start();
require "includes/header.php";

// Als je niet bent ingelogd, stuur ik je terug naar home
if (!isset($_SESSION['id'])) {
    header('Location: /');
    exit();
}

// Hier haal ik al je gereserveerde auto's op
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



<main>
    <div class="my-reservations-container">
        <!-- Titel en uitleg bovenaan de pagina -->
        <div class="my-reservations-header">
            <h1>MIJN AUTO RESERVERINGEN</h1>
            <p>Bekijk en beheer uw autoreserveringen bij <span class="brand-name">RYDR</span> - De beste autoverhuur van Nederland!</p>
            <img src="/assets/images/logo.png" alt="Logo">
        </div>
        
        <div class="user-info-bar">
            <small><b>Ingelogd als:</b> <?= $_SESSION['email'] ?? 'onbekend' ?> | <b>Datum:</b> <?= date('d-m-Y') ?></small>
        </div>
        
        <?php if (isset($_SESSION['reservation_success'])): ?>
            <div class="message success-message">
                ✅ <?= $_SESSION['reservation_success'] ?>
            </div>
            <?php unset($_SESSION['reservation_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['reservation_error'])): ?>
            <div class="message error-message">
                ❌ <?= $_SESSION['reservation_error'] ?>
            </div>
            <?php unset($_SESSION['reservation_error']); ?>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error-message"><?= $error ?></div>
        <?php else: ?>
            <?php if (count($reservations) > 0): ?>
                <!-- Teller voor aantal reserveringen -->
                <div class="reservations-counter">
                    <p>U heeft <?= count($reservations) ?> reservering(en)!</p>
                </div>
                
                <div class="reservations-list">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-card">
                            <div class="reservation-car-image">
                                <?php
                                // Hier kies ik de juiste tekst en kleur voor de status
                                $status_class = '';
                                switch ($reservation['status']) {
                                    case 'pending':
                                        $status_text = '⏳ IN AFWACHTING';
                                        $status_class = 'status-pending';
                                        break;
                                    case 'confirmed':
                                        $status_text = '✅ BEVESTIGD!';
                                        $status_class = 'status-confirmed';
                                        break;
                                    case 'canceled':
                                        $status_text = '❌ GEANNULEERD';
                                        $status_class = 'status-canceled';
                                        break;
                                    case 'completed':
                                        $status_text = '🎉 VOLTOOID!';
                                        $status_class = 'status-completed';
                                        break;
                                }
                                ?>
                                <!-- Label dat de status laat zien -->
                                <div class="status-label <?= $status_class ?>"><?= $status_text ?></div>
                                <!-- Foto van de auto -->
                                <img src="assets/images/products/<?= $reservation['main_image'] ?>" alt="<?= $reservation['brand'] ?> <?= $reservation['type'] ?>">
                            </div>
                            <div class="reservation-details">
                                <!-- Merk en type auto -->
                                <div class="car-info">
                                    <div class="reservation-car-name"><?= strtoupper($reservation['brand']) ?> <span><?= $reservation['type'] ?></span></div>
                                </div>
                                
                                <!-- Wanneer ophalen en terugbrengen -->
                                <div class="reservation-dates">
                                    <div class="reservation-date">
                                        <span class="reservation-date-label">📅 Ophalen:</span>
                                        <span class="reservation-date-value"><?= date('d-m-Y', strtotime($reservation['start_date'])) ?></span>
                                    </div>
                                    <div class="reservation-date">
                                        <span class="reservation-date-label">🔄 Retour:</span>
                                        <span class="reservation-date-value"><?= date('d-m-Y', strtotime($reservation['end_date'])) ?></span>
                                    </div>
                                </div>
                                
                                <!-- Hoeveel het kost -->
                                <div class="reservation-price">
                                    <span class="reservation-price-label">Totale Prijs:</span>
                                    <span class="reservation-price-value">€<?= number_format($reservation['total_price'], 2, ',', '.') ?></span>
                                    <?php if($reservation['total_price'] > 500): ?>
                                        <span class="luxury-tag">Dure luxe auto! 💎</span>
                                    <?php endif; ?>
                                </div>
                            </div>
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

<link rel="stylesheet" href="/assets/css/reservations.css">

<?php require "includes/footer.php" ?>
