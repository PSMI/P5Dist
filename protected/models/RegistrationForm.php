<?php

/**
 * @author Noel Antonio
 * @date 03-28-2014
 */

class RegistrationForm extends CFormModel
{      
    public $_connection;
    
    public $member_id;
    public $new_member_id;   
    public $account_type_id = 3;    
    public $activation_code;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $address1;
    public $address2;
    public $address3;
    public $country_id;
    public $zip_code;
    public $gender;
    public $civil_status;
    public $birth_date;
    public $mobile_no;
    public $telephone_no;
    public $email;
    public $tin_no;
    public $company;
    public $occupation;
    public $spouse_name;
    public $spouse_contact_no;
    public $beneficiary_name;
    public $relationship;
    public $endorser_id;
    public $upline_id;
    public $upline_name;
    public $product_code;
    public $product_name;
    public $date_purchased;
    public $payment_mode_id;
    public $captcha_code;    
    public $plain_password;
    
    public function __construct() {
        $this->_connection = Yii::app()->db;
        
    }
    
    public function rules()
    {
        return array(
            //array('activation_code', 'validateCode',),
            array('activation_code', 'required','message'=>'The activation code is required to complete the registration.'),
            array('activation_code', 'length', 'min'=>20, 'max'=>20),
                        
            array('upline_id,upline_name','required','message'=>'Upline is required for new registrations.'),
            array('last_name,first_name,middle_name,gender,civil_status,mobile_no,beneficiary_name', 'required'),
            
            array('email','email','message'=>'The email address is not valid.'),
            array('email','required'),
            
            array('birth_date,date_purchased','date','format'=>'yyyy-mm-dd'),
            array('birth_date,date_purchased','required'),
            array('address1,address2,address3,country_id', 'required'),
            array('product_code,product_name,payment_mode_id', 'required'),
            
            //Not required but should be inserted
            array('zip_code,telephone_no,tin_no,company,occupation,
                  spouse_name,spouse_contact_no,beneficiary_name,relationship','safe'),
        );
    }
            
    public function attributeLabels()
    {
            return array(
                'activation_code'=>'Activation Code',
                'upline_id'=>'Place under',
                'last_name'=>'Last Name',
                'first_name'=>'First Name',
                'middle_name'=>'Middle Name',
                'address1'=>'House/Unit/Street No',
                'address2'=>'Subdivision/Village/Brgy',
                'address3'=>'Municipality/City/Province',
                'country_id'=>'Country',
                'zip_code'=>'Zip Code',
                'gender'=>'Gender',
                'civil_status'=>'Civil Status',
                'birth_date'=>'Birth date',
                'mobile_no'=>'Mobile No',
                'telephone_no'=>'Telephone No',
                'email'=>'Email',
                'tin_no'=>'TIN No',
                'company'=>'Company',
                'occupation'=>'Occupation',
                'spouse_name'=>'Spouse Name',
                'spouse_contact_no'=>'Spouse Contact No',
                'beneficiary_name'=>'Beneficiary Name',
                'relationship'=>'Relationship',
                'product_code'=>'Product Code',
                'product_name'=>'Product Name',
                'date_purchased'=>'Date Purchased',
                'payment_mode_id'=>'Payment Mode',
            );
    }
    
    
    
    public function countries()
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_countries ORDER BY country_name";
        $command =  $conn->createCommand($query);        
        $result = $command->queryAll();        
        return $result;
    }
    public function listCountries()
    {       
        return CHtml::listData($this->countries(), 'country_id', 'country_name');
    }
    
