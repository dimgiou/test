<?php
class Form_SearchForm extends Zend_Form{
    public function init(){
        $query= $this->createElement('text', 'query');
        $query->setLabel('Keywords');
        $query->setRequired(TRUE);
        $query->setAttrib('size', 20);
        $this->addElement($query);
        
        $submit=$this->createElement('submit', 'submit');
        $submit->setLabel('Search Site');
        $submit->setDecorators(array('ViewHelper'));
        $this->addElement($submit);
    }//init close
}
