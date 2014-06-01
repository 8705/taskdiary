<?php echo $this->render('elements/header', array($title)); ?>
<body>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <h1><a class="navbar-brand" href="<?php echo $base_url; ?>/">Task Diary</a></h1>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/account/login">ログイン</a></li>
                <li><a href="/account/register">アカウント登録</a></li>
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if ($session->isAuthenticated()): ?>
                        <li><a href="<?php echo $base_url; ?>/account/login">ログイン</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $base_url; ?>/account/register">アカウント登録</a></li>
                        <?php else: ?>
                        <?php endif; ?>
                    </ul>
                </li> -->
            </ul>
        </div>
    </nav>
    <div id="main">
        <?php echo $_content; ?>
    </div>
</body>
</html>
