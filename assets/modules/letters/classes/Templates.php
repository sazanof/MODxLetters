<?php

/**
 * Created by PhpStorm.
 * User: sazanof
 * Date: 17.01.2017
 * Time: 9:31
 */
class Templates
{
    private $tbl = TBL_TEMPLATES;
    public $fields = 'id,date,title,description,code';

    public function getTemplates($where=false,$order=false,$limit=false){
        global $modx;
        $fields = $this->fields;
        $prepare = $modx->db->select($fields,$this->tbl,false,'id DESC',false);
        $res = $modx->db->makeArray($prepare);
        return $res;
    }

    public function getTemplate($id){
        global $modx;
        $fields = $this->fields;
        $prepare = $modx->db->select($fields,$this->tbl,'id='.$id);
        $res = $modx->db->getRow($prepare);
        return $res;
    }

    public function deleteTemplate($id){
        global $modx;
        $res = $modx->db->delete($this->tbl,'id='.$id);
        return $res;
    }

    public function InsOrUpdTemplate($data,$id){
        print_r($data);
        global $modx;
        $fields=$this->fields;
        $data['date'] = 'NOW()';
        if (intval($id)){
            $data['id'] = $id;
        }
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
        $duplicate = array();
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
            $sql = "INSERT INTO $this->tbl ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE $dub";
            echo $sql;
            if($modx->db->query($sql)){
                header('Location: '.$_SERVER['REQUEST_URI']);
            }
            return true;
        }
        else {
            return header('Location: '.$_SERVER['REQUEST_URI']);;
        }
    }
    
}
