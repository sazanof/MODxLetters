<?php

/**
 * Created by PhpStorm.
 * User: sazanof
 * Date: 11.01.2017
 * Time: 14:29
 */
class Categories
{
    public function getCategories ($where=false,$order=''){
        global $modx;
        $fields='id,title,description';
        if ($order!==''){
            $order_ext = 'ORDER BY '.$modx->db->escape($order);
        }
        $sql = $modx->db->select($fields,TBL_CATEGORIES,$where,$order_ext);
        return $modx->db->makeArray($sql);
    }

    public function getCategory($id){
        global $modx;
        $fields='id,title,description';
        $prepare = $modx->db->select($fields,TBL_CATEGORIES,'WHERE id='.$id);
        $res = $modx->db->getRow($prepare);
        return $res;
    }

    public function deleteCategory($id){
        global $modx;
        $res = $modx->db->delete(TBL_CATEGORIES,'id='.$id);
        return $res;
    }

    public function catAfterDelete($cat_id){
        $cat = explode(',',$cat_id);
        $res = '';
        foreach ($cat as $id){
            $c = $this->getCategory($id);
            if (count($c==1)){
                $res[]=$id;
            }
            return implode(',',$res);
        }
    }

    public function InsOrUpdCategory($data,$id){
        global $modx;
        $fields='id,title,description';
        $data['id'] = $id;
        if (is_array($data) && count($data) > 0){
            $f_ar = explode(',',$fields);
            foreach ($data as $key => $val){
                if (in_array($key,$f_ar)){
                    if (!intval($val)){
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
        $sql = "INSERT INTO ".TBL_CATEGORIES." ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE $dub";
        if($modx->db->query($sql)){
            header('Location: '.$_SERVER['REQUEST_URI']);
        }
        return true;
    }
}