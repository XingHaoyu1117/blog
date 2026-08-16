<?php
require __DIR__.'/../inc/bootstrap.php';

if(!admin()){
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();

    $_SESSION=[];

    if(ini_get('session.use_cookies')){
        $params=session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time()-42000,
            $params['path'],
            $params['domain']??'',
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ADMIN LOGOUT</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<header>
    <div class="brand">BLOG // ADMIN TERMINAL</div>
    <nav>
        <a href="index.php">DASHBOARD</a>
        <a href="logout.php">LOGOUT</a>
    </nav>
</header>

<main class="container">
    <div class="card">
        <p class="status">&gt; SYSTEM / SESSION</p>

        <h1>[ LOGOUT ]</h1>

        <p>&gt; CURRENT SESSION: <span class="blink">ACTIVE</span></p>
        <p>&gt; USER: ADMIN</p>
        <p>&gt; WARNING: SESSION WILL BE TERMINATED.</p>

        <div class="admin-nav">
            <form method="post" style="margin:0;">
                <?=csrf_field()?>
                <button type="submit">EXECUTE LOGOUT</button>
            </form>

            <a href="index.php">CANCEL</a>
        </div>

        <p class="status">&gt; awaiting command<span class="blink">_</span></p>
    </div>
</main>

<footer>
    ADMIN TERMINAL // SESSION CONTROL
</footer>

</body>
</html>
