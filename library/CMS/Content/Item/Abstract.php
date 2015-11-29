<?php   //sel 87 tou tutorial // this class wraps a standart page
abstract class CMS_Content_Item_Abstract{
    public $id;
    public $name;
    public $parent_id=0;
    
    protected $_namespace = 'page';         //?????????????????????
    protected $_pageModel;
    
    const NO_SETTER ='setter method does not exist';
    
    // constuctor
    public function __construct($pageId=null){
        $this->_pageModel=new Model_Page();       //?????????????????
        if(null != $pageId){
            $this->loadPageObject(intval($pageId));
        }
    }    
    
  //this method fetch the row that the content item relates to from the pages table   
    protected function _getInnerRow($id=null){
        if ($id==null){
            $id = $this->id;
        }
        return $this->_pageModel->find($id)->current();
    }
    
    //this method will return an array of the properties (NAME only?) of the content item
    protected function _getProperties(){
        $propertyArray = array();
        $class=new Zend_Reflection_Class($this);
        $properties=$class->getProperties();
        foreach ($properties as $property) {
            if($property->isPublic()){
                $propertyArray[]=$property->getName();
            }
        }
        return $propertyArray;
    }
    
    //this method will attempt to call a setter method for the value you pass it
    protected function _callSetterMethod ($property, $data){
        //create the method name
        $method = Zend_Filter::filterStatic($property, 'Word_UnderscoreToCamelCase');
        $methodName = '_set'.$method;
        if(method_exists($this, $methodName)){
            return $this->$methodName($data);
        }else{
            return self::NO_SETTER;
        }
    }
    
    public function loadPageObject($id){
        $this->id =$id;
        $row=$this->_getInnerRow();
        if($row){
            if($row->namespace != $this->_namespace){
                throw new Zend_Exception('Unable to cast page type:'.
                $row->namespace.'to type'. $this->_namespace);
            }
        $this->name = $row->name;
        $this->parent_id = $row->parent_id;
        $contentNode = new Model_ContentNode();
        $nodes = $row->findDependentRowset($contentNode);
        if ($nodes){
            $properties = $this->_getProperties();
            foreach ($nodes as $node) {
                $key=$node['node']; 
                if(in_array($key, $properties)){
                    //try to call the setter method
                    $value =$this->_callSetterMethod($key, $nodes); // $nodes or $node????????
                    if($value ===self::NO_SETTER){ //??????
                        $value=$node['content'];
                    }
                    $this->$key =$value;
                }
            }
        }else{
            throw new Zend_Exception("Unable to load content item");
            }
        }// if($row)
        
    }//loadPageObject close
    
    public function toArray(){
        // build an array of the value of the public properties (items properties)
        $properties =$this->_getProperties();
        foreach ($properties as $property) {
            $array[$property]= $this->$property;
        }
        return $array;
    }
    
    public function save(){
        if (isset($this->id)){
            $this->_update();
        }else{
            $this->_insert();
        }        
    }
    
    protected function _insert(){
        $pageId=$this->_pageModel->createPage(
            $this->name, $this->_namespace, $this->parent_id);
        $this->id = $pageId;
        $this->_update();        
    }
    
    protected function _update(){
        $data = $this->toArray();
        $this->_pageModel->updatePage($this->id, $data); 
        var_dump($data);       
    }
    
    public function delete(){
            if(isset($this->id)){
                $this->_pageModel->deletePage($this->id);
            }else {
                throw new Zend_Exception('Unable to delete item; the item is empty');
            }
        
    }
    
}// class close
?>