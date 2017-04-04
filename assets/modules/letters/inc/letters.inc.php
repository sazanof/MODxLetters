<?php
if (IN_MANAGER_MODE != 'true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
$num = intval($_GET['edit']);
# Предпросмотр, удаление, короче все, что приходит аяксом
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $token = $subscribers->onlyChars($_GET['token']);
    $id = (int)$_GET['delete'];
    if ($_POST['token']){
        $_SESSION['token'] = $subscribers->onlyChars($_POST['token']);
    }
    if (!empty($_SESSION['token']) && $_SESSION['token']===$token){
        if ($letters->deleteLetter($id) and isset($_GET['delete'])){
            echo 1;
            unset($_SESSION['token']);
        }
    }
    if (isset($num)){
        // если нажата кнопка отправить
        // инклуд отправки письма
        if (preg_match('@[a-z]@u',$_GET['send'])){
            require_once ENL_PATH.'inc/send.inc.php';
        }
        $letter = $letters->getLetter($num);
        $res = $letters->generateTplFromCode($letter['template'],$letter['newsletter']);
        echo $res;
    }
    exit;
}
$tpl_letters = $twig->load('letters.html');
$letters_list = $letters->getLetters(TBL_LETTERS,false,'id DESC');
$letter = array();
if (intval($num)){
    $letter = $letters->getLetter($num);
    $letter['cat_id'] = explode(',',$letter['cat_id']);
    $lang['editOrNew'] = $lang['letter_edit'];
}
else {
    // формируем пустой массив letter
    $letter = array(
        'id' => NULL,
        'template' => $_POST['template'],
        'pagetitle' => $pagetitle,
        'subject' => $lang['letter_base_title'],
        'newsletter' => $lang['letter_base_body'],
        'cat_id' => $_POST['categories']
    );
    $lang['editOrNew'] = $lang['letter_create'];
}
// tinyMCE инициализация
$tinyMCE = $letters->renderTinyMCE('Body',htmlspecialchars_decode($letter['newsletter']));
$content = $tinyMCE['editor_html'];
$letter['tinyMCE'] = $tinyMCE['rte_html'];
$vars = array(
    'lang' => $lang,
    'url' => ENL_MODULE_URL.'&act='.$act,
    'get_id' => (int)$_GET['edit'],
    'letter' => $letter,
    'letters' => $letters_list,
    'templates' => $letters->getTemplates(),
    'categories' => $categories->getCategories()
);
$content .= $tpl_letters->render($vars);
if ($_POST['sub']==1 && !in_array('', $_POST)){
    $_POST['newsletter'] = str_replace($f, $r, $_POST['tvBody']);
    $_POST['newsletter'] = $letters->fromHtml($_POST['newsletter']);
    unset($_POST['tvBody']);
    $letters->InsOrUpdLetter(TBL_LETTERS, $_POST,$letter['id']);
}