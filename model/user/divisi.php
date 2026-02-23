<?php
class ModelUserDivisi extends Model {
  public function getDivisis($where=array()){
    return $this->db->all('divisi',$where,array(),0,null);
  }
}
?>
