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
                        @include('alert')

                        <form method="POST" action="{{ route('storeUserAccount') }}" class="register-form">
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="text" name="email" id="email" placeholder="メールを入力してください"/>
                                @if($errors->has('email'))
                                    <label class="control-label" for="inputError" style="color: red; padding-left: 5px; margin-top:20px"><b>{{ $errors->first('email')}}</b></label><br/>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="パスワードを入力してください"/>
                                @if($errors->has('password'))
                                    <label class="control-label" for="inputError" style="color: red; padding-left: 5px; margin-top:20px"><b>{{ $errors->first('password')}}</b></label><br/>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="remember-me" id="remember-me" class="agree-term" />
                                <label for="remember-me" class="label-agree-term"><span><span></span></span>レメンバ</label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="ログイン"/>
                            </div>
                            @csrf
                        </form>
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
    <script src="/template/register/vendor/jquery/jquery.min.js"></script>
    <script src="/template/register/js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
