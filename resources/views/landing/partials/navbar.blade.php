<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container text-center">
                <a class="navbar-brand" href="{{ url('/') }}"><h2 class="section-heading text-uppercase mb-3 text-primary">Apotek Sakura</h2></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ request()->routeIs('products.*') || request()->routeIs('cart.*') || request()->routeIs('customer.*') ? url('/').'#beranda' : '#beranda' }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ request()->routeIs('products.*') || request()->routeIs('cart.*') || request()->routeIs('customer.*') ? route('products.index') : '#product' }}">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ request()->routeIs('products.*') || request()->routeIs('cart.*') || request()->routeIs('customer.*') ? url('/').'#team' : '#team' }}">Team</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ request()->routeIs('products.*') || request()->routeIs('cart.*') || request()->routeIs('customer.*') ? url('/').'#contact' : '#contact' }}">Contact</a>
                        </li>
                        <li class="nav-item ms-2" id="loginSection">
                            <button class="btn btn-success text-white" type="button" data-bs-toggle="modal" data-bs-target="#loginModal" id="loginButton">
                                <i class="fas fa-user"></i>
                            </button>
                        </li>
                        <li class="nav-item ms-2" id="userSection" style="display: none;">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i>
                                    <span id="userName">User</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><h6 class="dropdown-header">Selamat datang!</h6></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cart.view') }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Keranjang
                                        @if($cartCount > 0)
                                            <span class="badge bg-danger ms-1">{{ $cartCount }}</span>
                                        @endif
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.orders') }}"><i class="fas fa-history me-2"></i>Riwayat</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" id="logoutButton"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="loginModalLabel">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                        <!-- Login Form -->
                        <form id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="loginField" class="form-label" id="loginFieldLabel">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="loginFieldIcon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="loginField" name="username" placeholder="Masukkan username" required>
                                </div>
                            </div>
                            

                            
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Masukkan password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                                <label class="form-check-label" for="rememberMe">
                                    Ingat saya
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success" form="loginForm" id="loginSubmitBtn">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                    
                    <!-- Additional Links -->
                    <div class="modal-footer border-top-0 pt-0" id="additionalLinks">
                        <div class="w-100 text-center">
                            <small class="text-muted" id="registerLink">
                                Belum punya akun? 
                                <a href="{{ route('user.register.form') }}" class="text-success text-decoration-none">
                                    Daftar di sini
                                </a>
                            </small>
                            <br>
                            {{-- <small>
                                <a href="#" class="text-muted text-decoration-none">Lupa password?</a>
                            </small> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const registerLink = document.getElementById('registerLink');
                
                // Show register link for customers
                if (registerLink) {
                    registerLink.style.display = 'inline';
                }
                
                // Toggle password visibility
                const togglePassword = document.getElementById('togglePassword');
                const loginPassword = document.getElementById('loginPassword');
                
                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
                        const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                        loginPassword.setAttribute('type', type);
                        
                        // Toggle icon
                        const icon = this.querySelector('i');
                        if (type === 'password') {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        } else {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        }
                    });
                }

                // Handle login form submission
                const loginForm = document.getElementById('loginForm');
                console.log('Login form element:', loginForm);
                
                if (loginForm) {
                    console.log('Adding submit event listener to login form');
                    
                    loginForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        console.log('Form submission prevented and handling started');
                        console.log('Form element:', this);
                        
                        const formData = new FormData(this);
                        const submitBtn = document.getElementById('loginSubmitBtn');
                        
                        if (!submitBtn) {
                            console.error('Submit button not found with ID loginSubmitBtn');
                            return;
                        }
                        
                        const originalText = submitBtn.innerHTML;
                        
                        console.log('Submit button found:', submitBtn);
                        console.log('Original button text:', originalText);
                        
                        // Ensure CSRF token is in form data (it should be from @csrf in form)
                        if (!formData.has('_token')) {
                            console.log('Adding CSRF token manually');
                            formData.append('_token', csrfToken);
                        }
                        
                        // Debug: show form data
                        console.log('Form data entries:');
                        for (let [key, value] of formData.entries()) {
                            console.log(' -', key, ':', value);
                        }
                        
                        // Show loading state
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Login...';
                            submitBtn.disabled = true;
                        }
                        
                        // Get CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        console.log('CSRF Token:', csrfToken);
                        
                        if (!csrfToken) {
                            console.error('CSRF token not found!');
                            showNotification('Token keamanan tidak ditemukan!', 'error');
                            return;
                        }
                        
                        fetch('{{ route("auth.login") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);
                            console.log('Response URL:', response.url);
                            
                            if (!response.ok) {
                                console.log('Response not OK, status:', response.status);
                            }
                            
                            // Get the response text first to see what we're getting
                            return response.text().then(text => {
                                console.log('Raw response text:', text);
                                
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Failed to parse JSON:', e);
                                    console.error('Response was:', text);
                                    throw new Error('Server returned invalid JSON: ' + text.substring(0, 100));
                                }
                            });
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            
                            if (data.success) {
                                console.log('Login successful, closing modal...');
                                
                                // Close modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                                if (modal) {
                                    modal.hide();
                                } else {
                                    console.log('Modal instance not found');
                                }
                                
                                // Show success message
                                showNotification(data.message, 'success');
                                
                                // Check if this is an admin login
                                if (data.redirect && (data.redirect.includes('/admin') || data.redirect.includes('/apoteker'))) {
                                    // This is an admin login, redirect to admin dashboard
                                    console.log('Admin login detected, redirecting to:', data.redirect);
                                    setTimeout(() => {
                                        window.location.href = data.redirect;
                                    }, 1500);
                                } else {
                                    // This is a customer login, update navbar
                                    console.log('Customer login detected, updating navbar with user data:', data.user);
                                    updateNavbar(data.user);
                                }
                                
                                // Clear form
                                loginForm.reset();
                                
                                console.log('Login process completed successfully');
                            } else {
                                console.log('Login failed:', data.message);
                                showNotification(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Login error details:', error);
                            console.error('Error stack:', error.stack);
                            showNotification('Terjadi kesalahan saat login: ' + error.message, 'error');
                        })
                        .finally(() => {
                            // Restore button
                            if (submitBtn) {
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                            }
                        });
                    });
                }
                
                // Alternative: Handle login button click
                const loginSubmitBtn = document.getElementById('loginSubmitBtn');
                if (loginSubmitBtn) {
                    console.log('Adding click event listener to login button');
                    loginSubmitBtn.addEventListener('click', function(e) {
                        console.log('Login button clicked');
                        // The form submit event should handle this, but this is a backup
                    });
                }

                // Handle logout
                const logoutButton = document.getElementById('logoutButton');
                if (logoutButton) {
                    logoutButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Logout button clicked');
                        
                        // Get CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        console.log('Logout CSRF Token:', csrfToken);
                        
                        if (!csrfToken) {
                            console.error('CSRF token not found for logout!');
                            showNotification('Token keamanan tidak ditemukan!', 'error');
                            return;
                        }
                        
                        fetch('{{ route("auth.logout") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            console.log('Logout response status:', response.status);
                            console.log('Logout response URL:', response.url);
                            
                            if (!response.ok) {
                                console.log('Logout response not OK, status:', response.status);
                            }
                            
                            return response.text().then(text => {
                                console.log('Logout raw response:', text);
                                
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Failed to parse logout JSON:', e);
                                    console.error('Logout response was:', text);
                                    throw new Error('Server returned invalid JSON for logout: ' + text.substring(0, 100));
                                }
                            });
                        })
                        .then(data => {
                            console.log('Logout response data:', data);
                            
                            if (data.success) {
                                showNotification(data.message, 'success');
                                updateNavbar(null); // Hide user info
                                
                                // Reset login form
                                const loginForm = document.getElementById('loginForm');
                                if (loginForm) {
                                    loginForm.reset();
                                }
                            } else {
                                showNotification(data.message || 'Logout gagal!', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Logout error details:', error);
                            console.error('Logout error stack:', error.stack);
                            showNotification('Terjadi kesalahan saat logout: ' + error.message, 'error');
                        });
                    });
                }

                // Check login status on page load
                checkLoginStatus();
            });

            // Function to show notifications
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info')} alert-dismissible fade show position-fixed`;
                notification.style.top = '20px';
                notification.style.right = '20px';
                notification.style.zIndex = '9999';
                notification.style.minWidth = '300px';
                
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-triangle' : 'info-circle')} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 4000);
            }

            // Function to update navbar based on login status
            function updateNavbar(user) {
                console.log('Updating navbar for user:', user);
                
                const loginSection = document.getElementById('loginSection');
                const userSection = document.getElementById('userSection');
                const userName = document.getElementById('userName');
                
                if (user) {
                    // Hide login button, show user dropdown
                    loginSection.style.display = 'none';
                    userSection.style.display = 'block';
                    
                    // Update user name
                    userName.textContent = user.name;
                    
                    // Update cart count in dropdown
                    updateCartCount(user.cart_count || 0);
                } else {
                    // Show login button, hide user dropdown
                    loginSection.style.display = 'block';
                    userSection.style.display = 'none';
                    
                    // Clear cart count
                    updateCartCount(0);
                }
            }

            // Function to update cart count badge
            function updateCartCount(count) {
                const cartLink = document.querySelector('a[href*="cart"]');
                if (cartLink) {
                    let cartBadge = cartLink.querySelector('.badge');
                    if (count > 0) {
                        if (!cartBadge) {
                            cartBadge = document.createElement('span');
                            cartBadge.className = 'badge bg-danger ms-1';
                            cartLink.appendChild(cartBadge);
                        }
                        cartBadge.textContent = count;
                        cartBadge.style.display = 'inline';
                    } else {
                        if (cartBadge) {
                            cartBadge.style.display = 'none';
                        }
                    }
                }
            }

            // Global function to update cart count (can be called from other pages)
            window.updateCartCount = updateCartCount;

            // Function to check login status
            function checkLoginStatus() {
                fetch('{{ route("auth.user-info") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in) {
                        updateNavbar(data.user);
                    }
                })
                .catch(error => {
                    console.log('Not logged in or error checking status');
                });
            }
        </script>