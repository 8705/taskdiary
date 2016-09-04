<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php if (isset($title)): echo $this->escape($title) . ' - '; endif; ?>Task Diary</title>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
    <link href='http://fonts.googleapis.com/css?family=Damion' rel='stylesheet' type='text/css'>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/taskdiary.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/moment.min.js"></script>
    <?php if($_SERVER["SERVER_NAME"] === 'taskdiary.8705.co'): ?>
    <script src="/js/google_analytics.js"></script>
    <?php endif; ?>
    <!-- <script src="/js/desktop-notify.js"></script> -->
    <link rel="shortcut icon" href="/favicon.ico">
</head>
