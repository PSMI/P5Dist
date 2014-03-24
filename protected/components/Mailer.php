<?php

/*
 * @author : owliber
 * @date : 2014-02-06
 */

class Mailer
{
    CONST VERIFY_ACCOUNT_TMPL = 1;
    CONST UPLINE_NOTIFY_TMPL = 2;
    CONST CHANGE_PASSWORD_TMPL = 3;
    CONST DISAPPROVE_NOTIFY_TMPL = 4;
    const APPROVE_NOTIFY_TMPL = 5;
    const MEMBER_NOTIFY_TMPL = 6;
    CONST ACCOUNT_CREATION = 7;
    
    /**
     * Send verification link to new member
     * @param type $param
     */
    public function sendVerificationLink($param)
    {
        $model = new RegistrationForm();
        
        $member_id = $param['member_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::VERIFY_ACCOUNT_TMPL);
                
        $members = new MembersModel();
        $result = $members->selectMemberDetails($member_id);        

        $email = $result['email'];
        $member_name = $result['first_name'] . ' ' . $result['last_name'];
        $activation_code = $result['activation_code'];
        $username = $result['username'];
        $password = $param['plain_password'];
        
        $params = array('email'=>$email,
                        'code'=>$activation_code);
        
        $verification_link = 'https://'.$_SERVER['HTTP_HOST'].  Yii::app()->createUrl('activation/verify', $params);
        $link = '<a href="'.$verification_link.'">'.$verification_link.'</a>';
        
        $placeholders = array('MEMBER_NAME'=>$member_name, 
                              'VERIFICATION_LINK'=>$link,
                              'USERNAME'=>$username,
                              'PASSWORD'=>$password);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        if(count($result) > 0)
        {
            $sender = 'noreply@p5partners.com';
            $sender_name = 'P5 Marketing Incorporated';
            $recipient = $email;
            $subject = 'Important:P5 Membership Activation Required';
                        
            $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);
            
        }
        
        
    }
    
    /**
     * Send notification message to the upline
     * @param type $param
     */
    public function sendUplineNotification($param)
    {
        $model = new RegistrationForm();
                    
        $downline_id = $param['new_member_id'];
        $upline_id = $param['upline_id'];
        $endorser_id = $param['endorser_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::UPLINE_NOTIFY_TMPL);
                
        $members = new MembersModel();
        
        $downline_info = $members->selectMemberDetails($downline_id); 
        $upline_info = $members->selectMemberDetails($upline_id);
        $endorser_info = $members->selectMemberDetails($endorser_id);
                
        $upline_email = $upline_info['email'];
        $upline_name = $upline_info['first_name'] . ' ' . $upline_info['last_name'];
        $downline_name = $downline_info['first_name'] . ' ' . $downline_info['last_name'];
        $endorser_name = $endorser_info['first_name'] . ' ' . $endorser_info['last_name'];
        
        $placeholders = array('MEMBER_NAME'=>$upline_name, 
                              'ENDORSER_NAME'=>$endorser_name,
                              'DOWNLINE_NAME'=>$downline_name);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        $sender = 'noreply@p5partners.com';
        $sender_name = 'P5 Marketing Incorporated';
        $recipient = $upline_email;
        $subject = 'Important: New downline for approval';
 
        $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);

                
    }
    
    /**
     * Send change password notification
     * @param type $param
     */
    public function sendChangePassword($param)
    {
        $model = new RegistrationForm();
        
        $member_id = $param['member_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::CHANGE_PASSWORD_TMPL);
                
        $members = new MembersModel();
        $result = $members->selectMemberDetails($member_id);        

        $email = $result['email'];
        $member_name = $result['first_name'] . ' ' . $result['last_name'];
        $username = $result['username'];
        $password = $param['plain_password'];
        
        $placeholders = array('MEMBER_NAME'=>$member_name, 
                              'USERNAME'=>$username,
                              'PASSWORD'=>$password);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        if(count($result) > 0)
        {
            $sender = 'noreply@p5partners.com';
            $sender_name = 'P5 Marketing Incorporated';
            $recipient = $email;
            $subject = 'Change Password Notification';
            
            $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);

        }
                
    }
    
    /**
     * 
     * @param type $param
     */
    public function sendDisapproveNotification($param)
    {
        $model = new RegistrationForm();
                    
        $downline_id = $param['new_member_id'];
        $upline_id = $param['upline_id'];
        $endorser_id = $param['endorser_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::DISAPPROVE_NOTIFY_TMPL);
                
        $members = new MembersModel();
        
        $downline_info = $members->selectMemberDetails($downline_id); 
        $upline_info = $members->selectMemberDetails($upline_id);
        $endorser_info = $members->selectMemberDetails($endorser_id);
                
        $upline_name = $upline_info['first_name'] . ' ' . $upline_info['last_name'];
        $downline_name = $downline_info['first_name'] . ' ' . $downline_info['last_name'];
        $endorser_name = $endorser_info['first_name'] . ' ' . $endorser_info['last_name'];
        $endorser_email = $endorser_info['email'];
        $curdate = date('M d Y');
        
        $placeholders = array('UPLINE_NAME'=>$upline_name, 
                              'ENDORSER_NAME'=>$endorser_name,
                              'DOWNLINE_NAME'=>$downline_name,
                              'DISAPPROVED_DATE'=>$curdate);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        $sender = 'noreply@p5partners.com';
        $sender_name = 'P5 Marketing Incorporated';
        $recipient = $endorser_email;
        $subject = 'Notice: New downline disapproved.';
 
        $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);

                
    }
    
