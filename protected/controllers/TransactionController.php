<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 02-12-2014
------------------------*/

class TransactionController extends Controller
{
    public $layout = 'column2';
    
    //For Loan
    public function actionLoans()
    {
        $model = new LoanMember();

        $member_id = Yii::app()->user->getId();

        $rawData = $model->getLoanTransactions($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('loans', array('dataProvider' => $dataProvider));
    }
    
    //For GOC
    public function actionGoc()
    {
        $model = new GroupOverrideCommissionMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::GOC);
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));

        $member_id = Yii::app()->user->getId();

        $rawData = $model->getComissions($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('goc', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff));
    }
    
    //For Bonus
    public function actionBonus()
    {
        $model = new BonusMember();

        $member_id = Yii::app()->user->getId();

        $rawData = $model->getBonus($member_id);
        $promo = $model->getActivePromo();

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('bonus', array('dataProvider' => $dataProvider,'promo'=>$promo));
    }
    
    //For Direct Endorsement
    public function actionDirectendorse()
    {
        $model = new DirectEndorsementMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::DIRECT_ENDORSE);
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));

        $member_id = Yii::app()->user->getId();

        $rawData = $model->getDirectEndorsement($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('directendorse', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff));
    }
    
    //For IPD Direct Endorsement
    public function actionIpddirectendorse()
    {
        $model = new IpdDirectEndorsementMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::DIRECT_ENDORSE);////////CHANGE DIRECT_ENDORSE to IPD_DIRECT_ENDORSE
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));

        $member_id = Yii::app()->user->getId();

        $rawData = $model->getIpdDirectEndorsement($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('ipddirectendorse', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff));
    }
    
    //For Unilevel
    public function actionUnilevel()
    {
        $model = new UnilevelMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::UNILEVEL);
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));
        
        $member_id = Yii::app()->user->getId();

        $rawData = $model->getUnilevel($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('unilevel', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff));
    }
    
    //For IPD Unilevel
    public function actionIpdUnilevel()
    {
        $model = new IpdUnilevelMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::UNILEVEL);////////CHANGE UNILEVEL to IPD_UNILEVEL
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));
        
        $member_id = Yii::app()->user->getId();
        
        $rawData = $model->getUnilevel($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('ipdunilevel', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff));
    }
    
    //For IPD RP Commission
    public function actionIpdRpCommission()
    {
        $model = new IpdRpCommissionMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::IPD_REPEAT_PURCHASE_COMMISSION);
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));
        
        $member_id = Yii::app()->user->getId();

        $rawData = $model->getIpdRpCommission($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('ipdrpcommission', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff));
    }
    
    public function getStatusForButtonDisplayLoan($status_id, $status_type)
    {
        if ($status_type == 3)
        {
            //file loan button (member)
            if ($status_id == 0)
            {
                return false;
            }
            else if($status_id == 1)
            {
                return true;
            }
            else if($status_id == 2)
            {
                return false;
            }
            else if($status_id == 3)
            {
                return false;
            }
            else if($status_id == 4)
            {
                return false;
            }
        }
        else if ($status_type == 4)
        {
            //download button (member)
            if ($status_id == 0)
            {
                return false;
            }
            else if($status_id == 1)
            {
                return false;
            }
            else if($status_id == 2)
            {
                return true;
            }
            else if($status_id == 3)
            {
                return true;
            }
            else if($status_id == 4)
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
    
    public function getStatusForButtonDisplayGoc($status_id, $status_type)
    {
        if ($status_type == 1)
        {
            //approve button
            if ($status_id == 0)
            {
                return true;
            }
            else if($status_id == 1)
            {
                return false;
            }
            else if($status_id == 2)
            {
                return false;
            }
        }
        else if ($status_type == 2)
        {
            //claim button
            if ($status_id == 0)
            {
                return false;
            }
            else if($status_id == 1)
            {
                return true;
            }
            else if($status_id == 2)
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
        
        
            
            




    
    public function actionProcessTransaction()
    {
        if(!Yii::app()->request->isAjaxRequest)
        {
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
        
        if(isset($_GET["id"]))
        {
            $status = $_GET["status"];
            $loan_id = $_GET["id"];
                
            $model = new LoanMember();
            $result = $model->updateLoanStatus($loan_id, $status);

            if (count($result) > 0)
            {
                $result_code = 0;
                $result_msg = "Loan Filed.";
            }
            else
            {
                $result_code = 1;
                $result_msg = "An error occured. Please try again.";
            }
        }
        else
        {
            $result_code = 2;
            $result_msg = "An error occured. Please try again.";
        }
        
        echo CJSON::encode(array('result_code'=>$result_code, 'result_msg'=>$result_msg));
    }
    public function actionPdfLoans()
    {
        $model = new LoanMember();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        if(isset($_GET["id"]))
        {
            $loan_id = $_GET["id"];
            $member_id = $_GET["member_id"];
            $loan_type_id = $_GET["loan_type_id"];
            $level_no = $_GET["level_no"];
            $member_name = $_GET["member_name"];
            $loan_amount = $_GET["loan_amount"];
            //Convert amount in words
            //$amount_in_words = $this->convert_number_to_words($num);
            $loan_amount_nodecimal = floor($loan_amount);
            $convert_amounttoword = $this->widget('ext.NumtoWord.NumtoWord', array('num'=>$loan_amount_nodecimal)); 
            $amount_in_words = ucfirst($convert_amounttoword->result);
            //Get Payee Details
            $payee = $model->getPayeeDetails($member_id);
            if ($loan_type_id == 1)
            {
                //Check if member has previous loan/s.
                $prev_loan = $model->getPreviousLoans($member_id, $loan_id);
                $limit = 5 * count($prev_loan);
                //Get direct endorse details
                $direct_downlines = $model->getLoanDirectEndorsementDownlines($member_id, $limit);
                $html2pdf->WriteHTML($this->renderPartial('_loandirectreport', array(
                        'member_name'=>$member_name,
                        'payee'=>$payee,
                        'amount_in_words'=>$amount_in_words,
                        'loan_amount'=>$loan_amount,
                        'direct_downlines'=>$direct_downlines,
                    ), true
                 ));
                $html2pdf->Output('LoanDirect_' . $member_name . '_' . date('Y-m-d') . '.pdf', 'D');
            }
            else
            {
                //Get names of endorsed IBO
                $rawData = Networks::getDownlines($member_id);
                if (count($rawData) > 0)
                {   
                    $final = Networks::arrangeLevel($rawData);
                    //Get level 1 downline ids
                    foreach ($final['network'] as $val)
                    {
                        if ($val['Level'] == $level_no)
                        {
                            $downline_ids = $val['Members'];
                            $direct_downlines = $model->getLoanCompletionDownlines($downline_ids);
                        }
                    }
                    $html2pdf->WriteHTML($this->renderPartial('_loancompletionreport', array(
                            'member_name'=>$member_name,
                            'payee'=>$payee,
                            'amount_in_words'=>$amount_in_words,
                            'loan_amount'=>$loan_amount,
                            'direct_downlines'=>$direct_downlines,
                        ), true
                     ));
                    $html2pdf->Output('LoanCompletion_' . $member_name . '_' . date('Y-m-d') . '.pdf', 'D'); 
                }
            }
            Yii::app()->end();
        }
        }
    
    public function actionPdfGoc()
    {
        $model = new GroupOverrideCommissionMember();
        $reference = new ReferenceModel();
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        if(isset($_GET["id"]))
        {
            $member_id = $_GET["member_id"];
            $member_name = $_GET["member_name"];
            $commission_amount = $_GET["amount"];
            $ibo_count = $_GET["ibo_count"];
            //Get Payee Details
            $payee = $model->getPayeeDetails($member_id);
            //Get Payee loan balance
            $loan_balance_arr = $model->getLoanBalance($member_id);
            $loan_balance = $loan_balance_arr['loan_balance'];
            //Get names of endorsed IBO
            $rawData = Networks::getDownlines($member_id);
            $final = Networks::arrangeLevel($rawData,'ASC');
            //get cutoff dates
            $cutoff = ReferenceModel::get_cutoff_by_id($_GET["cutoff_id"]);
            $from_cutoff = $cutoff['last_cutoff_date'];
            $to_cutoff = $cutoff['next_cutoff_date'];
            //Get previous loans
            $prev_loans_total = $model->getPrevousLoans($member_id, $from_cutoff, $to_cutoff);
            $total_previous_loan = $prev_loans_total[0]['total_loan'];
            //Get downlines excluding level 1
            $downlines = array();
            foreach ($final['network'] as $val)
            {   
                if ($val['Level'] != 1)
                {
                    $exploded_members = explode(",", $val['Members']);
                    $current_level = $val["Level"];
                    $i = 0;
                    foreach ($exploded_members as $ibo_id)
                    {
                        $exist = $model->checkIfExistInCutoff($ibo_id, $from_cutoff, $to_cutoff);
                        if (count($exist) > 0)
                        {
                            $downlines_new = $model->getPayeeDownlineDetails($ibo_id);
                            $downlines["level"] = $current_level;
                            $downlines["member_name"] = $downlines_new[0]["member_name"];
                            $downlines["upline_name"] = $downlines_new[0]["upline_name"];
                            $downlines["date_joined"] = $downlines_new[0]["date_joined"];
                            $dt[] = $downlines;
                        }
                        $i++;
                    }
                }
            }
            $tax_withheld = $reference->get_variable_value('TAX_WITHHELD');
            $amount['total_commission'] = $commission_amount;
            $previous_loan = $total_previous_loan;
            $total_tax = $commission_amount * ($tax_withheld/100);
            $commission_amount = $commission_amount - $previous_loan;
            $net_commission = $commission_amount - $total_tax;
            $amount['tax'] = $total_tax;
            $amount['net_commission'] = $net_commission;
            $amount['previous_loan'] = $previous_loan;
            //Total Amount table
            $amount['cash'] = (80 / 100) * $net_commission;
            $amount['check'] = (20 / 100) * $net_commission;
            $html2pdf->WriteHTML($this->renderPartial('_gocreport', array(
                            'member_name'=>$member_name,
                            'payee'=>$payee,
                            'amount'=>$amount,
                            'downlines'=>$dt,
                            'ibo_count'=>$ibo_count,
                            'loan_balance'=>$loan_balance,
                        ), true
                     ));
            $html2pdf->Output('GOC_' . $member_name . '_'  . date('Y-m-d') . '.pdf', 'D'); 
            Yii::app()->end();
        }
        }
    
    public function actionPdfUnilevel()
    {
        if(isset($_GET["id"]) && isset($_GET['cutoff_id']))
        {
            $member_id = $_GET["id"];
            $cutoff_id = $_GET["cutoff_id"];
            $model = new UnilevelMember();
            $member = new MembersModel();            
            $reference = new ReferenceModel();
            $model->cutoff_id = $cutoff_id;
            $model->member_id = $member_id;
            $result = $model->getUnilevelDetails();
            $total_amount = $result['amount'];
            $tax_withheld = $reference->get_variable_value('TAX_WITHHELD');
            $total_tax = $total_amount * ($tax_withheld/100);
            $payout['total_amount'] = $total_amount;
            $payout['ibo_count'] = $result['ibo_count'];
            $payout['tax_amount'] = $total_tax;
            $payout['net_amount'] = $total_amount - $total_tax;
            //Payee Information
            $payee = $member->selectMemberDetails($member_id);
            $payee_endorser_id = $payee['endorser_id'];
            $payee_name = $payee['last_name'] . '_' . $payee['first_name'];
            //Endorser Information
            $endorser = $member->selectMemberDetails($payee_endorser_id);
            //Cut-Off Dates
            $cutoff = $reference->get_cutoff_by_id($cutoff_id);
            $date_from = date('Y-m-d',strtotime($cutoff['last_cutoff_date']));
            $date_to = date('Y-m-d',strtotime($cutoff['next_cutoff_date']));
            $downline = Networks::getUnilevel($member_id);
            //$downline = Networks::getDownlines($member_id);
            $unilevels = Networks::arrangeLevel($downline, 'ASC');
            foreach($unilevels['network'] as $level)
            {                    
                $levels = $level['Level'];
                 if($levels < 11)
                 {
                    if($model->is_first_transaction())
                        $downlines = Networks::getUnilevelDownlines($level['Members']);
                    else
                        $downlines = Networks::getUnilevelDownlinesByCutOff($level['Members'],$date_from,$date_to);
                    if(!is_null($downlines))
                    {
                        $unilevel['member_id'] = $member_id;
                        $total =+ count($downlines);
                        $unilevel['total'] = $total;
                        $unilevel['level'] = $levels;                     
                        $unilevel['downlines'] = $downlines;
                        $unilevel_downlines[] = $unilevel;
                    }
                 }
            }
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($this->renderPartial('_unilevelreport', array(
                    'payee'=>$payee,
                    'endorser'=>$endorser,
                    'downlines'=>$unilevel_downlines,
                    'cutoff'=>$cutoff,
                    'payout'=>$payout,
                ), true
             ));
            $html2pdf->Output('Unilevel_' . $payee_name . '_' . date('Y-m-d') . '.pdf', 'D'); 
        }
    }
    
    public function actionIpdPdfUnilevel()
    {
        if(isset($_GET["id"]) && isset($_GET['cutoff_id']))
        {
            $member_id = $_GET["id"];
            $cutoff_id = $_GET["cutoff_id"];
            $model = new IpdUnilevelMember();
            $member = new MembersModel();            
            $reference = new ReferenceModel();
            $model->cutoff_id = $cutoff_id;
            $model->member_id = $member_id;
            $result = $model->getUnilevelDetails();
            $total_amount = $result['amount'];
            $tax_withheld = $reference->get_variable_value('TAX_WITHHELD');
            $total_tax = $total_amount * ($tax_withheld/100);
            $payout['total_amount'] = $total_amount;
            $payout['ibo_count'] = $result['ibo_count'];
            $payout['tax_amount'] = $total_tax;
            $payout['net_amount'] = $total_amount - $total_tax;
            //Payee Information
            $payee = $member->selectMemberDetails($member_id);
            $payee_endorser_id = $payee['endorser_id'];
            $payee_name = $payee['last_name'] . '_' . $payee['first_name'];
            //Endorser Information
            $endorser = $member->selectMemberDetails($payee_endorser_id);
            //Cut-Off Dates
            $cutoff = $reference->get_cutoff_by_id($cutoff_id);
            $date_from = date('Y-m-d',strtotime($cutoff['last_cutoff_date']));
            $date_to = date('Y-m-d',strtotime($cutoff['next_cutoff_date']));
            $downline = Networks::getUnilevel($member_id);
            //$downline = Networks::getDownlines($member_id);
            $unilevels = Networks::arrangeLevel($downline, 'ASC');
            foreach($unilevels['network'] as $level)
            {                    
                $levels = $level['Level'];
                 if($levels < 11)
                 {
                    if($model->is_first_transaction())
                        $downlines = Networks::getUnilevelDownlines($level['Members']);
                    else
                        $downlines = Networks::getUnilevelDownlinesByCutOff($level['Members'],$date_from,$date_to);
                    if(!is_null($downlines))
                    {
                        $unilevel['member_id'] = $member_id;
                        $total =+ count($downlines);
                        $unilevel['total'] = $total;
                        $unilevel['level'] = $levels;                     
                        $unilevel['downlines'] = $downlines;
                        $unilevel_downlines[] = $unilevel;
                    }
                 }
            }
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf->WriteHTML($this->renderPartial('_ipdunilevelreport', array(
                    'payee'=>$payee,
                    'endorser'=>$endorser,
                    'downlines'=>$unilevel_downlines,
                    'cutoff'=>$cutoff,
                    'payout'=>$payout,
                ), true
             ));
            $html2pdf->Output('IPD_Unilevel_' . $payee_name . '_' . date('Y-m-d') . '.pdf', 'D'); 
        }
    }
    
    public function actionPdfDirectSummary()
    {
        $model = new DirectEndorsementMember();
        $member_id = Yii::app()->user->getId();
        $member_name_arr = $model->getMemberName($member_id);
        $member_name = $member_name_arr[0]['member_name'];
        $direct_details = $model->getDirectEndorsement($member_id);
        $html2pdf = Yii::app()->ePdf->HTML2PDF();            
        $html2pdf->WriteHTML($this->renderPartial('_directsummaryreport', array(
                'direct_details'=>$direct_details,
                'member_name'=>$member_name,
            ), true
         ));
        $html2pdf->Output('Direct_Endorsement_Summary_' . date('Y-m-d') . '.pdf', 'D'); 
        Yii::app()->end();
    }
    
    public function actionIpdPdfDirectSummary()
    {
        $model = new IpdDirectEndorsementMember();
        $member_id = Yii::app()->user->getId();
        $member_name_arr = $model->getMemberName($member_id);
        $member_name = $member_name_arr[0]['member_name'];
        $direct_details = $model->getIpdDirectEndorsement($member_id);
        $html2pdf = Yii::app()->ePdf->HTML2PDF();            
        $html2pdf->WriteHTML($this->renderPartial('_ipddirectsummaryreport', array(
                'direct_details'=>$direct_details,
                'member_name'=>$member_name,
            ), true
         ));
        $html2pdf->Output('IPD_Direct_Endorsement_Summary_' . date('Y-m-d') . '.pdf', 'D'); 
        Yii::app()->end();
    }
}
