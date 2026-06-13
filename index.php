<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <section class="hero-section py-5 text-center">
        <h1 class="display-3 fw-bold mb-4">Welcome to TruckLoad</h1>
        <p class="lead mb-4">The modern platform for truck loading and logistics</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="<?php echo APP_URL; ?>/pages/rides.php" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>Find Loads
            </a>
            <a href="<?php echo APP_URL; ?>/pages/login.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Get Started
            </a>
        </div>
    </section>

    <section class="features py-5">
        <h2 class="text-center mb-5">Why Choose TruckLoad?</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-lightning-bolt fa-3x mb-3" style="color: var(--primary);"></i>
                        <h5 class="card-title">Fast Matching</h5>
                        <p class="card-text">Get matched with available drivers instantly</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt fa-3x mb-3" style="color: var(--success);"></i>
                        <h5 class="card-title">Secure</h5>
                        <p class="card-text">Safe and secure platform with verified users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-dollar-sign fa-3x mb-3" style="color: var(--warning);"></i>
                        <h5 class="card-title">Transparent Pricing</h5>
                        <p class="card-text">Fixed platform fees, no hidden charges</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
