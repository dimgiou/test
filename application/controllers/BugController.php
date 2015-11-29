<?php

class BugController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
	
    }

    public function indexAction()
    {
        // action body
    }

    public function submitAction()
    {
		$bugReportForm = new Form_BugReportForm(); 
		$bugReportForm->setAction('/bug/submit');
		$bugReportForm->setMethod('post');
		
		
		if($this->getRequest()->isPost()){
		//echo "posted back";
			if($bugReportForm->isValid($_POST)){
				//echo "valid data";
				//$data= $bugReportForm->getValues();
				//var_dump($data);
				
				//if the form is valid then create the new bug
				$bugModel= new Model_Bug();
				
				$result=$bugModel->createBug(
				$bugReportForm->getValue('author'), 
				$bugReportForm->getValue('email'),
				$bugReportForm->getValue('date'),
				$bugReportForm->getValue('url'),
				$bugReportForm->getValue('description'),
				$bugReportForm->getValue('priority'),
				$bugReportForm->getValue('status'));
				echo "result= ".$result;
				//if the createBug method returns a result
				// then the bug was successfully created
				if($result) {
					echo "mpike sto confirm redirect";
					$this->_redirect('/bug/list');				
				}
			}
		}
	$this->view->form=$bugReportForm;	// check an meinei edo doulevei??
    }

    public function confirmAction()
    {
        // action body
    }

    public function listAction()
    {
	// get the filter form
	$listToolsForm = new Form_BugReportListToolsForm();
	$listToolsForm->setAction('/bug/list');
	$listToolsForm->setMethod('post');
	$this->view->listToolsForm=$listToolsForm;
	
	// set the default filter values to null *** old
	/*
	$sort=null;
	$filter=null;
	*/
	
	//set the sort and filter criteria. you need to update this to use the request,
	// as these values can come in from the form post or a url parameter
	$sort=$this->_request->getParam('sort',null);
	$filterField=$this->_request->getParam('filter_field', null);
	$filterValue=$this->_request->getParam('filter',null);
	
	
	
	if(!empty($filterField)){
		
		$filter[$filterField]=$filterValue;
	}else{
		$filter=null;
		}
	// now you need to manually set these controls values
	$listToolsForm->getElement('sort')->setValue($sort);
	$listToolsForm->getElement('filter_field')->setValue($filterField);
	$listToolsForm->getElement('filter')->setValue($filterValue);
	
	
	
	
	//var_dump($sort);
	var_dump($filter);
	
	//if this request is a postback request and is valid
	//then add the sort and filter criteria
	/*
	if($this->getRequest()->isPost()){
		if($listToolsForm->isValid($_POST)){
			$sortValue=$listToolsForm->getValue('sort');
			echo "sortValue=".$sortValue;
			if ($sortValue !='0'){
				$sort=$sortValue;
				echo "<br/>sort=".$sort;
			}
			$filterFieldValue= $listToolsForm->getValue('filter_field');
			if ($filterFieldValue !='0'){
				$filter[$filterFieldValue]=$listToolsForm->getValue('filter');
				
			}
		}
	}
	*/
	
	$bugModel = new Model_Bug();
	// fetch all bugs
	/*
	$this->view->bugs=$bugModel->fetchBugs($filter, $sort);
	*/
	
	//fetch the bug paginator adapter
	$adapter=$bugModel->fetchPaginatorAdapter($filter, $sort); //auta tora piran times edo ston controller
	$paginator=new Zend_Paginator($adapter);
	//show 10 bugs per page
	$paginator->setItemCountPerPage(10);
	//get the page number that is passed in the request.
	//if none is set then default to page 1
	$page=$this->_request->getParam('page',1);
	$paginator->setCurrentPageNumber($page);
	//pass the paginator to the view to render
	$this->view->paginator=$paginator;


	} // listAction close
    

    public function editAction()
    {
       $bugModel = new Model_Bug();
	   $bugReportForm = new Form_BugReportForm();
	   $bugReportForm->setAction('/bug/edit');
	   $bugReportForm->setMethod('post');
	   
	   
	   
	   if($this->getRequest()->isPost()){
			if ($bugReportForm->isValid($_POST)){
			// edo mpainei otan patas to submit stin forma tou edit
				//var_dump ($_POST);
				
				$result= $bugModel->updateBug(
					$bugReportForm->getValue('id'),
					$bugReportForm->getValue('author'),
					$bugReportForm->getValue('email'),
					$bugReportForm->getValue('date'),
					$bugReportForm->getValue('url'),
					$bugReportForm->getValue('description'),
					$bugReportForm->getValue('priority'),
					$bugReportForm->getValue('status')		
				);
				return $this->_redirect('/bug/list');
			}
	   }else {
	   // edo mpainei otan patas to edit kai to briskei sto URL
			$id = $this->_request->getParam('id');
			$bug = $bugModel->find($id)->current();
			// magic!!
			$bugReportForm->populate($bug->toArray());
			$bugReportForm->getElement('date')->setValue(date('m-d-Y',$bug->date));
	   }
	   
	   $this->view->form=$bugReportForm;
    }// edit action close

	public function deleteAction(){
	
	$bugModel = new Model_Bug();
	$id = $this->_request->getParam('id');
	$bugModel->deleteBug($id);
	 $this->_redirect('/bug/list');
	
	}
}//class close







