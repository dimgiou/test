<?php 
class Form_PageForm extends Zend_Form{
    public function init(){
        $this->setAttrib('enctype', 'multipart/form-data');
        
        // create new element id
        $id = $this->createElement('hidden', 'id');
        //element options
        $id->setDecorators(array('ViewHelper'));
        //add the element to the form
        $this->addElement($id);
        
        //name
        $name = $this->createElement('text', 'name');
        $name->setLabel('Page Name:');
        $name->setRequired(TRUE);
        $name->setAttrib('size', 40);
        $this->addElement($name);
        
        // headline
        $headline = $this->createElement('text', 'headline');
        $headline->setLabel('headline:');
        $headline->setRequired(TRUE);
        $headline->setAttrib('size', 50);
        $this->addElement($headline);
        
        // file (image storage)
        $image= $this->createElement('file', 'image');
        $image->setLabel('Image:');
        $image->setRequired(FALSE);
        //dont forget to make the directory
        $image->setDestination(APPLICATION_PATH.'/../public/images/upload');
        //ensure only 1 file
        $image->addValidator('Count', FALSE, 1);
        //limit to 100k
        $image->addValidator('Size', FALSE, 502400);
        //only JPEG,PNG, and GIFs
        $image->addValidator('Extension', FALSE, 'jpg,png,gif');
        $this->addElement($image);
        
        //description
        $description = $this->createElement('textarea', 'description');
        $description->setLabel('description:');
        $description->setRequired(TRUE);
        $description->setAttrib('cols', 40);
        $description->setAttrib('rows', 4);
        $this->addElement($description);
        
        //content
        $content = $this->createElement('textarea', 'content');
        $content->setLabel('content:');
        $content->setRequired(TRUE);
        $content->setAttrib('cols', 50);
        $content->setAttrib('rows', 12);
        $this->addElement($content);
        
        //submit
        $submit=$this->addElement('image', 'submit',array('label'=>'', 'src'=>'/images/start.gif', 'width'=>'80px'));
        
    }// init close
}

?>