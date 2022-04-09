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

        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">登録</h2>
                        <form method="POST" action="{{ route('insertUser') }}" class="register-form">
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="名前を入力してください" value="{{ old('name') }}"/>
                                @if($errors->has('name'))
                                    <label class="control-label" for="inputError" style="color: red; padding-left: 5px; margin-top:20px"><b>{{ $errors->first('name')}}</b></label><br/>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="メールを入力してください"/>
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
                                <label for="re_pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="もう一回パスワードを入力してください"/>
                                @if($errors->has('re_pass'))
                                    <label class="control-label" for="inputError" style="color: red; padding-left: 5px; margin-top:20px"><b>{{ $errors->first('re_pass')}}</b></label><br/>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="address"><i class="zmdi zmdi-home"></i></label>
                                <input type="address" name="address" id="address" value="{{ old('address') }}" placeholder="住所を入力してください"/>
                                @if($errors->has('address'))
                                    <label class="control-label" for="inputError" style="color: red; padding-left: 5px; margin-top:20px"><b>{{ $errors->first('address')}}</b></label><br/>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="phone"><i class="zmdi zmdi-phone"></i></label>
                                <input type="phone" name="phone" id="phone" value="{{ old('phone') }}" placeholder="電話番号を入力してください"/>
                                @if($errors->has('phone'))
                                    <label class="control-label" for="inputError" style="color: red; padding-left: 5px; margin-top:20px"><b>{{ $errors->first('phone')}}</b></label><br/>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="avatar"><i class="zmdi zmdi-comment-image"></i></label>
                                <div class="input-group">
                                    <input type="file" style="margin-bottom:5px" class="form-control" id="uploadRegister" value="{{ old('avatar') }}">
                                    <input type="hidden" name="avatar" id="register">
                                </div>
                                <div id="image_show_register" style="margin-top:10px">
                                </div>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="登録"/>
                            </div>
                            @csrf
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="/template/register/images/signup-image.jpg" alt="sing up image"></figure>
                        <a href="{{ route('loginUser') }}" class="signup-image-link"><b>アカウントがありました</b></a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JS -->
    <script src="/template/register/vendor/jquery/jquery.min.js"></script>
    <script src="/template/js/public.js"></script>
    <script>
        $('#uploadRegister').change(function () {
            const form = new FormData();
            form.append('file', $(this)[0].files[0]);

            $.ajax({
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'JSON',
                data: form,
                url: 'user/upload/services',
                success: function (results) {
                    console.log(results);
                    if (results.error === false) {
                            $('#image_show_register').html('<a href="' + results.url + '" target="_blank">' +
                                '<img src="' + results.url + '" width="100px"></a>')
                            $('#register').val(results.url);
                    } else {
                        alert('Upload File Lỗi');
                    }
                }
            });
        });
    </script>
</body>
</html>
