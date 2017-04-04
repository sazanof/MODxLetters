<?php
if (IN_MANAGER_MODE != 'true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $do = strip_tags(htmlspecialchars($_GET['do']));
    switch ($do) {
        case 'subs':
            if ((int)$_GET['num']) {
                $sql = $modx->db->select('id,firstname,lastname,email,cat_id', TBL_SUBSCRIBERS, 'id=' . (int)$_GET['num']);
                $res = $modx->db->getRow($sql);
                $c = explode(',',$res['cat_id']);
                $cats = $categories->getCategories();
                foreach ($cats as $cat){
                    $res['cat_id'] .= '<option value="'.$cat['id'].'"';
                    if (is_array($c)){
                        if (in_array($cat['id'], $c)){
                            $res['cat_id'].=' selected="selected" ';
                        }
                    }
                    $res['cat_id'] .= '>'.$cat['title'].'</option>';
                }
                if ($modx->db->getRecordCount($sql) == 1) {
                    $res = json_encode($res);
                    echo $res;
                }
                exit;
            }
            $data = '';
            $cat_int = $_GET['cat_id'];
            if (intval($cat_int)){
                $where = "WHERE CONCAT(',',cat_id,',') LIKE '%,".$cat_int.",%'";
            }
            else {
                $where = '';
            }
            $subs = $modx->db->select('id,firstname,lastname,email,cat_id', TBL_SUBSCRIBERS,$where);
            $res = $modx->db->makeArray($subs);
            $i = 0;
            foreach ($res as $row) {
                $res[$i]['buttons'] = '<a class="btn btn-success fa fa-pencil editSub" href="' . ENL_MODULE_URL . '&act=1&do=subs&num=' . $row['id'] . '"></a>
            <a title="'.$lang['delete_subscriber'].'" class="btn btn-danger fa fa-minus-circle deleteSome" href="' . ENL_MODULE_URL . '&act=1&delete='.$row['id'].'" aria-hidden="true"></a>';;
                $i++;
            }
            $res = array(
                "data" => $res
            );
            $data = json_encode($res, JSON_NUMERIC_CHECK);
            echo $data;
            exit;
            break;
        case 'form_subscriber':
            // пишем сюда
            $email = $modx->db->escape($_POST['email']);
            $firstname = $modx->db->escape($_POST['firstname']);
            $lastname = $modx->db->escape($_POST['lastname']);
            $cat_id = @implode(',',$_POST['cat_id']);
            $sql = "INSERT INTO " . TBL_SUBSCRIBERS . " 
                (email, firstname, lastname, cat_id, created) VALUES ('" . $email . "','" . $firstname . "','" . $lastname . "','" . $cat_id . "', NOW())
                ON DUPLICATE KEY UPDATE 
                email='$email',
                firstname='$firstname',
                lastname='$lastname',
                cat_id='$cat_id',
                created=NOW()
            ";
            //echo $sql;
            if (filter_var($email,FILTER_VALIDATE_EMAIL)){
                // add realemail check
                if ($realemail->checkEmail($email) == 1 or $realemail->checkEmail($email) == 2){
                    if ($modx->db->query($sql)){
                        echo 1;
                    }
                }
                else {
                    echo $email.' не существует в природе.';
                }
            }
            else {
                echo 'Не заполнен email';
            }
            exit;
            break;
    }
    $id = intval($_GET['delete']);
    if ($id){
        if ($subscribers->deleteSubscriber($id)){
            echo 1;
            exit;
        }
    }
}
$cat_f = $categories->getCategories();
foreach ($cats as $cat){
    $cat_filter .= '<option value="'.$cat['id'].'">'.$cat['title'].'</option>';
}
$html = '
<div class="panel panel-default">
    <div class="panel-body">
        <div class="buttons_act">
                <a href="#" id="addSubscriber" class="btn btn-success">
                <i class="fa fa-user-plus" aria-hidden="true"></i> ' . $lang['subscriber_add_header'] . '
                </a>

                <a href="#" id="csvImport" class="btn btn-default">
                <i class="fa fa-cloud-download" aria-hidden="true"></i> ' . $lang['import_title'] . '
                </a>
                
                <a href="#" id="refreshTable" class="btn btn-default">
                <i class="fa fa-refresh" aria-hidden="true"></i>
                </a>
                
                <select name="cat_filter" id="cat_filter" class="form-control floatright" style="width:300px; margin-left:5px;display:inline-block">
                    <option>'.$lang['filter_by_cat'].'</option>
                    '.$cat_filter.'
                </select>
        </div>
        <div class="tbl_subscribers">
            <table id="example" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Email</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#ID</th>
                        <th>Email</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Действия</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div id="csv" class="modal fade">
    <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <h4 class="modal-title">' . $lang['links_import'] . '</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
            <div class="alert alert-warning">'.$lang['import_info'].'</div>
            <div id="responseCSV"></div>
            <form class="csv_import" method="POST" action="' . ENL_MODULE_URL . '&act=6&do=csv_subs" enctype="multipart/form-data">
                <input type="file" title="' . $lang['choosefile'] . '" id="csvfile" name="csvfile">
            </form>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="closeFormImport" data-dismiss="modal">' . $lang['cancel'] . '</button>
        <button type="button" id="startImportCSV" class="btn btn-danger">' . $lang['import_sub'] . '</button>
      </div>
    </div>
  </div>
</div>
<div id="modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Заголовок модального окна -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Заголовок модального окна</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class="modal-body">
        <form method="post" class="formSubscriber">
            <input type="hidden" name="sub_id" id="sub_id" value="">
            <div class="input">
                <label for="email">' . $lang['subscriber_email'] . '</label>
                <input type="email" class="form-control" name="email" id="email" value="" placeholder="' . $lang['subscriber_email_txt'] . '">
            </div>
            <div class="input">
                <label for="firstname">' . $lang['subscriber_firstname'] . '</label>
                <input type="firstname" class="form-control" name="firstname" value="" id="firstname" placeholder="' . $lang['subscriber_firstname_txt'] . '">
            </div>
            <div class="input">
                <label for="lastname">' . $lang['subscriber_lastname'] . '</label>
                <input type="lastname" class="form-control" name="lastname" value="" id="lastname" placeholder="' . $lang['subscriber_lastname_txt'] . '">
            </div>
            <div class="input">
                <label for="cat_id">' . $lang['links_categories'] . '</label>
                <select multiple="multiple" name="cat_id[]" class="form-control" id="cat_id">
                    <option>'.$lang['not_choose'].'</option>
                    '.$cat_id.'
                </select>
            </div>
        </form>
      </div>
      <!-- Футер модального окна -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">' . $lang['cancel'] . '</button>
        <button type="button" id="sendDataSubs" class="btn btn-primary">' . $lang['savachanges'] . '</button>
      </div>
    </div>
  </div>
</div>';
$content .= $html;