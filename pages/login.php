<?php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TruckLoad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #FF6B35; --secondary: #004E89; }
        body {
            display: flex; align-items: center; justify-content: center; min-height: 100vh;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        }
        .login-container {
            background: white; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 40px; width: 100%; max-width: 400px;
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: var(--secondary); font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-truck"></i> TruckLoad</h1>
            <p class="text-muted">Sign in to your account</p>
        </div>
        
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>
        
        <div class="text-center">
            <p class="text-muted">Don't have an account? <a href="register.php">Sign up here</a></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/api.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('<?php echo APP_URL; ?>/api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                if (response.ok) {
                    localStorage.setItem('token', data.token);
                    window.location.href = '<?php echo APP_URL; ?>/pages/dashboard.php';
                } else {
                    alert(data.error);
                }
            } catch (error) {
                alert('Login failed: ' + error.message);
            }
        });
    </script>
</body>
</html>
