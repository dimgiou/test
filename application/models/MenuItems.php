<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH.'/models/Menus.php'; //declaire the relationship

class Model_MenuItems extends Zend_Db_Table_Abstract
{
// default table name
protected $_name="menu_items";

//declaire the relationship "MenuItems to Menus"= " many -> 1"
    protected $_referenceMap= array(
    'Menus'=> array(
            'columns'       =>  array('menu_id'),
            'refTableClass' =>  'Model_Menus',
            'refColumns'    =>  array('id'),
            'onDelete'      =>  self::CASCADE,
            'onUpdate'      =>  self::RESTRICT) 
    );

public function getItemsByMenu($menuId){
   $select = $this->select()->where('menu_id = ?', $menuId)->order('position');
   $items=$this->fetchAll($select);
   if ($items->count()>0){
       return $items;
   }else{
       return null;
   }
}//getItemsByMenu close

public function addItem($menuId, $label, $pageId=0, $link=null){
    $row=$this->createRow();
    $row->menu_id=$menuId;
    $row->label=$label;
    $row->page_id=$pageId;
    $row->link=$link;
    $row->position=$this->_getLastPosition($menuId) + 1;
   
    return $row->save();
}//addItem close

private function _getLastPosition($menuId){
    $select = $this->select()->where('menu_id = ?', $menuId)->order('position DESC'); 
    $row = $this->fetchRow($select); 
    if ($row){
        return $row->position;
       
    }else{
        return 0;
    }
}// _getLastPosition close

    public function moveUp($itemId){
        $row = $this->find($itemId)->current();
        if ($row){
            $position = $row->position;
            // it is already in 1st position
            if($position < 1){
                return false;
            }else{
            // find previous item
            $select = $this->select();
            $select->where('position < ?', $position);
            $select->where('menu_id = ?', $row->menu_id);
            $select->order("position DESC");
            $prevRow = $this->fetchRow($select);
                if($prevRow){
                    //switch positions with previous row
                    
                    $row->position= $prevRow->position;
                    $prevRow->position= $position;
                    $row->save();
                    $prevRow->save();
                    
                }            
            }      
        }else{
            throw new Zend_Exception('Error loading menuItem');
        }
        
    }// moveUp close

}//class close


