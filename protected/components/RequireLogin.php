<?php

/*
 * @author : owliber
 * @date : 2014-02-13
 */

class RequireLogin extends CBehavior
{
    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }
    
    public function handleBeginRequest($event)
    {
        $controller = Yii::app()->request->getPathInfo();
        $allowed = array(
            'site/login',
            'site/forgot',
            'activation/verify',
            'activation/success',
            'activation/error',
            'cron/goc',
            'cron/sendmail',
            'cron/directendorse',
            'cron/loandirect',
            'cron/loancompletion',
            'cron/unilevel',
            'cron/promocheck',
            'cron/run',
            'cron/updaterunningaccounts',
            'cron/rotatelog',
            'SMSAPI/activate',
            'SMSAPI/smsq',
            'SMSAPI/placement'
        );
        if (Yii::app()->user->isGuest && !in_array($controller, $allowed))
        {
            Yii::app()->user->loginRequired();
        }
    }
}

?>
