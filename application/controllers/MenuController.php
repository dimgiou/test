<?php

class MenuController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $mdlMenu= new Model_Menus();
        $menus=$mdlMenu->getMenus();
        
        $this->view->menus= $menus; 
       
    }// indexAction close
    

    public function createAction()
    {
        $frmMenu= new Form_Menu();
                
        if ($this->getRequest()->isPost()){
            if($frmMenu->isValid($_POST)){
                echo "mpike";
                $menuName=$frmMenu->getValue('name');
                $mdlMenu= new Model_Menus();
                $result= $mdlMenu->createMenu($menuName);
                if ($result){
                    $this->_redirect('/menu/index');
                }
                
            }
        }
        $frmMenu->setAction('/menu/create');
        $this->view->form=$frmMenu;
    }//createAction close
    

    public function editAction()
    {
       $id =$this->_request->getParam('id');
       $mdlMenu= new Model_Menus();
       $frmMenu=new Form_Menu();
       
       if ($this->getRequest()->isPost()){
           if($frmMenu->isValid($_POST)){
           $result=$mdlMenu->updateMenu($_POST['id'], $_POST['name']);
                if ($result){
                   $this->_redirect("/menu/index"); // return den xreiazetai
                   echo "ok";
                }
           }           
       }else{
       $currentMenu = $mdlMenu->find($id)->current();
       
       //echo "<pre>";
       //var_dump($currentMenu);
       //echo "</pre>";
       $frmMenu->getElement('id')->setValue($currentMenu->id);
       $frmMenu->getElement('name')->setValue($currentMenu->name);                 
       }     

       $frmMenu->setAction('/menu/edit'); 
       $this->view->form= $frmMenu;
    }//edit close
    

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $mdlMenu= new Model_Menus();
        $mdlMenu->deleteMenu($id);
        $this->_redirect("/menu/index");
    }

    public function renderAction()
    {
        $menuId = $this->getRequest()->getParam('menu');
        $mdlMenuItems = new Model_MenuItems();
        $menuItems = $mdlMenuItems->getItemsByMenu($menuId);
        if(count($menuItems)>0){
            
            foreach ($menuItems as $item) {
               $label = $item->label;
               $uri= (!empty($item->link)) ? $item->link : '/page/open/id/'.$item->page_id ;
                //ftiaxnei to array pou 8a mpei sto Zend_Navigation
                $itemArray[]= array(
                    'label'=>$label,
                    'uri'=>$uri
                );
            }
            $container = new Zend_Navigation($itemArray);
            $this->view->navigation()->setContainer($container);
        }
    }// render action close


}//class close







