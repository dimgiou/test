<?php

class FeedController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function rssAction()
    {
       //build the feed array
       $feedArray = array();
       
       //the title and link are required
       $feedArray['title']='Recent pages';
       $feedArray['link']='http://www.zf.local';
       
       //the published timestamp is optional
       $feedArray['published']= Zend_Date::now()->toString(Zend_Date::TIMESTAMP);
       
       //the charset is required
       $feedArray['charset']='UTF-8';
       
       $feedArray['image']='http://lorempixel.com/output/city-h-c-27-41-5.jpg';
       
       //now add recent pages to the feedArray['entries'][]
            //first get most recent pages
            $mdlPage= new Model_Page();
            $recentPages= $mdlPage->getRecentPages();
            
            // gia test
            $this->view->recentPages=$recentPages;
            
            //add the entries
            if(is_array($recentPages) && count($recentPages)>0){
                foreach ($recentPages as $page){
                   //create the entry
                   $entry = array();
                   $entry['guid']=$page->id;
                   $entry['title']=$page->headline;
                   $entry['link']='http://www.zf.local/page/open/id/'.$page->id;
                   $entry['description']=$page->description;
                   $entry['content']=$page->content;
                   //diko mou test
                   
                   
                   //add it to the feed
                   $feedArray['entries'][]=$entry;
                }//foreach close
            }//if close
            
            //create an rss feed from the $feedArray
            $feed= Zend_Feed::importArray($feedArray, 'rss');
            //disable viewRenderer and layout
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout->disableLayout();
            //now send the feed
            $feed->send();
    }//rss close


}//class close



