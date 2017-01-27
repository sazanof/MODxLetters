<?php
/*{
    @propetities
    "lng": [
    {
      "label": "Language",
      "type": "list",
      "value": "russian-UTF8",
      "options": "russian-UTF8,danish,english,german,italian",
      "default": "russian-UTF8",
      "desc": ""
    }
  ]
}*/
define ('MODULE_NAME','letters');
define ('ENL_PATH',MODX_BASE_PATH.'assets/modules/'.MODULE_NAME.'/');
define('ENL_FRONTEND_PATH','/assets/modules/'.MODULE_NAME.'/');
define('ENL_MODULE_URL',MODX_MANAGER_URL.'index.php?a=112&id='.(int)$_REQUEST['id']);
define('TBL_SUBSCRIBERS',$modx->getFullTableName('letters_subscribers'));
define('TBL_TEMPLATES',$modx->getFullTableName('letters_templates'));
define('TBL_LETTERS',$modx->getFullTableName('letters_newsletter'));
define('TBL_CATEGORIES',$modx->getFullTableName('letters_categories'));
$lng = isset($lng) ? $lng : 'russian-UTF8';
$lng_file = ENL_PATH.'languages/'.$lng.'.php';
require_once (ENL_PATH.'vendor/autoload.php');
$loader = new Twig_Loader_Filesystem(ENL_PATH.'templates');
$twig = new Twig_Environment($loader, array(
    // 'cache' => PATH.'cache'
));
$conf['emailsender'] = $modx->config['emailsender'];
$conf['email_method'] = $modx->config['email_method'];
$conf['smtp_auth'] = $modx->config['smtp_auth'];
$conf['smtp_host'] = $modx->config['smtp_host'];
$conf['smtp_port'] = $modx->config['smtp_port'];
$conf['smtp_username'] = $modx->config['smtp_username'];
$conf['smtp_secure'] = $modx->config['smtp_secure'];
$conf['smtppw'] = $modx->config['smtppw'];