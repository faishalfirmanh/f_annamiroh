<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Jamaah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center">
        <div class="login-card">
            <h3 class="text-center mb-4">Login Akses</h3>
            
            <div id="alert-message" class="alert d-none" role="alert"></div>

            <form id="formLogin">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Masukkan username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg" id="btnLogin">Masuk</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#formLogin').on('submit', function(e){
                e.preventDefault(); // Mencegah form reload

                let btn = $('#btnLogin');
                let alertMsg = $('#alert-message');
                let formData = $(this).serialize();

                // Ubah state tombol saat loading
                btn.html('Memproses...').prop('disabled', true);
                alertMsg.addClass('d-none').removeClass('alert-danger alert-success');

                $.ajax({
                    url: "<?= site_url('JamaahLinkShare/login_api') ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response){
                        btn.html('Masuk').prop('disabled', false);
                        
                        if(response.status === 'success'){
                            alertMsg.addClass('alert-success').removeClass('d-none').html(response.message);
                            // Redirect ke dashboard setelah 1 detik
                            setTimeout(function(){
                                window.location.href = "<?= site_url('JamaahLinkShare/dashboard') ?>";
                            }, 1000);
                        } else {
                            alertMsg.addClass('alert-danger').removeClass('d-none').html(response.message);
                        }
                    },
                    error: function(){
                        btn.html('Masuk').prop('disabled', false);
                        alertMsg.addClass('alert-danger').removeClass('d-none').html('Terjadi kesalahan pada server.');
                    }
                });
            });
        });
    </script>
</body>
</html>
