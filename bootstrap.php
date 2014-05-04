<?php

require 'core/ClassLoader.php';
require 'utility/develop.php';

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/controllers');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();
