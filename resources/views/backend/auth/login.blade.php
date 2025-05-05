<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>login</title>

    <link href="backend/css/bootstrap.min.css" rel="stylesheet">
    <link href="backend/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="backend/css/animate.css" rel="stylesheet">
    <link href="backend/css/style.css" rel="stylesheet">
    <link href="backend/css/custom.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">TRANG QUẢN TRỊ</h2>

                <p>
                    BẠN VUI LÒNG NHẬP ĐỊA CHỈ EMAIL VÀ PASSWORD ĐỂ TIẾN HÀNH ĐĂNG NHẬP VÀO TRANG QUẢN TRỊ 
                </p>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    
                    <form class="m-t" role="form" method="POST" action="{{ route('auth.login') }}">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name = "email"
                                class="form-control" 
                                placeholder="Username"
                                value="{{ old('email') }}"
                            >
                            @if ($errors->has('email'))
                                <span class="error-message">* {{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input 
                                type="password" 
                                name = "password"
                                class="form-control" 
                                placeholder="Password" 
                            >
                            @if ($errors->has('password'))
                            <span class="error-message">* {{ $errors->first('password') }}</span>
                        @endif
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        
    </div>

</body>

</html>
