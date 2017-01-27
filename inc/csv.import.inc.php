<?php
if(IN_MANAGER_MODE!='true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $do = strip_tags(htmlspecialchars($_GET['do']));
    $str = '';
    switch ($do){
        case 'csv_subs':
            if ($_GET['get']=='counter'){
                if (is_array($_SESSION['process'])){
                    echo json_encode($_SESSION['process']);
                    exit;
                }
            }
            //$_SESSION['prosess'] = 0;
            $file = $_FILES['csvfile'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $ar = array();
            if ($file['type']==='application/vnd.ms-excel' && $ext === 'csv'){
                $tmpfilename = htmlspecialchars($file['tmp_name']);
                $filename = htmlspecialchars($file['name']);
                if ($file['tmp_name']!=='' && !in_array('',$_POST)){
                    $content = file($tmpfilename);
                    $i = 0;
                    foreach ($content as $line){
                        $item = explode(';',$line);
                        if (filter_var($item[0],FILTER_VALIDATE_EMAIL)){
                            $ar[$i]['email'] = $item[0];
                            $ar[$i]['firstname'] = $item[1];
                            $ar[$i]['lastname'] = $item[2];
                        }
                        $i++;
                    }
                    $j = 0;
                    $total = count($ar);
                    if (count($ar)){
                        foreach ($ar as $item){
                            session_start();
                            $email = $modx->db->escape($item['email']);
                            $lastname = $modx->db->escape($item['lastname']);
                            $firstname = $modx->db->escape($item['firstname']);
                            $sql = "INSERT INTO `".TBL_SUBSCRIBERS."` 
                            (email, firstname, lastname, created) VALUES ('".$email."','".$firstname."','".$lastname."', NOW())
                            ON DUPLICATE KEY UPDATE 
                                email='$email',
                                firstname='$firstname',
                                lastname='$lastname',
                                created=NOW()
                            ";
                            if ($modx->db->query($sql)){
                                $per = round( (100 * ($j+1)) / $total );
                                $_SESSION['process'] = array(
                                    'total' => $total,
                                    'current' => ($j+1),
                                    'per' => $per,
                                    'email' => $item['email']
                                );
                            }
                            $j++;
                            session_write_close();
                            usleep(10000);
                        }
                    }
                    echo 100;
                    exit;

                }
                else {
                    unset($_SESSION['process']);
                    echo $lang['import_not_choose'];
                }
            }
            else {
                echo $lang['import_files_wrongtype'];
            }
            break;
    }
    exit;
}