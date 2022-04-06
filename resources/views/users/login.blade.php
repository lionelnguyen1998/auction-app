
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <!-- Font Icon -->
    <link rel="stylesheet" type="text/css" href="/template/fonts/iconic/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="/template/register/css/style.css">
</head>
<body>

    <div class="main">
        <!-- Sing in  Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="/template/register/images/signin-image.jpg" alt="sing up image"></figure>
                        <a href="{{ route('registerUser') }}" class="signup-image-link"><b>アカウントを登録</b></a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">ログイン</h2>
                        <label id="error-all" style="color: red; padding-left: 5px; margin-top:20px"><b></b></label><br/>

                        <div class="register-form">
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="text" name="email" id="email" placeholder="メールを入力してください"/>
                               
                                <label class="control-label" for="inputError" id="error-email" style="color: red; padding-left: 5px; margin-top:20px"><b></b></label><br/>
                            
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="パスワードを入力してください"/>
                              
                                <label class="control-label" for="inputError" id="error-password" style="color: red; padding-left: 5px; margin-top:20px"><b></b></label><br/>
                             
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="remember-me" id="remember-me" class="agree-term" />
                                <label for="remember-me" class="label-agree-term"><span><span></span></span>レメンバ</label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="ログイン"/>
                            </div>
                        </div>
                        <div class="social-login">
                            <ul class="socials">
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-twitter"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script>
        let inMemoryToken;
        var loginBlock = document.querySelector('.form-submit');
        loginBlock.addEventListener('click', (e) => {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            e.preventDefault();
            var loginApi = 'http://localhost:8080/api/login';
            fetch(loginApi, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json, text/plain,*/*',
                        'Content-Type': 'application/json',
                        'redirect': 'manual',
                    },
                    body: JSON.stringify({
                        "email": email,
                        "password": password,
                    })
                })
                .then(function(res) {
                    return res.json();
                })
                .then(res => {
                    if (res.error) {
                        document.getElementById('error-all').innerHTML = "Mật khẩu hoặc email không đúng ):";
                    } else if (res.errors) {
                        if (res.errors.password && res.errors.email) {
                            document.getElementById('error-password').innerHTML = res.errors.password;
                            document.getElementById('error-email').innerHTML = res.errors.email;
                        } else if (res.errors.email) {
                            document.getElementById('error-email').innerHTML = res.errors.email;
                        } else {
                            document.getElementById('error-password').innerHTML = res.errors.password;
                        }
                    } else {
                        let inMemoryToken = res.access_token;
                        localStorage.setItem('token', JSON.stringify(res));
                        return window.location.href="http://localhost:8080/";
                    }
                }) 
        }); 
    </script>
    <script src="/template/register/vendor/jquery/jquery.min.js"></script>
    <script src="/template/register/js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>