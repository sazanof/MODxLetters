<?php

/**
 * Created by PhpStorm.
 * User: sazanof
 * Date: 13.01.2017
 * Time: 13:49
 */
class Subscribers
{
    protected $tbl = TBL_SUBSCRIBERS;
    protected $fields = 'id,firstname,lastname,email,cat_id';
    public $subscriberId = '';
    public $send_file;
    
    public function getSubscribers($where=false,$order=false,$limit=false){
        global $modx;
        $subs = $modx->db->select($this->fields, $this->tbl,$where,'',$limit);
        $res = $modx->db->makeArray($subs);
        return $res;
    }

    public function getSubscriber($fields=false,$id){
        global $modx;
        if ($fields==false){
            $fields=$this->fields;
        }
        $prepare = $modx->db->select($fields,TBL_SUBSCRIBERS,'WHERE id='.$id);
        $res = $modx->db->getRow($prepare);
        return $res;
    }

    public function InsOrUpdSubscriber($data,$id=false){
        //print_r($data);
        global $modx;
        $fields=$this->fields;
        $data['created'] = 'NOW()';
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
            $sql = "INSERT INTO $this->tbl ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE $dub";
            //echo $sql;
            if($modx->db->query($sql)){
                $res = true;
            }
            return $res;
        }
        else {
            return header('Location: '.$_SERVER['REQUEST_URI']);
        }
    }

    public function deleteSubscriber($id){
        global $modx;
        $res = $modx->db->delete($this->tbl,'id='.$id);
        return $res;
    }

    public function checkEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        else {
            return false;
        }
    }
    
    public function makeSubsArray($subs){
        $i = 0;
        $ar = array();
        if (count($subs)>0){
            foreach ($subs as $sub){
                if ($this->checkEmail($sub['email'])){
                    $ar[$i]['id'] = $sub['id'];
                    $ar[$i]['email'] = $sub['email'];
                    $ar[$i]['firstname'] = $sub['firstname'];
                    $ar[$i]['lastname'] = $sub['lastname'];
                }
                $i++;
            }
        }
        return $ar;
    }
    
    public function lastLetterSend($num=false){
        $file = $this->send_file;
        $s = $this->getSubscriber('id,email',$this->subscriberId);
        $s['total'] = $num;
        file_put_contents($file,json_encode($s));
        return;
        
    }
    public function getLastLetterSend(){
        $file = $this->send_file;
        if (file_exists($file)){
            $content = file_get_contents($file);
            $res = json_decode($content,true);
        }
        else {
            $res = false;
        }
        return $res;
        
    }
    
    public function update($tbl,$f,$where){
        global $modx;
        return $modx->db->update($f,$tbl,$where);
    }

    public function updateLastnewsletter($id){
        global $modx;
        $fields = array('lastnewsletter'  => time());
        $result = $modx->db->update( $fields, TBL_SUBSCRIBERS, 'id = '.$id );
        return $result;
    }

    public function deleteTmpFile(){
        if (file_exists($this->send_file)){
            unlink($this->send_file);
        }
    }

    public function onlyChars($str){
        if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$str)) {
            return false;
        }
        else {
            return $str;
        }
    }
}