<?php
namespace App\Vico\Validator;



class ProductValidator extends Validator
{
    public function __construct()
    {
        $this->rule('required', ['brand_id', 'category_id', 'model', 'price', 'description']);
        $this->rule('image', 'uploaded_pic');
    }
    public function validate():void 
    {
        parent::validate();

        if(count($this->data['uploaded_pic']['name']) > 7)
        {
            if(!empty($this->errors['uploaded_pic']))
            {
                unset($this->errors['uploaded_pic']);
            }
            $this->errors['uploaded_pic'][] = 'Vous ne pouvez charger que 7 images au maximum';
            return;
        }
        
        if(empty($this->errors['uploaded_pic']))
        {
            foreach($this->data['uploaded_pic']['size'] as $pos => $size)
            {
                if($size > 900000)
                {
                    $this->errors['uploaded_pic'][] = 'L\'image "'.$this->data['uploaded_pic']['name'][$pos].'" est trop volumineuse : '. number_format($size / 1000, 0, '', '') .' ko (maximum 900 ko)';
                }
            }
        }

        if(isset($this->data['toDelete_pics']) AND in_array($this->data['first_pic_choice'], $this->data['toDelete_pics']))
        {
            $this->errors['toDelete_pics'][] = 'Vous ne pouvez pas supprimer l\'image à la une';
        }
        
    }
}