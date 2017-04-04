<?php
class Letters
{
    public function getLetters ($tbl,$where=false,$order=''){
        global $modx;
        $fields='id,date,status,sent,template,subject,newsletter,cat_id,log';
        if ($order!==''){
            $order_ext = 'ORDER BY '.$modx->db->escape($order);
        }
        $sql = $modx->db->select($fields,$tbl,$where,$order_ext);
        return $modx->db->makeArray($sql);
    }

    public function getLetter($id){
        global $modx;
        $fields='id,date,status,sent,template,subject,newsletter,cat_id,log';
        $prepare = $modx->db->select($fields,TBL_LETTERS,'WHERE id='.$id);
        $res = $modx->db->getRow($prepare);
        return $res;
    }

    public function deleteLetter($id,$where=''){
        global $modx;
        if ($where==''){
            $where = 'id='.$id;
        }
        $res = $modx->db->delete(TBL_LETTERS,$where);
        return $res;
    }

    public function getTemplate($id){
        global $modx;
        $sql = $modx->db->select('id,code',TBL_TEMPLATES,'WHERE id='.$id);
        return $modx->db->getRow($sql);
    }
    
    public function generateTplFromCode($tpl_id,$newsletter){
        if (intval($tpl_id)){
            $template = $this->getTemplate($tpl_id);
            if (count($template)<1){
                $loader = new Twig_Loader_Array(array(
                    'preview.html' => $this->toHtml($newsletter)
                ));
            }
            else {
                $loader = new Twig_Loader_Array(array(
                    'preview.html' => $this->toHtml($template['code'])
                ));
            }

        }
        else {
            $loader = new Twig_Loader_Array(array(
                'preview.html' => $this->toHtml($newsletter)
            ));
        }
        $twig = new Twig_Environment($loader);
        return $twig->render('preview.html', array('content' => $this->toHtml($newsletter)));
    }

    public function renderTinyMCE($el_name,$text=false){
        global $modx;
        include_once(MODX_MANAGER_PATH.'includes/tmplvars.inc.php');
        $event_output = $modx->invokeEvent("OnRichTextEditorInit", array('editor'=>$modx->config['which_editor'], 'elements'=>array('tv'.$el_name)));
        if(is_array($event_output)) {
            $tinyMce['editor_html'] = implode("",$event_output);
        }
        $tinyMce['rte_html'] = renderFormElement('textarea', $el_name, '', '', $text);
        return $tinyMce;
    }
    
    public function getTemplates(){
        global $modx;
        $sql = "SELECT id,title,description,code FROM ".TBL_TEMPLATES;
        $tpl = $modx->db->makeArray($modx->db->query($sql));
        return $tpl;
    }

    protected function prepeareFields($fields,$data){
        $res = explode(',',$fields);
        return $res;
    }

    public function InsOrUpdLetter($tbl,$data,$id){
        global $modx;
        $fields='id,date,status,sent,template,subject,newsletter,cat_id,log';
        $data['date'] = 'NOW()';
        if (intval($id)){
            $data['id'] = $id;
        }
        $data['cat_id'] = implode(',',$data['cat_id']);
        if (is_array($data) && count($data) > 0){
            $f_ar = explode(',',$fields);
            foreach ($data as $key => $val){
                if (in_array($key,$f_ar)){
                    if ($val != 'NOW()'){
                        $res[$key] = "'".$modx->db->escape($val)."'";
                    }
                    else {
                        $res[$key] = $modx->db->escape($val);
                   }
                }
            }
        }
        $keys = implode(',',array_keys($res));
        $values = implode(',',array_values($res));
        $duplicate = '';
        foreach ($res as $key => $value){
            if (!intval($value)){
                $duplicate[] = $key."=".$value;
            }
            else {
                $duplicate[] = $key."=".$value;
            }

        }
        $dub = implode(',',$duplicate);
        if (!in_array('', $data)){
            $sql = "INSERT INTO $tbl ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE $dub";
            //echo $sql;
            if($modx->db->query($sql)){
                header('Location: '.$_SERVER['REQUEST_URI']);
            }
            return true;
        }
        else {
            return header('Location: '.$_SERVER['REQUEST_URI']);;
        }
    }

    public function toHtml($str){
        $res = htmlspecialchars_decode($str);
        return $res;
    }

    public function fromHtml($str){
        $res = htmlspecialchars($str);
        return $res;
    }
}