    public function sendApproveNotification($param)
    {
        $model = new RegistrationForm();
                    
        $downline_id = $param['new_member_id'];
        $upline_id = $param['upline_id'];
        $endorser_id = $param['endorser_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::DISAPPROVE_NOTIFY_TMPL);
                
        $members = new MembersModel();
        
        $downline_info = $members->selectMemberDetails($downline_id); 
        $upline_info = $members->selectMemberDetails($upline_id);
        $endorser_info = $members->selectMemberDetails($endorser_id);
                
        $upline_name = $upline_info['first_name'] . ' ' . $upline_info['last_name'];
        $downline_name = $downline_info['first_name'] . ' ' . $downline_info['last_name'];
        $endorser_name = $endorser_info['first_name'] . ' ' . $endorser_info['last_name'];
        $endorser_email = $endorser_info['email'];
        $curdate = date('M d Y');
        
        $placeholders = array('UPLINE_NAME'=>$upline_name, 
                              'ENDORSER_NAME'=>$endorser_name,
                              'DOWNLINE_NAME'=>$downline_name,
                              'APPROVED_DATE'=>$curdate);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        $sender = 'noreply@p5partners.com';
        $sender_name = 'P5 Marketing Incorporated';
        $recipient = $endorser_email;
        $subject = 'Notice: New downline approved.';
 
        $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);
                
    }
    
     public function sendMemberNotification($param)
    {
        $model = new RegistrationForm();
                    
        $downline_id = $param['new_member_id'];
        $upline_id = $param['upline_id'];
        $endorser_id = $param['endorser_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::MEMBER_NOTIFY_TMPL);
                
        $members = new MembersModel();
        
        $downline_info = $members->selectMemberDetails($downline_id); 
        $upline_info = $members->selectMemberDetails($upline_id);
        $endorser_info = $members->selectMemberDetails($endorser_id);
                
        $upline_name = $upline_info['first_name'] . ' ' . $upline_info['last_name'];
        $downline_name = $downline_info['first_name'] . ' ' . $downline_info['last_name'];
        $endorser_name = $endorser_info['first_name'] . ' ' . $endorser_info['last_name'];
        $downline_email = $downline_info['email'];
        $curdate = date('M d Y');
        
        $placeholders = array('UPLINE_NAME'=>$upline_name, 
                              'ENDORSER_NAME'=>$endorser_name,
                              'MEMBER_NAME'=>$downline_name,
                              'APPROVED_DATE'=>$curdate);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        $sender = 'noreply@p5partners.com';
        $sender_name = 'P5 Marketing Incorporated';
        $recipient = $downline_email;
        $subject = 'Notice: Your placement was approved.';
 
        $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);
                
    }
    
    /**
     * Send account creation notification
     * @param type $param
     */
    public function accountCreation($param)
    {
        $model = new RegistrationForm();
        
        $member_id = $param['member_id'];
        
        $reference = new ReferenceModel();
        $message_template = $reference->get_message_template(self::ACCOUNT_CREATION);
                
        $members = new MembersModel();
        $result = $members->selectMemberDetails($member_id);        

        $email = $result['email'];
        $member_name = $result['first_name'] . ' ' . $result['last_name'];
        $username = $result['username'];
        $password = $param['plain_password'];
        
        $placeholders = array('MEMBER_NAME'=>$member_name, 
                              'USERNAME'=>$username,
                              'PASSWORD'=>$password);
        
        foreach($placeholders as $key => $value){
            $message_template = str_replace('{'.$key.'}', $value, $message_template);
        }
        
        if(count($result) > 0)
        {
            $sender = 'noreply@p5partners.com';
            $sender_name = 'P5 Marketing Incorporated';
            $recipient = $email;
            $subject = 'Account Creation';
            
            $model->log_messages($sender, $sender_name, $recipient, $subject, $message_template);

        }
                
    }
    
    /**
     * Send all queued emails
     * @param type $sender
     * @param type $sender_name
     * @param type $recipient
     * @param type $subject
     * @param type $message_body
     */
    public function sendMails($sender, $sender_name, $recipient, $subject, $message_body)
    {
        Yii::app()->mailer->Host = 'localhost';
        Yii::app()->mailer->IsHTML(TRUE);
        Yii::app()->mailer->IsMail();
        Yii::app()->mailer->From = $sender;
        Yii::app()->mailer->FromName = $sender_name;
        Yii::app()->mailer->AddAddress($recipient);
        Yii::app()->mailer->Subject = $subject;
        Yii::app()->mailer->Body = $message_body;
        Yii::app()->mailer->Send();
        Yii::app()->mailer->ClearAddresses();
    }
    
}
?>
