<?php
if (IN_MANAGER_MODE != 'true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
require_once(MODX_BASE_PATH . 'assets/modules/letters/inc/cfg.php');
if (file_exists($lng_file)) {
    require_once($lng_file);
} else {
    die("Не найден языковой файл! Проверьте конфинурацию модуля и файлы.");
}

require ENL_PATH . 'classes/Realemail.php';
require_once ENL_PATH . 'classes/Letters.php';
require_once ENL_PATH . 'classes/Categories.php';
require_once ENL_PATH . 'classes/Subscribers.php';

$realemail = new Realemail();
$letters = new Letters();
$categories = new Categories();
$subscribers = new Subscribers();

$sql = 'show tables';
$res = $modx->db->query($sql);
$res = $modx->db->makeArray($res);
$dbase = str_replace('`','',$modx->db->config['dbase']);
foreach ($res as $key => $val){
    $tbls[]=$val['Tables_in_'.$dbase];
}
if (!in_array($modx->db->config['table_prefix'].'letters_subscribers', $tbls)){
    require_once(ENL_PATH . 'inc/help.inc.php');
    echo $out;
    die;
}
$cats = $categories->getCategories();
$cat_id = '';
foreach ($cats as $cat) {
    $cat_id .= '<option value=' . $cat['id'] . '';
    $cat_id .= '>' . $cat['title'] . '</option>';
}
$out = '';
$title = $lang['title'];
$content = '';
$out .= '<!DOCTYPE html><html><head>
<title>[+title+]</title>
<link rel="stylesheet" type="text/css" href="'.MODX_MANAGER_URL.'/media/style/'.$modx->config['manager_theme'].'/style.css" />
<link rel="stylesheet" type="text/css" href="[+path+]libs/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="[+path+]libs/DataTables/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" href="media/style/common/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="[+path+]libs/DataTables/datatables.min.css"/>
<link rel="stylesheet" href="[+path+]css/style.css">

<script src="[+path+]libs/jquery-3.1.1.min.js"></script>
<script src="[+path+]libs/jquery-migrate-1.4.1.min.js"></script>
<script src="[+path+]libs/DataTables/datatables.min.js"></script>
<script src="[+path+]libs/bootstrap/js/bootstrap.min.js"></script>
<script src="[+path+]libs/bootstrap.file-input.js"></script>
<script>
function resetFormElement(e) {
  e.wrap("<form>").closest("form").get(0).reset();
  e.unwrap();
}

$(document).ready(function() {
    var subBtn = $("#sendDataSubs");
    var btnAddSub = $("#addSubscriber");
    var Modal = $("#modal");
    var ModalTitle = $(".modal-title");
    
    var sub_id = $("#sub_id");
    var email = $("#email");
    var cat_id = $("#cat_id");
    var firstname = $("#firstname");
    var lastname = $("#lastname");
    
    
    $("input[type=file]").bootstrapFileInput();
    
    var table = $("#example").DataTable( {
        "ajax": "' . ENL_MODULE_URL . '&act=1&do=subs",
        "stateSave": true,
        "lengthMenu": [[50,100,250,500, -1], [50,100,250,500, "Все"]],
        "columns": [
            { "data":"id"},
            { "data": "email" },
            { "data": "firstname" },
            { "data": "lastname" },  
            { "data":"buttons" }
        ],
        "processing": true,
        "language": {
            "url": "[+path+]languages/datatables/' . $lng . '.json"
        },
        "drawCallback": function () {
            $(".deleteSome").click(function() {
                var href = $(this).attr("href"); 
                var txt = $(this).attr("title");
                if (confirm(txt)) {
                    var token = Math.random().toString(36).substring(7);
                    var newUrl = href + "&token=" + token;
                    var that = $(this);
                    $.ajax({
                        url:newUrl,
                        type:"POST",
                        data:{"token": token},
                        success:function(txt){
                            if (txt==1){
                                table.ajax.reload();
                            }
                        }
                    })
                }
                return false;
            });
            $(".editSub").click(function(){
                $.ajax({
                    method: "GET",
                      url: $(this).attr("href"),
                      beforeSend: function(){
                        console.log("Before send");
                      }
                })
                .done(function( msg ) {
                    var res = $.parseJSON(msg);
                    sub_id.val(res.id);
                    email.val(res.email);
                    firstname.val(res.firstname);
                    lastname.val(res.lastname);
                    cat_id.html(res.cat_id);
                    ModalTitle.text("' . $lang['subscriber_edit_header'] . '")
                    Modal.modal();
                });
                return false;
            });
        }
    } );
    $("#cat_filter").change(function(){
        var v = $(this).val();
        var newUrl = "' . ENL_MODULE_URL . '&act=1&do=subs&cat_id=" + v;
        table.ajax.url( newUrl ).load();
        console.log(v);
    });
    btnAddSub.click(function(){
        sub_id.val("");
        email.val("");
        firstname.val("");
        lastname.val("");
        cat_id.html("' . $cat_id . '");
        ModalTitle.text("' . $lang['subscriber_add_header'] . '");
        Modal.modal();
        return false;
    });
    subBtn.click(function(){
        $.ajax({
          method: "POST",
          url: "' . ENL_MODULE_URL . '&act=1&do=form_subscriber",
          data: $(".formSubscriber").serialize(),
          beforeSend: function(){
            console.log("Before send");
          }
        })
          .done(function( msg ) {
            if (msg == 1){
                table.ajax.reload();
                Modal.modal("hide");
            }
          });
    });
    // CSV IMPORT
    var csv = $("#csv");
    var csvBtn = $("#csvImport");
    var startImport = $("#startImportCSV");
    var csvForm = $(".csv_import");
    var resp = $("#responseCSV");
    csvBtn.click(function(){   
        csv.modal();
    });
    startImport.click(function(){
        var formData = new FormData(csvForm[0]);
        var myVar = setInterval(function() {
            ls_ajax_progress(formData);
        }, 100);
        startImport.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: csvForm.attr("action"),
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) { 
                clearInterval(myVar);
                resp.html(data);
                startImport.removeAttr("disabled").html(\'' . $lang['import_files_again'] . '\');
                if (data == 100){
                    csv.modal("hide");
                    table.ajax.reload();
                }
            }
        });
        return false;
    });
    $("#refreshTable").click(function(){
        table.ajax.reload();
        console.log("Resresh Table Data");
    });
    function ls_ajax_progress(data) {
        /**
         * Выполняем AJAX запрос к скрипту опроса результата прогресса
         * @returns {Boolean}
         */
        $.ajax({
            type: "POST",
            data: data,
            async: true,
            cache: true,
            contentType: false,
            processData: false,
            url: csvForm.attr("action")+"&get=counter",
            success: function(data) {
                var json = $.parseJSON(data);
                resp.html(json.per+" <br>"+json.current+" / "+json.total);
            }
        });
 
        /**
         * На всякий случай вернем FALSE
         * @returns {Boolean}
         */
        return false;
    }
    
    // 2 раза в коде встречается, не гуд!
    $(".deleteSome").click(function() {
        var href = $(this).attr("href"); 
        var txt = $(this).attr("title");
        if (confirm(txt)) {
            var token = Math.random().toString(36).substring(7);
            var newUrl = href + "&token=" + token;
            var that = $(this);
            $.ajax({
                url:newUrl,
                type:"POST",
                data:{"token": token},
                success:function(txt){
                    if (txt==1){
                        that.parent().parent().remove();
                    }
                }
            })
        }
        return false;
    })
})
</script>
<meta charset="utf-8"></head>
 <body>
 <div class="sectionBody">
 <h1>[+title+]</h1>
    <div id="actions">
		<ul class="actionButtons">
			<li><a href="[+url+]&act=1"><i class="fa fa-users" aria-hidden="true"></i> ' . $lang['links_subscribers'] . '</a></li>
			<li><a href="[+url+]&act=2"><i class="fa fa-envelope" aria-hidden="true"></i> ' . $lang['links_newsletter'] . '</a></li>
			<li><a href="[+url+]&act=3"><i class="fa fa-tasks" aria-hidden="true"></i> ' . $lang['links_categories'] . '</a></li>
			<li><a href="[+url+]&act=7"><i class="fa fa-file-text-o" aria-hidden="true"></i> ' . $lang['links_templates'] . '</a></li>
			<li><a href="[+url+]&act=4"><i class="fa fa-gears" aria-hidden="true"></i></a></li>
			<li><a href="[+url+]&act=5"><i class="fa fa-question" aria-hidden="true"></i></a></li>
			<li><a href="#"><i class="fa fa-github" aria-hidden="true"></i></a></li>
        </ul>
	</div>
  [+content+]
 </div>
 </body>
