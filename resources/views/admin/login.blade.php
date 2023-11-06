<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    <title>Вход в панель администратора</title>
    <meta id="csrf" name="csrf-token" content="{{ csrf_token() }}">
    <link href="/dist/css/pages/login-register-lock.css" rel="stylesheet">
    <link href="/dist/css/style.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-default card-no-border">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{{ env('APP_NAME') }}</p>
    </div>
</div>
<section id="wrapper">
    <div class="login-register">
        <div class="login-box card">
            <div class="card-body">
                <form class="form-horizontal form-material" id="loginform" action="{{ route('admin_auth') }}"
                      method="POST">
                    @csrf
                    <h3 class="text-center m-b-20">Вход в панель администратора</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="phone" class="form-control tel" name="phone" type="text" required=""
                                   placeholder="Введите телефон администратора"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password" required=""
                                   placeholder="Введите пароль администратора"></div>
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit">Войти</button>
                        </div>
                    </div>
                    <div onclick="getNewAdminPass(this);" style="cursor: pointer">Получить новый пароль по СМС</div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="/assets/node_modules/popper/popper.min.js"></script>
<script src="/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(function () {
        $(".preloader").fadeOut();
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $('#to-recover').on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
</script>
<script src="/js/select.js"></script>
<script>
    async function getNewAdminPass(block) {
        let data = {
            "phone": document.getElementById('phone').value
        }
        let url = '{{ route('admin_new_pass') }}';
        let res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': document.getElementById('csrf').content
            },
            body: JSON.stringify(data)
        });
        let result = await res.json();
        if (result.ok && !result.error) {
            block.innerText = result.message
        } else {
            block.style.color = 'red';
            block.innerText = result.error;
        }
        block.onclick = '';
        block.style.cursor = 'default';
    }
</script>
</body>
</html>
