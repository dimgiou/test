<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
protected function _initView()
{
// initialize view
$view = new Zend_View();
$view->doctype('XHTML1_STRICT');
$view->headTitle('Zend CMS frm bootstrat');
$view->skin = 'blues';

//add it to the ViewRenderer
$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
$viewRenderer->setView($view);

// return it so that it can be stored by the bootstrap
return $view;
}//_initView close

protected function _initAutoload()
{
// Add autoloader empty namespace
$autoLoader = Zend_Loader_Autoloader::getInstance();
$autoLoader->registerNamespace('CMS_');  // giati to ebale auto???
$resourceLoader= new Zend_Loader_Autoloader_Resource(array(
'basePath' => APPLICATION_PATH,
'namespace'=>'',
'resourceTypes'=> array(
	'form'=>array(
		'path'=>'forms/',
		'namespace'=>'Form_',
		),
	'model'=>array(
		'path'=>'models/',
		'namespace'=>'Model_'
		),
	),

));
// Return it so that it can be stored by the bootstrap
return $autoLoader;
}//_initAutoload close

protected function _initMenus()
{
$view=$this->getResource('view');
$view->mainMenuId=7;
$view->adminMenuId=8;
}//_initMenus close

// DOJO - episis exei mpei sto aplication.ini to " resources.view="" "
protected function _initDojo()
{
    //get view resource
    $this->bootstrap('view');
    $view = $this->getResource('view');
    
    //add DOJO helper path to VIEW
    Zend_Dojo::enableView($view);
    
    //configure Dojo view helper and DISABLE him
    $view->dojo()->setLocalPath('/js/dojo/dojo/dojo.js')
                 ->addStyleSheetModule('dijit.themes.claro')
                 
                 ->disable();
}// _initDojo close
}//class close

