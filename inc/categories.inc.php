<?php
if (IN_MANAGER_MODE != 'true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
$tpl_categories = $twig->load('categories.html');
$num = $_GET['edit'];
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $response = '';
    $token = $subscribers->onlyChars($_GET['token']);
    $id = (int)$_GET['delete'];
    if ($_POST['token']) {
        $_SESSION['token'] = $subscribers->onlyChars($_POST['token']);
    }
    if (!empty($_SESSION['token']) && $_SESSION['token']===$token){
        if ($categories->deleteCategory($id)){
        $where = "WHERE CONCAT(',',cat_id,',') LIKE '%," . $id . ",%'";
        $subs = $subscribers->getSubscribers($where);
        foreach ($subs as $sub){
            $f = array(
                'cat_id' => $categories->catAfterDelete($sub['cat_id'])
            );
            $subscribers->update(TBL_SUBSCRIBERS, $f, $where);
        }
        $ltrs = $letters->getLetters(TBL_LETTERS,$where);
        foreach ($ltrs as $ltr){
            $f = array(
                'cat_id' => $categories->catAfterDelete($ltr['cat_id'])
            );
            $subscribers->update(TBL_LETTERS, $f, $where);
        }
        
        unset($_SESSION['token']);

        echo 1;
        }
    }
    exit();
}
$cat = $categories->getCategories(false, 'id DESC');
# Редактирование категории
if (intval($num)) {
    $ctg = $categories->getCategory($num);
    $ctg['pagetitle'] = $lang['cat_edit'];
} # Новая категория и пустая форма
else {
    $ctg = array(
        'id' => '',
        'title' => $modx->db->escape($_POST['title']),
        'description' => $modx->db->escape($_POST['description']),
        'pagetitle' => $lang['cat_add']
    );
}
$vars = array(
    'categories' => $cat,
    'category' => $ctg,
    'lang' => $lang,
    'url' => ENL_MODULE_URL . '&act=' . $act,
    'get_id' => $num
);
if ($_POST['sub'] == 1 && !in_array('', $_POST)) {
    $categories->InsOrUpdCategory($_POST, $ctg['id']);
}
$content = $tpl_categories->render($vars);