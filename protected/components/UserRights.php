<?php

/*
 * @author : owliber
 * @date : 2014-01-22
 */

class UserRights extends CWebUser
{
   
    public function hasUserAccess()
    {
        $model = new AccessRights();
        
        
        if(!$model->checkUserAccess($this->accountType()) || Yii::app()->user->isGuest)
            return false;
        else
            return true;
            
    }
    
    public function accountType()
    {
        return Yii::app()->session['account_type_id'];
    }
        
    public function getId() {
        return Yii::app()->session['distributor_id'];
    }
    
    public function getDistributorName()
    {
        $model = new Distributors();
        $distributor_name = $model->getDistributorName($this->getId());
        return $distributor_name;
    }
}
?>
