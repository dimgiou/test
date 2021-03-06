<?php

class PageController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $pageModel= new Model_Page();
        $recentPages= $pageModel->getRecentPages();
        if(is_array($recentPages)){
            for ($i=1; $i <=4 ; $i++) { 
                if(count($recentPages)>0){
                    $featuredItems[]= array_shift($recentPages); //!!!!!!
                }
            }
            $this->view->featuredItems = $featuredItems;
            
            if($recentPages>0){
                $this->view->recentPages = $recentPages;
            }else{
                $this->view->recentPages = null;
            }
        }
    }// index action close

    public function createAction()
    {
        $pageForm = new Form_PageForm();
        
        if($this->getRequest()->isPost()){
            if ($pageForm->isValid($_POST)){
                $itemPage= new CMS_Content_Item_Page();
                $itemPage->name= $pageForm->getValue('name');
                $itemPage->headline=$pageForm->getValue('headline');
                $itemPage->description=$pageForm->getValue('description');
                $itemPage->content=$pageForm->getValue('content');
                // upload the image
                if($pageForm->image->isUploaded()){
                    $pageForm->image->receive();
                    $itemPage->image= '/images/upload/'.basename($pageForm->image->getFileName());
                }
                
                //save the content item
                $itemPage->save();
                return $this->_redirect('/page/list');
            }
        }
        
        $pageForm->setAction('/page/create');
        $this->view->pageForm = $pageForm;
    }//create action close
    

    public function listAction()
    {
        $pageModel = new Model_Page();
        //fetch all of the current pages
        $select= $pageModel->select();
        $select->order('name');
        $currentPages=$pageModel->fetchAll($select);
        if ($currentPages->count()>0){
            $this->view->pages = $currentPages;
        }else {
            $this->view->pages=null;
        }
        
    }//list action close
    

    public function editAction()
    {
        $id= $this->_request->getParam('id');
        $itemPage= new CMS_Content_Item_Page($id);
        $pageForm = new Form_PageForm();
        $pageForm->setAction('/page/edit');
        
        //otan erxetai apo to POST tis edit form
        if($this->getRequest()->isPost()){
            if($pageForm->isValid($_POST)){
                $itemPage->name=$pageForm->getValue('name');
                $itemPage->description=$pageForm->getValue('description');
                $itemPage->content=$pageForm->getValue('content');
                if($pageForm->image->isUploaded()){
                    $pageForm->image->receive();
                    $itemPage->image = '/images/upload/'.basename($pageForm->image->getFileName());
                }
                // save the content item
                $itemPage->save();
                return $this->_redirect('/page/list');
            }
        }
        //*****close an erxetai apo POST
        
        
        $pageForm->populate($itemPage->toArray());
        
        //create the image preview
        $imagePreview =$pageForm->createElement('image', 'image_preview');
        //element options
        $imagePreview->setLabel('Preview Image:');
        $imagePreview->setAttrib('style', 'width:200px;height:auto;');
        //add element to the form
        $imagePreview->setOrder(4);
        $imagePreview->setImage($itemPage->image);
        $pageForm->addElement($imagePreview);
        
        $this->view->form = $pageForm;
    }//edit action close
    

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        $itemPage= new CMS_Content_Item_Page($id);
        $itemPage->delete();
        return $this->_redirect('/page/list');
        
    }

    public function openAction(){
        $id = $this->_request->getParam('id');
        $pageModel= new Model_Page();
        
        if($pageModel->find($id)->current()){
            $itemPage= new CMS_Content_Item_Page($id);
            $this->view->itemPage=$itemPage;
        }else{
            throw new Zend_Controller_Action_Exception("page was not found",404);
        }
    }
    
}//class close









