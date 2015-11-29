<?php

class MenuitemController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $menuId = $this->getRequest()->getParam('menu');
        $mdlMenu= new Model_Menus();
        $mdlMenuItems = new Model_MenuItems();
                
        $this->view->menu=$mdlMenu->find($menuId)->current();
        $this->view->menuItems=$mdlMenuItems->getItemsByMenu($menuId);
    }//index close
    

    public function addAction()
    {
        $menuId= $this->getRequest()->getParam('menu');
        $mdlMenu = new Model_Menus();
        $this->view->menu= $mdlMenu->find($menuId)->current();
        
        $frmMenuItems= new Form_MenuItems();
        
        if($this->getRequest()->isPost()){
            if ($frmMenuItems->isValid($_POST)){
                $data=$frmMenuItems->getValues();
                $mdlMenuItem = new Model_MenuItems();
                $mdlMenuItem->addItem($data['menu_id'], $data['label'],$data['page_id'],$data['link']);
/*kalo*/        $this->getRequest()->setParam('menu', $data['menu_id']);
                $this->_redirect('/menu/index');
            }
        }
        
        $frmMenuItems->populate(array('menu_id'=>$menuId));
        $this->view->form=$frmMenuItems;
    }
    
    public function moveAction(){
        $id = $this->getRequest()->getParam('id');
        $direction = $this->getRequest()->getParam('direction');
        $mdlMenuItem= new Model_MenuItems();
        $menuItem = $mdlMenuItem->find($id)->current();
        if($direction === 'up'){
            $mdlMenuItem->moveUp($id);
        }elseif ($direction === 'down'){
            $mdlMenuItem->moveDown($id);
        }
        $this->getRequest()->setParam('menu', $menuItem->menu_id);
        $this->_forward('index');
        
    }// moveAction close

}// class close



