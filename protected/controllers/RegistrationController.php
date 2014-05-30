<?php

/*
 * @author : owliber
 * @date : 2014-02-01
 */

class RegistrationController extends Controller
{
    public $layout = 'column2';
    
    public $dialogTitle;
    public $dialogMessage;
    public $showDialog = false;
    public $showConfirm = false;
    public $alertType = 'info';
    public $errorCode;
    
    
    /* ------------------------------------------ IPD REGISTRATION ------------------------------------------ */
    public function actionNew()
    {
        $model = new RegistrationForm();
        $model->member_id = Yii::app()->user->getId();
        if (isset($_POST['RegistrationForm']) && $_POST['hidden_flag'] != 1)
        {
            $model->attributes = $_POST['RegistrationForm'];
            
            // force required fields
            $model->product_name = 'Default: P5 Water Purifier';
            
            if ($model->validate())
            {
                $activation_code = $model->activation_code;
                
                $activation = new ActivationCodeModel();
                $result = $activation->validateActivationCode($activation_code, 2);
                if(count($result) > 0)
                {
                    $retname = $model->validateMemberName();
                    if (is_array($retname)) 
                    {
                        $this->dialogMessage = '<strong>Ooops!</strong> Member name already exist. Please use another name or append some characters you preferred to make it unique.';
                        $this->errorCode = 6;
                        $this->showDialog = true;
                    }
                    else 
                    {
                        $exist_member_code = $activation->checkUsedCodeByMembers($activation_code);
                        if ($exist_member_code > 0)
                        {
                            $this->dialogMessage = '<strong>Ooops!</strong> The activation code you have entered has already been used by another member. Please use another activation code.';
                            $this->errorCode = 6;
                            $this->showDialog = true;
                        }
                        else
                        {
                            $this->showConfirm = true;
                        }
                    }
                }
                else
                {
                    $this->dialogMessage = '<strong>Ooops!</strong> The activation code entered is invalid. Please make sure you have entered the code correctly or the code given to you is valid.';
                    $this->errorCode = 6;
                    $this->showDialog = true;
                }
                $this->dialogTitle = 'IBP Registration';
            }
        }
        else if ($_POST['hidden_flag'] == 1)
        {
            $model->attributes = $_POST['RegistrationForm'];
            $retval = $model->registerIPD();                    
            if($retval['result_code'] == 0)
            {
                $param['distributor_id'] = $model->new_member_id;
                $param['plain_password'] = $model->plain_password;
                Mailer::sendIPDVerificationLink($param);
                $param2['new_member_id'] = $model->new_member_id;
                $param2['endorser_id'] = $model->member_id;
                Mailer::sendIPDEndorserNotification($param2);
                $this->dialogMessage = '<strong>Well done!</strong> You have successfully registered our new business distributor.';
            }
            else
            {
                $this->dialogMessage = '<strong>Ooops!</strong> A problem encountered during the registration. Please contact P5 support.';
            }
            $this->errorCode = $retval['result_code'];
            $this->showDialog = true;
        }
        $this->render('_ipdindex',array('model'=>$model));
    }
    
    
    public function actionIpdConfirm()
    {
        $info = array();
        if (isset($_POST)) {
            $info[0]["member_name"] = strtoupper($_POST["last_name"] . ", " . $_POST["first_name"] . " " . $_POST["middle_name"]);
            $info[0]["endorser_name"] = Networks::getMemberName(Yii::app()->user->getId());
        }
        $dataProvider = new CArrayDataProvider($info, array(
                        'keyField' => false,
                        'pagination' => false
        ));
        $this->renderPartial('_ipdposition', array('dataProvider'=>$dataProvider));
    }
    
    public function actionPlaceUnderIPD()
    {
        if(Yii::app()->request->isAjaxRequest && isset($_GET['term']))
        {
            $model = new RegistrationForm();

            $result = $model->selectIPDDownlines($_GET['term']);

            if(count($result)>0)
            {
                foreach($result as $row)
                {
                    $arr[] = array(
                        'id'=>$row['member_id'],
                        'value'=>$row['member_name'],
                        'label'=>$row['member_name'],
                    );
                }

                echo CJSON::encode($arr);
                Yii::app()->end();
            }
            
        }
    }
}
?>
