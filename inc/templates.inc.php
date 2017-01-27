<?php
if (IN_MANAGER_MODE != 'true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
require_once ENL_PATH.'classes/Templates.php';
$templates = new Templates();
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $response = '';
    $token = $subscribers->onlyChars($_GET['token']);
    $id = (int)$_GET['delete'];
    if ($_POST['token']){
        $_SESSION['token'] = $subscribers->onlyChars($_POST['token']);
    }
    if ($_SESSION['token']===$token){
        if ($templates->deleteTemplate($id)){
            echo 1;
            unset($_SESSION['token']);
        }
    }
    exit();
}
$id = (int)$_GET['edit'];
$tpls = $templates->getTemplates();
$tpl_templates = $twig->load('templates.html');
if (intval($id)){
    $template = $templates->getTemplate($id);
    $template['pagetitle'] = $lang['tpl_edit'];
}
else {
    $template = array(
        'id'=>NULL,
        'date'=>'NOW()',
        'title'=>$subscribers->onlyChars($_POST['title']),
        'description'=>$subscribers->onlyChars($_POST['description']),
        'code'=>$_POST['tvCode'],
        'pagetitle'=>$lang['tpl_create']
    );
}
$tinyMCE = $letters->renderTinyMCE('Code',$letters->toHtml($template['code']));
$vars = array(
    'lang' => $lang,
    'url' => ENL_MODULE_URL.'&act='.$act,
    'tinyMCE' => $tinyMCE['rte_html'],
    'get_id' => (int)$_GET['edit'],
    'template'=>$template,
    'templates'=>$tpls,
);
if ($_POST['sub']==1 && !in_array('', $_POST)){
    $_POST['code'] = str_replace($f, $r, $_POST['tvCode']);
    $_POST['code'] = $letters->fromHtml($_POST['code']);
    unset($_POST['tvCode']);
    $templates->InsOrUpdTemplate($_POST,$id);
}
$content = $tinyMCE['editor_html'];
$content .= $tpl_templates->render($vars);
