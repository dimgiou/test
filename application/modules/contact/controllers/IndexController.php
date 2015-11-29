<?php
class Contact_IndexController extends Zend_Controller_Action{
    
    public function indexAction() 
    {
        
        $form = new Contact_Form_Contact();
        
        
        if ($this->getRequest()->isPost() && $form->isValid($_POST)){
            
           // echo "mpike";
           $sender=$form->getValue('name');
           $email=$form->getValue('email');
           $subject=$form->getValue('subject');
           $message=$form->getValue('message');
           $date=$form->getValue('dateTest');
           
           $fullMessage= $message." on ".$date;
           //load the template !!!!!!!!!!!!!!!!
           $values=$form->getValues();
           /*
           $htmlMessage= $this->view->partial(
           'templates/defaut.phtml',
           $values
           );
          */
           
           $this->view->test=$values;
           
           $mail = new Zend_Mail();
           
           $config= array(
           'ssl'=>'tls',
           'auth'=>'login',
           'username'=>'digkiousas@gmail.com',
           'password'=>'qwerty2012'
           );
           
           $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
           
           $mail->setSubject($subject);
           $mail->setFrom($email, $email);
           $mail->addTo('digkiousas@gmail.com', 'webdim');
           //$mail->setBodyHtml($htmlMessage);
           $mail->setBodyText($fullMessage);
           $result=$mail->send($transport);
           $this->view->mail = $mail;
           //inform the view with the status
           $this->view->messageProcessed=TRUE;
           if($result){
               $this->view->sendError=FALSE;
           }else{
               $this->view->sendError=TRUE;
           }  
            
                 
        }//if close
        $values=$form->getValues();
        echo "<pre>";
        print_r($values);
        echo "</pre>";
       
        
        $form->setMethod('post');
        $form->setAction('/contact/index');
        $this->view->form= $form;
    }//index action close
    
    public function autocompleteAction(){
        // disable layout and view rendering
        $this->_helper->layout->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender(true);
        
        // get country list from Zend_Locale
        $territories = Zend_Locale::getTranslationList('territory', null, 2);
        $items = array();
        foreach ($territories as $t) {
        $items[] = array('name' => $t);
        }
        
        // generate and return JSON string compliant with dojo.data structure
        $data = new Zend_Dojo_Data('name', $items);
        header('Content-Type: application/json' );
        echo $data->toJson();
        
    }
}//class close

?>