<?php
error_reporting(0);
/*
 * set_time_limit установить в тестовом режиме, чтобы проверить отправку в цикле с сохранением последнего отправленного письма
 * пример (10 сек.)
 * set_time_limit(10);
 */
set_time_limit(10);

if (IN_MANAGER_MODE != 'true' && !$modx->hasPermission('exec_module')) die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.');
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $id = (int)$_GET['edit'];
    $who = strip_tags($_GET['send']);
    $file = ENL_PATH.'cache/'.$who.'_'.$id.'.tmp';
    $subscribers->send_file = $file;
    $getLast = $subscribers->getLastLetterSend();
    $ar = array();
    if (is_array($getLast)){
        $limit = $getLast['id'].','.$getLast['total'];
    }
    else {
        $limit = false;
    }
    switch ($who) {
        case 'all':
            $subs = $subscribers->getSubscribers(false,"id ASC",$limit);
            break;
        case 'cat':
            $cat_id = (int)$_GET['cat_id'];
            $subs = $subscribers->getSubscribers("WHERE CONCAT(',',cat_id,',') LIKE '%," . $cat_id . ",%'","id ASC",$limit);
            break;
    }
    if (is_array($subs) || count($subs) > 0) {
        $ar = $subscribers->makeSubsArray($subs);
        $num = count($ar);
        $mailer_file = MODX_BASE_PATH . 'manager/includes/controls/phpmailer/class.phpmailer.php';
        if ($num == 0){
            $subscribers->deleteTmpFile();
            $answer = $lang['send_complete'];
        }
        else {
            $answer = $lang['send_prosess'];
        }
        $response = array(
            'num' => $num,
            'answer' => $answer
        );
        $response = json_encode($response);
        echo $response;
        if (file_exists($mailer_file)) {
            require_once $mailer_file;
            // подключаемся к PHP Mailer
            $lt = $letters->getLetter($id);
            $body =  $letters->generateTplFromCode($lt['template'],$lt['newsletter']);
            $mail = new PHPMailer();
            if ($conf['email_method'] == 'mail') {
                $mail->IsMail();
            }
            if ($conf['email_method'] == 'smtp') {
                $mail->IsSMTP();
                $mail->Host = $conf['smtp_host'];
                if ($conf['smtp_auth'] == 1) {
                    $pass = explode('%',$conf['smtppw']);
                    $mail->SMTPAuth = true;
                    $mail->Username = $conf['smtp_username'];
                    $mail->Password = base64_decode($pass[0]);
                } else {
                    $mail->SMTPAuth = false;
                }
            }
            $mail->CharSet = $modx->config['modx_charset'];
            $From = $conf['emailsender'];
            $mail->Sender = $conf['emailsender'];
            $mail->setFrom($From, $modx->config['site_name'],true);
            $mail->addReplyTo($From, $modx->config['site_name']);
            $mail->Subject = $lt['subject'];
            $mail->Body = $body;
            $mail->AltBody = $body;
            //$mail->SMTPDebug  = 1;
            foreach ($ar as $val){
                //add realemail check
                if ($realemail->checkEmail($val['email']) == 1 or $realemail->checkEmail($val['email']) == 2){
                    $mail->AddAddress($val['email'],$val['firstname']);
                    if (!$mail->send()) {
                        echo 'Main mail: ' . $mail->ErrorInfo;
                    } else {
                        $subscribers->subscriberId = $val['id'];
                        $subscribers->lastLetterSend($num);
                        $subscribers->updateLastnewsletter($val['id']);
                        $mail->ClearAddresses();
                    }
                }
            }
        } else {
            exit('Отсутствует класс PHP Mailer. Обратитесь к администратору.');
        }
    }
    else {
        $subscribers->deleteTmpFile();
        echo 0;
    }
    //echo '<pre>';print_r($modx->config);echo '</pre>';
    exit;
}