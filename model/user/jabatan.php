<?php
class ModelUserJabatan extends Model {
  public function getJabatans($where=array()){
    return $this->db->all('jabatan',$where);
  }
}
?>
