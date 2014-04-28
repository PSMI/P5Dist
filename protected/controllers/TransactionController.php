<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 02-12-2014
------------------------*/

class TransactionController extends Controller
{
    public $layout = 'column2';
        
    //For IPD Direct Endorsement
    public function actionIpddirectendorse()
    {
        $model = new IpdDirectEndorsementMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::IPD_DIRECT_ENDORSE);
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));

        $member_id = Yii::app()->user->getId();
        $total = $model->getPayoutTotal($member_id);

        $rawData = $model->getIpdDirectEndorsement($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('ipddirectendorse', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff, 'total'=>$total));
    }
    
    //For IPD Unilevel
    public function actionIpdUnilevel()
    {
        $model = new IpdUnilevelMember();
        $reference = new ReferenceModel();
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::IPD_UNILEVEL);////////CHANGE UNILEVEL to IPD_UNILEVEL
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
        
        $cutoff = $reference->get_cutoff_dates(TransactionTypes::REPEAT_PURCHASE_COMMISSION);
        $next_cutoff = date('M d Y',strtotime($cutoff['next_cutoff_date']));
        
        $member_id = Yii::app()->user->getId();

        $rawData = $model->getIpdRpCommission($member_id);
        $total = $model->getPayoutTotal($member_id);

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('ipdrpcommission', array('dataProvider' => $dataProvider,'next_cutoff'=>$next_cutoff, 'total'=>$total));
    }
    
    public function actionIpdRetention()
    {
        $model = new RetentionMoney();
        
        $model->member_id = Yii::app()->user->getId();
        $rawData = $model->getSavings();
        $total = $model->getTotals();

        $dataProvider = new CArrayDataProvider($rawData, array(
                                                'keyField' => false,
                                                'pagination' => array(
                                                'pageSize' => 10,
                                            ),
                                ));

        $this->render('ipdretention', array('dataProvider' => $dataProvider,'total'=>$total));
    }
    /*
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
    */
    
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
            $payout['ipd_count'] = $result['ipd_count'];
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
            $downline = Networks::getIPDUnilevel10thLevel($member_id);
            //$downline = Networks::getDownlines($member_id);
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            
            if (count($downline) > 1)
            {
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
                
                $html2pdf->WriteHTML($this->renderPartial('_ipdunilevelreport', array(
                        'payee'=>$payee,
                        'endorser'=>$endorser,
                        'downlines'=>$unilevel_downlines,
                        'cutoff'=>$cutoff,
                        'payout'=>$payout,
                    ), true
                 ));
            }
            else
            {
                $payout['total_amount'] = 0;
                $payout['ipd_count'] = 0;
                $payout['tax_amount'] = 0;
                $payout['net_amount'] = 0;
            
                $html2pdf->WriteHTML($this->renderPartial('_ipdunilevelreport', array(
                        'payee'=>$payee,
                        'endorser'=>$endorser,
                        'downlines'=>$unilevel_downlines = Array(),
                        'cutoff'=>$cutoff,
                        'payout'=>$payout,
                    ), true
                 ));
            }
            
            $html2pdf->Output('Distributor_Unilevel_' . $payee_name . '_' . date('Y-m-d') . '.pdf', 'D'); 
        }
    }   
    
    public function actionIpdPdfDirectSummary()
    {
        $model = new IpdDirectEndorsementMember();
        $member_id = Yii::app()->user->getId();
        
        $member_name_arr = $model->getMemberName($member_id);
        $member_name = $member_name_arr[0]['member_name'];
        
        $direct_details = $model->getIpdDirectEndorsement($member_id);
        $total = $model->getPayoutTotal($member_id);
        
        $html2pdf = Yii::app()->ePdf->HTML2PDF();            
        $html2pdf->WriteHTML($this->renderPartial('_ipddirectsummaryreport', array(
                'direct_details'=>$direct_details,
                'member_name'=>$member_name,
                'total'=>$total,
            ), true
         ));
        $html2pdf->Output('Distributor_Direct_Endorsement_Summary_' . date('Y-m-d') . '.pdf', 'D'); 
        Yii::app()->end();
    }
    
    public function actionIpdPdfRpCommissionSummary()
    {
        $model = new IpdRpCommissionMember();
        $member_id = Yii::app()->user->getId();
        
        $member_name_arr = $model->getMemberName($member_id);
        $member_name = $member_name_arr[0]['member_name'];
        
        $direct_details = $model->getIpdRpCommission($member_id);
        $total = $model->getPayoutTotal($member_id);
        
        $html2pdf = Yii::app()->ePdf->HTML2PDF();            
        $html2pdf->WriteHTML($this->renderPartial('_ipdrpcommissionsummaryreport', array(
                'direct_details'=>$direct_details,
                'member_name'=>$member_name,
                'total'=>$total,
            ), true
         ));
        $html2pdf->Output('Distributor_Repeat_Purchase_Commission' . date('Y-m-d') . '.pdf', 'D'); 
        Yii::app()->end();
    }
    
    public function actionIpdPdfRetentionSummary()
    {
        $model = new RetentionMoney();
        $member_id = Yii::app()->user->getId();
        
        $member_name_arr = $model->getMemberName($member_id);
        $member_name = $member_name_arr[0]['member_name'];
        
        $direct_details = $model->getSavings($member_id);
        $total = $model->getTotals($member_id);
        
        $html2pdf = Yii::app()->ePdf->HTML2PDF();            
        $html2pdf->WriteHTML($this->renderPartial('_ipdretentionsummaryreport', array(
                'direct_details'=>$direct_details,
                'member_name'=>$member_name,
                'total'=>$total,
            ), true
         ));
        $html2pdf->Output('Distributor_Retention_Summary_' . date('Y-m-d') . '.pdf', 'D'); 
        Yii::app()->end();
    }
}
