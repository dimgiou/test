<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH.'/models/MenuItems.php'; //declaire the relationship

class Model_Menus extends Zend_Db_Table_Abstract
{
// default table name
protected $_name="menus";

//declaire the relationship with content_nodes table
protected $_dependentTables = array('Model_MenuItems');

public function createMenu($name){
    $row= $this->createRow();
    $row->name = $name;
    $row->save();
    $id = $row->id;
        
    return $id;
    
}//creatMenu close

public function getMenus(){
    $select= $this->select();
    $select->order('name');
    
    $menus= $this->fetchAll($select);
    if($menus->count()>0){
        return $menus;
    }else{
        return null;
    }
}//getMenus close

public function updateMenu($id, $name){
    $currentMenu = $this->find($id)->current();
    echo "<pre>";
    var_dump($currentMenu);
    echo "</pre>";
    if ($currentMenu){
        $currentMenu->name = $name;
        return $currentMenu->save();
    }else{
        return null;
    }   
    
}

public function deleteMenu($id){
    $row= $this->find($id)->current();
    if($row){
        return $row->delete();
    }else{
        throw new Zend_Exception ('error loading menu');
    }
}//delete close

}//class close

