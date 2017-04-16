<?php
if (!defined('MODX_BASE_PATH')) {
    die('What are you doing? Get out of here!');
}
session_start();
include_once MODX_BASE_PATH . 'assets/modules/letters/inc/cfg.php';
$mailer_file = MODX_MANAGER_PATH.'includes/controls/phpmailer/class.phpmailer.php';
require_once ($mailer_file);
$mail = new PHPMailer();
/*
 * @lng
 * Язык
 * По умолчанию - russian-UTF8
 * Имена файлов в папке languages
 *
 * @tpl
 * Шаблон формф подписки.
 * По умолчанию так, как определено ниже.
 * Возможные значения - имя чанка в системе
 *
 * @tpl_unsubscribe
 * Шаблон отписки от рассылки
 * Значение: чанк из системы
 *
 * @cat_id
 * Список категорий через запятую, который будет присвоен подписчику
 * Пример 1 или 2,4 или 2,3,4,5
 *
 * @formname
 * Имя формы атрибута name
 * String
 *
 *
 * ПРИМЕР вызова
 * [!MODxLetters? &type=`subscribe` &cat_id=`2`!]
 * [!MODxLetters? &type=`unsubscribe`!]
 */
$lng = isset($lng) ? $lng : 'ru';
$formname = isset($formname) ? $formname : 'lForm';
$cat_id = isset($cat_id) ? $cat_id : '';
$type = isset($type) ? $type : 'subscribe';
$tpl = isset($tpl) ? $modx->getChunk($tpl) : '
<form action="[~[*id*]~]?type=subscribe" method="post" name="[+formname+]">
<input type="hidden" name="token" value="[+token+]">
<div class="form-group">
    <input type="text" name="firstname" class="form-control" placeholder="Ваше имя" value="[+firstname+]">
</div>
<div class="form-group">
    <input type="email" name="email" class="form-control" placeholder="Адрес email" value="[+email+]">
</div>
<button type="submit" name="sub" value="1" class="btn btn-success">Отправить</button>
</form>';

$tpl_unsubscribe = isset($tpl_unsubscribe) ? $tpl_unsubscribe : '
<form method="post" action="[~[*id*]~]?type=unsubscribe">
<input type="hidden" name="token" value="[+token+]">
<div class="input-group">
    <span class="input-group-addon">@</span>
    <input type="email" name="email" value="[+email+]" class="form-control">
    <span class="input-group-btn">
            <button class="btn btn-success" type="submit" name="sub" value="1">Отписываюсь!</button>
    </span>
    </div>
</form>
';
$confirm_tpl = isset($confirm_tpl) ? $confirm_tpl : 'Здравствуйте! Поступил запрос на исключение Вас из списка рассылки.
Для подтверждения перейдите по этой <a href="[+confirm_url+]" title="" target="_blank">ссылке</a>
<br><i>Вы можете также скопировать ссылку и вставить его в адресную строку браузера: [+confirm_url+]</i>';

$lng_file = ENL_PATH . 'languages/' . $lng . '.php';
$token = sha1(md5(date('dmYHis') . '_' . $type));
$out = '';
$data = array();
if (file_exists($lng_file)) {
    require_once($lng_file);
    require_once ENL_PATH . 'classes/Subscribers.php';
    $subscribers = new Subscribers();
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
    $mail->Sender = $conf['emailsender'];
    $From = $conf['emailsender'];
    $mail->setFrom($From, $modx->config['site_name'],true);
    $mail->addReplyTo($From, $modx->config['site_name']);
    $mail->Subject = $lang['subject_unset'];
    // подписаться
    switch ($type) {
        case 'subscribe':
            if ($_POST['sub'] == 1 && !empty($_POST['token']) && $_GET['type'] === 'subscribe') {
                $_SESSION['token'] = $subscribers->onlyChars($_POST['token']);
                if ($_SESSION['token'] === $_POST['token']) {
                    $data['firstname'] = $subscribers->onlyChars($_POST['firstname']);
                    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['email'] = $_POST['email'];
                        $s = $subscribers->getSubscribers("email='" . $data['email'] . "'");
                        if (count($s) == 1) {
                            $out .= $lang['subscriber_exist'];
                            unset($_SESSION['token']);
                        } else {
                            //добавляем подписчика
                            $data['cat_id'] = $cat_id;
                            if ($subscribers->InsOrUpdSubscriber($data, '') === true) {
                                header('Location: ' . $_SERVER['REQUEST_URI']);
                            }
                            unset($_SESSION['token']);
                        }
                    }
                }
            }
            break;
        case 'unsubscribe':

            if ($_POST['sub']==1 && !empty($_POST['token']) && $_GET['type'] === 'unsubscribe'){
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email'] = $_POST['email'];
                    $s = $subscribers->getSubscribers("email='" . $data['email'] . "'");
                    if (count($s) == 1) {
                        $action = '&unset='.sha1(md5($s[0]['email'])).'&email='.$s[0]['id'];
                        $link = $modx->config['site_url'].$modx->makeUrl($modx->documentIdentifier).$action;
                        $_SESSION['unset'] = sha1(md5($s[0]['email']));
                        $confirm_tpl = str_replace('[+confirm_url+]', $link , $confirm_tpl);
                        $mail->Body = $confirm_tpl;
                        $mail->AltBody = $confirm_tpl;
                        $mail->AddAddress($s[0]['email'],$s[0]['firstname']);
                        if (!$mail->send()) {
                            echo 'Main mail: ' . $mail->ErrorInfo;
                        } else {
                            header('Location:'.$_SERVER['REQUEST_URI']);
                            $mail->ClearAddresses();
                        }
                    }

                }

            }
            if (isset($_SESSION['unset']) && $_SESSION['unset']!=''){
                $tpl = '';
                $out = $lang['wait_unset_email'];
                //unset($_SESSION['unset']);
                //echo($_SESSION['unset']);
                if ($_GET['unset'] == $_SESSION['unset']){
                    if ((int)$_GET['email']){
                        if ($subscribers->deleteSubscriber($_GET['email'])){
                            $out = $lang['unsubscribe_success'];
                            unset ($_SESSION['unset']);
                        }
                    }
                    
                }
            }
            else {
                $tpl = $tpl_unsubscribe;
            }

            break;
        default:
            exit('Type parameter type is empty!');
            break;
    }

    // отписаться
    $f = array(
        '[+token+]',
        '[+formname+]',
        '[+firstname+]',
        '[+email+]'
    );
    $r = array(
        $token,
        $formname,
        $data['firstname'],
        $data['email']
    );
    $tpl = str_replace($f, $r, $tpl);
    $out .= $tpl;
} else {
    $out = "Не найден языковой файл! Проверьте конфинурацию модуля и файлы.";
}
echo $out;