</html>';
$act = (int)$_GET['act'];
if (!$act) header('Location:' . ENL_MODULE_URL . '&act=1');
switch ($act) {
    case 1:
        $title = $lang['links_subscribers'];
        require_once(ENL_PATH . 'inc/subscriber.inc.php');
        break;
    case 2:
        $title = $lang['links_newsletter'];
        require_once(ENL_PATH . 'inc/letters.inc.php');
        break;
    case 3:
        $title = $lang['links_categories'];
        require_once(ENL_PATH . 'inc/categories.inc.php');
        break;
    case 4:
        $title = $lang['links_configuration'];
        require_once(ENL_PATH . 'inc/settings.inc.php');
        break;
    case 5:
        $title = $lang['links_help'];
        require_once(ENL_PATH . 'inc/help.inc.php');
        break;
    case 6:
        require_once(ENL_PATH . 'inc/csv.import.inc.php');
        break;
    case 7:
        $title = $lang['links_templates'];
        require_once(ENL_PATH . 'inc/templates.inc.php');
        break;
}
$f = array(
    '[+title+]',
    '[+content+]',
    '[+path+]',
    '[+url+]'
);
$r = array(
    $title,
    $content,
    ENL_FRONTEND_PATH,
    ENL_MODULE_URL
);
echo str_replace($f, $r, $out);
