<?php
$str = '';
$file = ENL_PATH.'install.sql';
if (file_exists($file)){
    $install = file_get_contents($file);
    //echo '<pre>';print_r($modx->config);die;
    $install = str_replace('{per}',$modx->db->config['table_prefix'],$install);
    if ($_POST['install']==='1'){
        $sqlar = explode('#sep#',$install);
        //echo '<pre>';print_r($sqlar);die;
        foreach ($sqlar as $_sql){
            $modx->db->query($_sql);
        }
            $str = '<p style="color:red">Таблицы обновлены. Перезагрузите страницу модуля, если установка производится впервые.<br>';
            $str .= 'Для наполнения тестовыми данными выполните эти два запроса в панели управления сайтом:</p>
<pre>INSERT INTO `{per}letters_templates` (`id`, `date`, `title`, `description`, `code`) VALUES
(NULL, \'2017-01-17 11:07:00\', \'Пустой шаблон по умолчанию\', \'Описание\', \'{{ content|raw }}\');
</pre>
<pre>
INSERT INTO `{per}letters_categories` (`id`, `title`, `description`) VALUES
(NULL, \'Моя первая категория\', \'Первая категория по умолчанию для подписчиков\');</pre>';
    }
}
$str = str_replace('{per}',$modx->db->config['table_prefix'],$str);
$out .= '<div class="sectionBody"><div class ="panel panel-default"><div class="panel-body">';
$out .= '<p><form method="post"><button type="submit" class="btn btn-warning" name="install" value="1">Обновить таблицы</button></form></p>';
$out .= $str.'<p><b>install.sql</b></p><pre>'.$install.'</pre>';
$out .= '</div></div>';
