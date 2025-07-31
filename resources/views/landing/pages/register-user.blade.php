<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daftar Pelanggan - Apotek Sakura</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
       <link rel="icon" href="{{ asset('landing/assets/sakurapotek2.ico') }}" />
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" />
    
    <style>
        body {
            background-color: #ffffff;
            font-family: "Roboto Slab", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .register-container {
            min-height: 100vh;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .register-card {
            background: #ffffff;
            border: 1px solid #e3f2fd;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(25, 118, 210, 0.08);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .register-header h2 {
            color: #198754;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 12px;
        }
        
        .register-header p {
            color: #666666;
            margin: 0;
            font-size: 15px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 100%;
            background-color: #ffffff;
        }
        
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
            outline: none;
            background-color: #ffffff;
        }
        
        .form-control:hover {
            border-color: #198754;
        }
        
        .form-control.is-invalid {
            border-color: #d32f2f;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
        }
        
        .form-control.is-invalid:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.2);
        }
        
        .btn-register {
            background: #198754;
            color: #ffffff;
            border: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 20px;
            cursor: pointer;
        }
        
        .btn-register:hover {
            background: #157347;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
        }
        
        .btn-register:active {
            transform: translateY(1px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }
        
        .login-link a {
            color: #198754;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #157347;
            text-decoration: underline;
        }
        
        .back-home {
            position: absolute;
            top: 30px;
            left: 30px;
            color: #198754;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .back-home:hover {
            color: #157347;
            background-color: #d1e7dd;
            text-decoration: none;
        }
        
        .text-danger {
            color: #d32f2f;
        }
        
        .text-muted {
            color: #666666;
        }
        
        /* Alert styles for validation messages */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid;
        }
        
        .alert-danger {
            background-color: #ffebee;
            border-color: #ffcdd2;
            color: #c62828;
        }
        
        .alert-success {
            background-color: #e8f5e8;
            border-color: #c8e6c9;
            color: #2e7d32;
        }
        
        .alert ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }
        
        .alert li {
            margin-bottom: 4px;
        }
        
        @media (max-width: 768px) {
            .register-container {
                padding: 20px 15px;
            }
            
            .register-card {
                padding: 30px 20px;
                margin: 0;
            }
            
            .register-header h2 {
                font-size: 24px;
            }
            
            .back-home {
                position: relative;
                top: auto;
                left: auto;
                color: #1976d2;
                margin-bottom: 20px;
                justify-content: center;
                background-color: #e3f2fd;
                border-radius: 8px;
            }
        }
        
        @media (max-width: 576px) {
            .register-header {
                margin-bottom: 25px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <a href="{{ url('/') }}" class="back-home">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Beranda
        </a>
        
        <div class="register-card">
            <div class="register-header">
                <h2>Daftar Pelanggan</h2>
                <p>Bergabunglah dengan Apotek Sakura untuk pengalaman belanja yang lebih baik</p>
            </div>
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('user.register') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_pelanggan" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                   id="nama_pelanggan" 
                                   name="nama_pelanggan" 
                                   value="{{ old('nama_pelanggan') }}"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   placeholder="Masukkan username"
                                   required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telpon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="tel" 
                                   class="form-control @error('telpon') is-invalid @enderror" 
                                   id="telpon" 
                                   name="telpon" 
                                   value="{{ old('telpon') }}"
                                   placeholder="Masukkan nomor telepon"
                                   required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('kota') is-invalid @enderror" 
                                   id="kota" 
                                   name="kota" 
                                   value="{{ old('kota') }}"
                                   placeholder="Masukkan kota"
                                   required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                              id="alamat" 
                              name="alamat" 
                              rows="3" 
                              placeholder="Masukkan alamat lengkap"
                              required>{{ old('alamat') }}</textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password"
                                   required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Konfirmasi password"
                                   required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-register">
                    <i class="fas fa-user-plus me-2"></i>
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="login-link">
                <p class="text-muted">Sudah punya akun? <a href="{{ url('/') }}">Kembali ke beranda untuk login</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>