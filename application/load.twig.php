<?php
/**
 * Created by PhpStorm.
 * User: sa
 * Date: 3/30/15
 * Time: 12:15 AM
 */

if ($settings['cache_enable'] == true) {
    $twig_env['cache'] = $settings['cache_path'];
}

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/templates/'.$settings['template_name']);
$twig = new Twig_Environment($loader, $twig_env);
