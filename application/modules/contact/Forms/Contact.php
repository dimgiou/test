<?php
class Contact_Form_Contact extends Zend_Dojo_Form{
    public function init(){
        $name = new Zend_Dojo_Form_Element_TextBox ('name');
        $name->setLabel("Enter your name");
        $name->setRequired(TRUE);
        $name->setAttrib('size', 40);
        $this->addElement($name);
        
        $email = new Zend_Dojo_Form_Element_TextBox('email');
        $email->setLabel('Enter your email');
        $email->setRequired(TRUE);
        $email->setAttrib('size', 40);
        $email->addValidator('EmailAddress');
        $email->addErrorMessage('Invalid email address');
        $this->addElement($email);
        
        $subject = new Zend_Dojo_Form_Element_TextBox('subject');
        $subject->setLabel("Subject:");
        $subject->setRequired(TRUE);
        $subject->setAttrib('size',60);
        $this->addElement($subject);
        
        $message= new Zend_Dojo_Form_Element_Textarea('message');
        $message->setLabel('Enter your message');
        $message->setRequired(TRUE);
        $message->setAttrib('style' , 'width: 400px; height:150px; min-height:100px;');
        $this->addElement($message);
        
        //create autocomplete input for ... country
        $country= new Zend_Dojo_Form_Element_ComboBox('country');
        $country->setLabel('Choose from list');
        $country->setRequired(TRUE);
        $country->addValidator('NotEmpty', TRUE);
        //$country->addFilter('HTMLEntities');
        //$country->addFilter('StringToLower');
        $country->addFilter('StringTrim');
        //$country->setMultiOptions( array('red'=>'κοκινο' , 'black'=>'μαυρο'));
        $country->setAutocomplete(FALSE);
                // data store
        $country->setStoreId('countryStore');
        $country->setStoreType('dojo.data.ItemFileReadStore');
        $country->setStoreParams(array('url' => "/contact/index/autocomplete"));
        $country->setDijitParams(array('searchAttr'=>'name'));
        
        $this->addElement($country);
        
        
        $dateTest = new Zend_Dojo_Form_Element_DateTextBox('dateTest');
        $dateTest->setLabel('Date:');
        $dateTest->setOptions(array(
                    'width' => '150px',
                    'height' => '100px'));
        $this->addElement($dateTest);
        
        // create captcha
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
        'captcha' => array(
        'captcha' => 'Image',
        'wordLen' => 6,
        'timeout' => 300,
        'width' => 300,
        'height' => 100,
        'DotNoiseLevel'=>'10',
        'LineNoiseLevel'=>'10',
        'imgUrl' => '/captcha',
        'imgDir' => APPLICATION_PATH . '/../public/captcha',
        'font' => APPLICATION_PATH .
        '/../public/fonts/LiberationSansRegular.ttf',
        )
        ));
        $captcha->setLabel('Verification code:');
        $this->addElement($captcha);
        
        $submit= new Zend_Dojo_Form_Element_SubmitButton('Submit');
        $submit->setLabel("Submit");
        $this->addElement($submit);
    }//init close
}
?>