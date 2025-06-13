<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會員登入</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-bg: #ffffff;
            --color-surface: #F9F7F7;
            --color-border: #DBE2EF;

            --color-primary: #3F72AF;
            --color-primary-light: #5B8BD6;

            --color-accent: #E1B822;

            --color-text: #2c2c2c;
            --color-text-secondary: #64748b;
            --color-text-inverse: #1e293b;

            --box-shadow: rgba(63, 114, 175, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: "Noto Sans TC", sans-serif;
            background: linear-gradient(to top right, rgb(141, 155, 179) 0%, var(--color-primary-light) 100%);
            background-repeat: no-repeat;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: var(--color-border);

        }
    </style>
</head>

<body>
    <div class="container w-25 p-4 rounded-4">
        <form action="./doLogin.php" method="post">
            <h1 class="border-bottom border-white pb-4 text-center">登入</h1>
            <label for="input-account" class="form-label mt-2   ">帳號</label>
            <input type="text" name="account" id="input-account" class="form-control" placeholder="請輸入帳號" required>
            <label for="input-password1" class="form-label mt-2">密碼</label>
            <input type="password" name="password1" id="input-password1" class="form-control" placeholder="請輸入密碼" required>
            <input type="password" name="password2" class="form-control mt-3" placeholder="再次輸入密碼">
            <div class="border-bottom border-white text-center my-4"></div>
            <div class="text-center">
                <button class="btn btn-primary btn-send me-3">登入</button>
                <a class="btn btn-warning btn-send">註冊</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous">
    </script>
</body>

</html>