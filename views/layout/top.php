<?php echo $this->render('elements/header', array($title)); ?>
<body id="top">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <h1><a class="navbar-brand" href="<?php echo $base_url; ?>/">Task Diary</a></h1>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/top/index">今日のタスク</a></li>
                <!-- <li><a href="/top/future?nav=0">未来のタスク</a></li> -->
                <li><a href="/top/past?nav=0">過去のタスク</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $base_url; ?>/settings/index">アカウント設定</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $base_url; ?>/account/logout">サインアウト</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div id="main">
        <?php echo $_content; ?>
    </div>
    <script>
    if (Notification.permission === 'default') {
      Notification.requestPermission(function(result) {
        // if (result === 'denied') {
        //   alert('リクエスト結果：通知許可されませんでした');
        // } else if (result === 'default') {
        //   alert('リクエスト結果：通知可能か不明です');
        // } else if (result === 'granted') {
        //   alert('リクエスト結果：通知許可されました！！');
        // }
      })
    }
    </script>
</body>
</html>
