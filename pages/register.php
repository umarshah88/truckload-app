<?php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TruckLoad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #FF6B35; --secondary: #004E89; }
        body {
            display: flex; align-items: center; justify-content: center; min-height: 100vh;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        }
        .register-container {
            background: white; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 40px; width: 100%; max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1 class="text-center mb-4" style="color: var(--secondary);"><i class="fas fa-truck me-2"></i>TruckLoad</h1>
        <h5 class="text-center mb-4">Create Your Account</h5>
        
        <form id="registerForm">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">I am a:</label>
                <select class="form-control" id="role" required>
                    <option value="shipper">Shipper (Load Provider)</option>
                    <option value="driver">Driver (Truck Owner)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">
                Create Account
            </button>
        </form>
        
        <div class="text-center">
            <p class="text-muted">Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/api.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;
            if (password !== confirm_password) {
                alert('Passwords do not match');
                return;
            }
            try {
                const response = await fetch('<?php echo APP_URL; ?>/api/auth.php?action=register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        phone: document.getElementById('phone').value,
                        role: document.getElementById('role').value,
                        password: password
                    })
                });
                const data = await response.json();
                if (response.ok) {
                    localStorage.setItem('token', data.token);
                    window.location.href = '<?php echo APP_URL; ?>/pages/dashboard.php';
                } else {
                    alert(data.error);
                }
            } catch (error) {
                alert('Registration failed: ' + error.message);
            }
        });
    </script>
</body>
</html>
