<?php
$conf_tpl = $twig->load('settings.html');
$vars = array(
    'lang'=>$lang,
    'conf'=>$conf
);
$content = $conf_tpl->render($vars);