<?php 
require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH.'/models/Page.php';  //declaire the relationship

class Model_ContentNode extends Zend_Db_Table_Abstract{
	
	// the default table name
	protected $_name='content_nodes';
	
	//declaire the relationship "page to contentNode"= "1 -> many"
	protected $_referenceMap= array(
	'Page'=> array(
			'columns' 		=>	array('page_id'),
			'refTableClass'	=>	'Model_Page',
			'refColumns'	=>	array('id'),
			'onDelete'		=>	self::CASCADE,
			'onUpdate'		=>	self::RESTRICT)	
	);
	
	// insert and update 
	public function setNode($pageId, $node, $value){
		//fetch the row if it exists
		$select=$this->select();
		$select->where("page_id = ?", $pageId);
		$select->where("node = ?", $node);
		$row=$this->fetchRow($select);
		
		//if row does not exist then create it
		if(!$row){
			$row= $this->createRow();
			$row->page_id=$pageId;
			$row->node=$node;
		
		}
		
		//set the content
		$row->content=$value;
		$row->save();
	
	}//setNode close

}//class close

?>