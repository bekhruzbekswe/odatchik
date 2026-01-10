<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
        }

        .user-email {
            font-size: 12px;
            opacity: 0.9;
        }

        .btn-logout {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: white;
            color: #667eea;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-card h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .welcome-card p {
            color: #666;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        .stat-title {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            color: #333;
            font-size: 32px;
            font-weight: 700;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar-user {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <nav class="navbar">
        <div class="navbar-brand">Dashboard</div>
        <div class="navbar-user">
            <div class="user-info">
                <div class="user-name" id="userName">Loading...</div>
                <div class="user-email" id="userEmail"></div>
            </div>
            <button class="btn-logout" id="logoutBtn">Logout</button>
        </div>
    </nav>

    <div class="container">
        <div id="errorMessage" class="error-message"></div>

        <div class="welcome-card">
            <h1>Welcome back, <span id="welcomeName">User</span>!</h1>
            <p>Here's your dashboard overview</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👤</div>
                <div class="stat-title">Account Status</div>
                <div class="stat-value">Active</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📧</div>
                <div class="stat-title">Email Status</div>
                <div class="stat-value" id="emailStatus">Verified</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-title">Member Since</div>
                <div class="stat-value" id="memberSince">2024</div>
            </div>
        </div>
    </div>

    <script>
        const loadingOverlay = document.getElementById('loadingOverlay');
        const errorMessage = document.getElementById('errorMessage');
        const logoutBtn = document.getElementById('logoutBtn');

        // Check if user is logged in
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login';
        }

        // Load user data
        async function loadUserData() {
            try {
                const response = await fetch('/api/user', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const user = await response.json();
                    
                    // Update UI with user data
                    document.getElementById('userName').textContent = user.name;
                    document.getElementById('userEmail').textContent = user.email;
                    document.getElementById('welcomeName').textContent = user.name;
                    
                    // Update email status
                    const emailStatus = user.email_verified_at ? 'Verified' : 'Not Verified';
                    document.getElementById('emailStatus').textContent = emailStatus;
                    
                    // Update member since
                    const memberSince = new Date(user.created_at).getFullYear();
                    document.getElementById('memberSince').textContent = memberSince;
                } else {
                    throw new Error('Failed to load user data');
                }
            } catch (error) {
                errorMessage.textContent = 'Failed to load user data. Please login again.';
                errorMessage.classList.add('show');
                
                setTimeout(() => {
                    localStorage.removeItem('access_token');
                    localStorage.removeItem('user');
                    window.location.href = '/login';
                }, 2000);
            }
        }

        // Logout function
        logoutBtn.addEventListener('click', async () => {
            loadingOverlay.classList.add('show');

            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                // Clear local storage
                localStorage.removeItem('access_token');
                localStorage.removeItem('user');
                
                // Redirect to login
                window.location.href = '/login';
            }
        });

        // Load user data on page load
        loadUserData();
    </script>
</body>
</html>