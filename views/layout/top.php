<?php echo $this->render('elements/header', array($title)); ?>
<body>
    <div id="header">
        <h1><a href="<?php echo $base_url; ?>/">Task Diary</a></h1>
    </div>

    <div id="nav">
        <p>
            <?php if ($session->isAuthenticated()): ?>
                <a href="<?php echo $base_url; ?>/">ホーム</a>
                <a href="<?php echo $base_url; ?>/settings/index">アカウント設定</a>
                <a href="<?php echo $base_url; ?>/account/logout">サインアウト</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>/account/login">ログイン</a>
                <a href="<?php echo $base_url; ?>/account/register">アカウント登録</a>
            <?php endif; ?>
        </p>
    </div>

    <div id="main">
        <?php echo $_content; ?>
    </div>
</body>
</html>
