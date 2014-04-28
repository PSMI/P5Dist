<?php

/*
 * @author : owliber
 * @date : 2014-02-06
 */

class ActivationController extends Controller
{
    public $layout = "column1";
    
    public function actionVerify()
    {
        if(isset($_GET['email']) && isset($_GET['code']))
        {
            $email = $_GET['email'];
            $code = $_GET['code'];
            
            //validate email and activation code
            $model = new RegistrationForm();
            
            $isValid = $model->validateAccount($email, $code);
            
            if($isValid)
            {
                $isActivated = $model->activateAccount($email, $code);
                
                if($isActivated)
                    $this->redirect (array('activation/success'));
                else
                    $this->redirect (array('activation/error'));
            }
            
        }
        else
        {
            $this->redirect(array('site/login'));
        }
    }
    
    public function actionError()
    {
        $this->render('error');
    }
    
    public function actionSuccess()
    {
        $this->render('success');
    }
}
?>