    public function occupations()
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_occupations WHERE status = 1";
        $command = $conn->createCommand($query);        
        $result = $command->queryAll();        
        return $result;
    }
    
    public function listOccupations()
    {
        return CHtml::listData($this->occupations(), 'occupation_id', 'occupation_name');
    }
    
    public function relationships()
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_relationships WHERE status = 1";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();        
        return $result;
    }
    
    public function listRelationships()
    {
        return CHtml::listData($this->relationships(), 'relationship_id', 'relationship_name');
    }
    
    public function paymentTypes()
    {
        $conn = $this->_connection;
        
        $query = "SELECT * FROM ref_paymenttypes WHERE status = 1";
        $command = $conn->createCommand($query);
        $result = $command->queryAll();        
        return $result;
    }
    
    public function listPaymentTypes()
    {
        return CHtml::listData($this->paymentTypes(), 'payment_type_id', 'payment_type_name');
    }

    public function listProducts()
    {
        return CHtml::listData(ProductsForm::selectAll(), 'product_id', 'product_name');
    }
    
    public function selectDownlines($filter)
    {
        $conn = $this->_connection;        
        $filter = "%".$filter."%";
        
        $model = new Downlines(); 
        
        $model->member_id = Yii::app()->user->getId();
        
        $placeUnder = Networks::getPlaceUnder(Yii::app()->user->getId());
        $downline_lists = Networks::autoComplete($placeUnder);
        
        $query = "SELECT
                    m.member_id,
                    CONCAT(COALESCE(md.last_name,' '), ', ', COALESCE(md.first_name,' '), ' ', COALESCE(md.middle_name,' ')) AS member_name
                  FROM members m
                    INNER JOIN member_details md ON m.member_id = md.member_id
                  WHERE (md.last_name LIKE :filter
                    OR md.first_name LIKE :filter
                    OR md.middle_name LIKE :filter)
                    AND m.member_id IN ($downline_lists)
                  ORDER BY md.last_name";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':filter', $filter);
        $result = $command->queryAll();        
        return $result;
    }    
    
    public function register()
    {
        $conn = $this->_connection;        
        $trx = $conn->beginTransaction();
        
        $account_type_id = $this->account_type_id;
        $activation_code = $this->activation_code;
        $endorser_id = $this->member_id;
        $upline_id = $this->upline_id;
        $date_joined = $this->date_purchased;
               
        /* Insert member account info */
        
        $query = "INSERT INTO members (account_type_id, activation_code, endorser_id, upline_id, placement_date, date_joined)
                  VALUES (:account_type_id, :activation_code, :endorser_id, :upline_id, now(),:date_joined)";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':account_type_id', $account_type_id);
        $command->bindParam(':activation_code', $activation_code);
        $command->bindParam(':endorser_id', $endorser_id);
        $command->bindParam(':upline_id', $upline_id);
        $command->bindParam(':date_joined', $date_joined);
        
        $result = $command->execute();
        //Get the new member_id
        $member_id = $conn->getLastInsertID();
        $this->new_member_id = $member_id;
        
        try 
        {
            if(count($result) > 0)
            {
                /* Insert member details */
                
                $query2 = "INSERT INTO member_details 
                                   (member_id, last_name, first_name, middle_name, address1, address2, address3, country_id, 
                                    zip_code, gender, civil_status, birth_date, mobile_no, telephone_no, email, tin_no, company, 
                                    occupation, spouse_name, spouse_contact_no, beneficiary_name, relationship)
                            VALUES (:member_id, :last_name, :first_name, :middle_name, :address1, :address2, :address3, :country_id,
                                    :zip_code, :gender, :civil_status, :birth_date, :mobile_no, :telephone_no, :email, :tin_no, :company,
                                    :occupation, :spouse_name, :spouse_contact_no, :beneficiary_name, :relationship)";
                
                $command2 = $conn->createCommand($query2);
                $command2->bindParam(':member_id', $member_id);
                $command2->bindParam(':last_name', $this->last_name);
                $command2->bindParam(':first_name', $this->first_name);
                $command2->bindParam(':middle_name', $this->middle_name);
                $command2->bindParam(':address1', $this->address1);
                $command2->bindParam(':address2', $this->address2);
                $command2->bindParam(':address3', $this->address3);
                $command2->bindParam(':country_id', $this->country_id);
                $command2->bindParam(':zip_code', $this->zip_code);
                $command2->bindParam(':gender', $this->gender);
                $command2->bindParam(':civil_status', $this->civil_status);
                $command2->bindParam(':birth_date', $this->birth_date);
                $command2->bindParam(':mobile_no', $this->mobile_no);
                $command2->bindParam(':telephone_no', $this->telephone_no);
                $command2->bindParam(':email', $this->email);
                $command2->bindParam(':tin_no', $this->tin_no);
                $command2->bindParam(':company', $this->company);
                $command2->bindParam(':occupation', $this->occupation);
                $command2->bindParam(':spouse_name', $this->spouse_name);
                $command2->bindParam(':spouse_contact_no', $this->spouse_contact_no);
                $command2->bindParam(':beneficiary_name', $this->beneficiary_name);
                $command2->bindParam(':relationship', $this->relationship);
                
                $result2 = $command2->execute();
                
                try
                {
                    if(count($result2) > 0)
                    {
                        //Instantiate purchases model
                        $purchase = new PurchasesModel();
                        
                        $product['member_id'] = $this->new_member_id;
                        $product['product_code'] = $this->product_code;
                        $product['product_name'] = $this->product_name;
                        $product['date_purchased'] = $this->date_purchased;
                        $product['payment_mode_id'] = $this->payment_mode_id;
        
                        $result3 = $purchase->insertPurchased($product);
                        
                        if(count($result3) > 0)
                        {
                            $username = Helpers::generate($this->new_member_id, $this->first_name, $this->last_name);
        
                            $reference = new ReferenceModel();
                            //get reference variables for maximum random password
                            $max_rand_lenth = $reference->get_variable_value('MAX_RAND_PASSWORD');
                            $password = Helpers::randomPassword($max_rand_lenth);
                            $this->plain_password = $password;
                            
                            $hashed_password = md5($password);

                            $query4 = "UPDATE members SET username = :username, `password` = :password
                                       WHERE member_id = :member_id";

                            $command4 = $conn->createCommand($query4);
                            $command4->bindParam(':username', $username);
                            $command4->bindParam(':password', $hashed_password);
                            $command4->bindParam(':member_id', $member_id);

                            $result4 = $command4->execute();
                            
                            if(count($result4) > 0)
                            {
                                //Instantiate Activation Code Model
                                $activation = new ActivationCodeModel();
                                $result5 = $activation->updateActivationCodeStatus($this->activation_code, 1);
                                
                                if(count($result5) > 0)
                                {
                                    $query5 = "INSERT INTO pending_placements (member_id, endorser_id, upline_id)
                                                VALUES (:member_id, :endorser_id, :upline_id)";
                                    
                                    $command5 = $conn->createCommand($query5);
                                    $command5->bindParam(':member_id', $this->new_member_id);
                                    $command5->bindParam(':endorser_id', $this->member_id);
                                    $command5->bindParam(':upline_id', $this->upline_id);
                                    
                                    $result5 = $command5->execute();
                                    
                                    if(count($result5) > 0)
                                    {
                                        $trx->commit();
                                        return array('result_code'=>0,
                                                     'result_msg'=>'Registration successful');
                                    }
                                    else
                                    {
                                        $trx->rollback();
                                        return array('result_code'=>6,
                                                     'result_msg'=>'Registration failed (Errcode:05)');
                                    }
                                    
                                }
                                else
                                {
                                    $trx->rollback();
                                    return array('result_code'=>5,
                                                 'result_msg'=>'Registration failed (Errcode:05)');
                                }
                                
                            }
                            else
                            {
                                $trx->rollback();
                                return array('result_code'=>4,
                                             'result_msg'=>'Registration failed (Errcode:04)'); 
                            }
        
                            
                        }
                        else
                        {
                            $trx->rollback();
                            return array('result_code'=>3,
                                         'result_msg'=>'Registration failed (Errcode:03)');
                        }
                    }
                }
                catch(PDOException $exc)
                {
                    $trx->rollback();
                    return array('result_code'=>2,
                                 'result_msg'=>'Registration failed (Errcode:02)');
                }
            }
        } 
        catch (PDOException $exc) 
        {
            $trx->rollback();
            return array('result_code'=>1,
                         'result_msg'=>'Registration failed (Errcode:x01)');
        }
    }
    
    
    public function setupAccount($member_id, $firstname, $lastname)
    {
        $conn = $this->_connection;        
        $trx = $conn->beginTransaction();
        
        $username = $this->generate($firstname, $lastname);
        
        //get reference variables for maximum random password
        $max_rand_lenth = ReferenceModel::get_variable_value('MAX_RAND_PASSWORD');
        $password = $this->randomPassword($max_rand_lenth);
        
        $query = "UPDATE members SET username = :username, `password` = :password
                   WHERE member_id = :member_id";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':username', $username);
        $command->bindParam(':password', $password);
        $command->bindParam(':member_id', $member_id);
        
        $result = $command->execute();
        
        try
        {
            if(count($result)>0)
            {
                $trx->commit();
                return true;
            }
            else
            {
                $trx->rollback();
                return false;
            }
        }
        catch(PDOException $e)
        {
            $trx->rollback();
            return false;
        }
        
        
    }
    
    public function validateAccount($email, $code)
    {
        $conn = $this->_connection;
        
        $query = "SELECT
                    *
                  FROM members m
                    INNER JOIN member_details md
                      ON m.member_id = md.member_id
                  WHERE md.email = :email AND m.activation_code = :activation_code;";
        $command = $conn->createCommand($query);
        $command->bindParam(':email', $email);
        $command->bindParam(':activation_code', $code);
        
        $result = $command->queryAll();
        
        if(count($result)>0)
            return true;
        else
            return false;
    }
    
    public function activateAccount($email,$code)
    {
        $conn = $this->_connection;
        
        $trx = $conn->beginTransaction();
        
        $query = "UPDATE members m
                INNER JOIN member_details md
                  ON m.member_id = md.member_id
                SET m.status = 1
                WHERE md.email = :email AND m.activation_code = :activation_code
                AND m.status = 0;";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':email', $email);
        $command->bindParam(':activation_code', $code);
        
        $result = $command->execute();
        
        try
        {
            if(count($result)>0)
            {
                $trx->commit();
                return true;
            }
            else
            {
                $trx->rollback();
                return false;
            }
        }
        catch(PDOException $e)
        {
            $trx->rollback();
            return false;
        }
        
    }
    
    public function validateMemberName()
    {
        $conn = $this->_connection;
        
        $last_name = trim($this->last_name);
        $first_name = trim($this->first_name);
        $middle_name = trim($this->middle_name);
                
        $query = "SELECT member_id FROM member_details
                    WHERE last_name = :last_name
                    AND first_name = :first_name
                    AND middle_name = :middle_name";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':last_name', $last_name);
        $command->bindParam(':first_name', $first_name);
        $command->bindParam(':middle_name', $middle_name);
        $result = $command->queryRow();
        
        return $result;
    }
    
    public function log_messages($sender, $sender_name, $recipient, $subject, $message_body)
    {
        $conn = $this->_connection;
        
        $query = "INSERT INTO email_messages (sender, sender_name, recipient, email_subject, message_body)
                   VALUES (:sender, :sender_name, :recipient, :email_subject, :message_body)";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':sender', $sender);
        $command->bindParam(':sender_name', $sender_name);
        $command->bindParam(':recipient', $recipient);
        $command->bindParam(':email_subject', $subject);
        $command->bindParam(':message_body', $message_body);
        $command->execute();
    }
    
    /**
     * This function is used to register an IPD distributor.
     * @author Noel Antonio
     * @return boolean TRUE - commit, FALSE - rollback
     */
    public function registerIPD()
    {
        $conn = $this->_connection;        
        $trx = $conn->beginTransaction();
        
        $account_type_id = 5;
        $activation_code = $this->activation_code;
        $endorser_id = $this->member_id;
        $date_joined = $this->date_purchased;
               
        /* Insert distributor account info */
        
        $query = "INSERT INTO distributors (account_type_id, activation_code, endorser_id, date_joined)
                  VALUES (:account_type_id, :activation_code, :endorser_id, :date_joined)";
        
        $command = $conn->createCommand($query);
        $command->bindParam(':account_type_id', $account_type_id);
        $command->bindParam(':activation_code', $activation_code);
        $command->bindParam(':endorser_id', $endorser_id);
        $command->bindParam(':date_joined', $date_joined);
        
        $result = $command->execute();
        
        // Get the new member_id
        $member_id = $conn->getLastInsertID();
        $this->new_member_id = $member_id;
        
        try 
        {
            if(count($result) > 0)
            {
                /* Insert distributor_details */
                
                $query2 = "INSERT INTO distributor_details 
                                   (distributor_id, last_name, first_name, middle_name, address1, address2, address3, country_id, 
                                    zip_code, gender, civil_status, birth_date, mobile_no, telephone_no, email, tin_no, company, 
                                    occupation, spouse_name, spouse_contact_no, beneficiary_name, relationship)
                            VALUES (:member_id, :last_name, :first_name, :middle_name, :address1, :address2, :address3, :country_id,
                                    :zip_code, :gender, :civil_status, :birth_date, :mobile_no, :telephone_no, :email, :tin_no, :company,
                                    :occupation, :spouse_name, :spouse_contact_no, :beneficiary_name, :relationship)";
                
                $command2 = $conn->createCommand($query2);
                $command2->bindParam(':member_id', $member_id);
                $command2->bindParam(':last_name', $this->last_name);
                $command2->bindParam(':first_name', $this->first_name);
                $command2->bindParam(':middle_name', $this->middle_name);
                $command2->bindParam(':address1', $this->address1);
                $command2->bindParam(':address2', $this->address2);
                $command2->bindParam(':address3', $this->address3);
                $command2->bindParam(':country_id', $this->country_id);
                $command2->bindParam(':zip_code', $this->zip_code);
                $command2->bindParam(':gender', $this->gender);
                $command2->bindParam(':civil_status', $this->civil_status);
                $command2->bindParam(':birth_date', $this->birth_date);
                $command2->bindParam(':mobile_no', $this->mobile_no);
                $command2->bindParam(':telephone_no', $this->telephone_no);
                $command2->bindParam(':email', $this->email);
                $command2->bindParam(':tin_no', $this->tin_no);
                $command2->bindParam(':company', $this->company);
                $command2->bindParam(':occupation', $this->occupation);
                $command2->bindParam(':spouse_name', $this->spouse_name);
                $command2->bindParam(':spouse_contact_no', $this->spouse_contact_no);
                $command2->bindParam(':beneficiary_name', $this->beneficiary_name);
                $command2->bindParam(':relationship', $this->relationship);
                
                $result2 = $command2->execute();
                
                try
                {
                    if(count($result2) > 0)
                    {
                        // Instantiate purchases model
                        $purchase = new PurchasesModel();
                        
                        // Retrieve product info
                        $product_info = ProductsForm::selectProductById($this->product_name);
                        
                        $product['distributor_id'] = $this->new_member_id;
                        $product['product_id'] = $this->product_name;
                        $product['amount'] = $product_info['amount'];
                        $product['date_purchased'] = $this->date_purchased;
                        $product['payment_mode_id'] = $this->payment_mode_id;
        
                        $result3 = $purchase->insertIPDPurchased($product);
                        
                        if(count($result3) > 0)
                        {
                            $username = Helpers::generate($this->new_member_id, $this->first_name, $this->last_name);
        
                            $reference = new ReferenceModel();
                            
                            // get reference variables for maximum random password
                            $max_rand_lenth = $reference->get_variable_value('MAX_RAND_PASSWORD');
                            $password = Helpers::randomPassword($max_rand_lenth);
                            $this->plain_password = $password;
                            
                            $hashed_password = md5($password);

                            $query4 = "UPDATE distributors SET username = :username, `password` = :password
                                       WHERE distributor_id = :distributor_id";

                            $command4 = $conn->createCommand($query4);
                            $command4->bindParam(':username', $username);
                            $command4->bindParam(':password', $hashed_password);
                            $command4->bindParam(':distributor_id', $member_id);

                            $result4 = $command4->execute();
                            
                            if(count($result4) > 0)
                            {
                                // Instantiate Activation Code Model
                                $activation = new ActivationCodeModel();
                                $result5 = $activation->updateActivationCodeStatus($this->activation_code, 2);
                                
                                if(count($result5) > 0)
                                {
                                    $membersModel = new MembersModel();
                                    $member_info = $membersModel->selectMemberDetails($endorser_id);
                                    $has_ipd = $member_info["has_ipd"];
                                    $has_ipd += 1;
                                    
                                    $query5 = "UPDATE members SET has_ipd = :has_ipd WHERE member_id = :endorser_id";
                                    $command5 = $conn->createCommand($query5);
                                    $command5->bindParam(':endorser_id', $endorser_id);
                                    $command5->bindParam(':has_ipd', $has_ipd);
                                    $result6 = $command5->execute();
                                    
                                    if(count($result6) > 0)
                                    {
                                        $trx->commit();
                                        return array('result_code'=>0,
                                                     'result_msg'=>'Registration successful');
                                    }
                                    else
                                    {
                                        $trx->rollback();
                                        return array('result_code'=>6,
                                                     'result_msg'=>'Registration failed (Errcode:05)');
                                    }
                                }
                                else
                                {
                                    $trx->rollback();
                                    return array('result_code'=>5,
                                                 'result_msg'=>'Registration failed (Errcode:05)');
                                }
                                
                            }
                            else
                            {
                                $trx->rollback();
                                return array('result_code'=>4,
                                             'result_msg'=>'Registration failed (Errcode:04)'); 
                            }
        
                            
                        }
                        else
                        {
                            $trx->rollback();
                            return array('result_code'=>3,
                                         'result_msg'=>'Registration failed (Errcode:03)');
                        }
                    }
                }
                catch(PDOException $exc)
                {
                    $trx->rollback();
                    return array('result_code'=>2,
                                 'result_msg'=>'Registration failed (Errcode:02)');
                }
            }
        } 
        catch (PDOException $exc) 
        {
            $trx->rollback();
            return array('result_code'=>1,
                         'result_msg'=>'Registration failed (Errcode:x01)');
        }
    }
    
}
?>
