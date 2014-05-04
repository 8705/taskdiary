<?php

/**
 * PerfectApplication.
 *
 * @author 8705
 */
class PerfectApplication extends Application
{
    protected $login_action = array('account', 'index');

    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    protected function registerRoutes()
    {
        return array(
            '/'
                => array('controller' => 'project', 'action' => 'index'),
            '/:controller/'
                => array('action' => 'index'),
            '/:controller/:action'
                => array(),
            '/:controller/:action/:property'
                => array(),
            '/:controller/:action/:property/:property2'
                => array(),
        );
    }

    protected function configure()
    {
        include 'configure.php';
    }
}
