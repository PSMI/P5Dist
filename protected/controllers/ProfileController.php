<?php

/**
 * @author Noel Antonio
 * @date 03-28-2014
 */

class ProfileController extends Controller
{
    public $msg = '';
    public $title = '';
    public $showDialog = false;
    public $showConfirm = false;
    public $showRedirect = false;
    public $reOpenDialog = false;
    
    public $layout = "column2";
    
    public function actionIndex()
    {
        $model = new Distributors();
        $members = new Members();
        
        $login_id = Yii::app()->user->getId();
        
        $rawData = $model->getProfileInfo($login_id);
        
        $endorserInfo = $members->selectMemberName($rawData["ipd_endorser_id"]);
        
        if (isset($_POST["btnChange"]))
        {
            $db_pass = $rawData["password"];
            $curr_pass = $_POST["txtCurrentPass"];
            $new_pass = $_POST["txtNewPass"];
            $confirm_pass = $_POST["txtConfirmPass"];

            if ($curr_pass != "" && $new_pass != "" && $confirm_pass != "")
            {
                if ($new_pass == $confirm_pass)
                {
                    if ($db_pass == md5($curr_pass))
                    {
                        $retval = $model->changePassword($login_id, $new_pass);
                        
                        if ($retval)
                        {
                            $param['distributor_id'] = $login_id;
                            $param['plain_password'] = $new_pass;
                            Mailer::sendChangePassword($param);
                            
                            $this->title = "SUCCESSFUL";
                            $this->msg = "Distributor's password successfully modified.";
                            $this->showRedirect = true;
                        }
                        else
                        {
                            $this->title = "NOTIFICATION";
                            $this->msg = "Change password failed.";
                            $this->showDialog = true;
                        }
                    }
                    else
                    {
                        $this->title = "NOTIFICATION";
                        $this->msg = "Invalid current password. Please try again.";
                        $this->reOpenDialog = true;
                    }
                }
                else
                {
                    $this->title = "NOTIFICATION";
                    $this->msg = "Your new password and confim password did not match.";
                    $this->reOpenDialog = true;
                }
            }
            else
            {
                $this->title = "NOTIFICATION";
                $this->msg = "Please fill-up the required fields.";
                $this->reOpenDialog = true;
            }
        }
        
        $this->render('index', array('model'=>$model, 'data'=>$rawData, 'endorser'=>$endorserInfo));
    }
    
    public function actionInfo()
    {
        $model = new Distributors();
        
        $distributor_id = $_POST['id'];
        
        $rawData = $model->getContactInfo($distributor_id);
        
        return $this->renderPartial('_update', array('model'=>$model, 'data'=>$rawData));
    }
    
    public function actionUpdate()
    {
        $model = new Distributors();
        
        $model->attributes = $_POST['Distributors'];
        
        if ($model->validate()) {
            $retval = $model->updateContactInfo();
            if ($retval)
                $array = array('code'=>1, 'msg'=>'Contact information successfully updated!');
            else
                $array = array('code'=>0, 'msg'=>'Failed in updating contact information.');
            
            $this->showRedirect = true;
        }
        else {
            $array = array('code'=>0, 'msg'=>'Please check all your input fields.');
        }
        
        echo CJSON::encode($array);
    }
}
?>
