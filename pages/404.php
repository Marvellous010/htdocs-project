<?php
// 404 Error page
require "includes/header.php";
?>

<main>
    <div class="error-container">
        <div class="error-card">
            <h1>404</h1>
            <h2>Pagina niet gevonden</h2>
            <p>De pagina die u probeert te bezoeken bestaat niet of is verplaatst.</p>
            <div class="error-actions">
                <a href="/" class="button-primary">Terug naar Home</a>
            </div>
        </div>
    </div>
</main>

<style>
.error-container {
    max-width: 800px;
    margin: 50px auto;
    text-align: center;
    padding: 0 20px;
}

.error-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 40px 30px;
}

.error-card h1 {
    font-size: 72px;
    margin: 0;
    color: #3563E9;
}

.error-card h2 {
    margin-top: 10px;
    margin-bottom: 20px;
    color: #1A202C;
}

.error-card p {
    color: #4A5568;
    margin-bottom: 30px;
}

.error-actions {
    margin-top: 20px;
}
</style>

<?php require "includes/footer.php"; ?>
