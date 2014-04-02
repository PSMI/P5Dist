<?php

/**
 * @author Noel Antonio
 * @date 02/11/2014
 */

class NetworkController extends Controller
{
    public $layout = "column2";
    
    public function actionIpdUnilevel()
    {
        if (isset($_POST["hidden_member_id"])) {
            $member_id = $_POST["hidden_member_id"];
            Yii::app()->session['hidden_member_id'] = $member_id;
        }
        else if (Yii::app()->request->isAjaxRequest) {
            $member_id = Yii::app()->session['hidden_member_id'];
        }
        else {
            $member_id = Yii::app()->user->getId();
        }
        $model = new Members();        
        $member = $model->selectMemberDetails($member_id);
        $endorser_id = $member['ipd_endorser_id'];
        
        $genealogy['member'] = Networks::getMemberName($member_id);
        $genealogy['endorser'] = Networks::getMemberName($endorser_id);
        
        $rawData = Networks::getIPDUnilevel10thLevel($member_id);
        $final = Networks::arrangeLevel($rawData);
        
        $genealogy['total'] = $final['total'];
        $dataProvider = new CArrayDataProvider($final['network'], array(
                        'keyField' => false,
                        'pagination' => array(
                            'pageSize' => 1000,
                        ),
        ));
        $this->render('_ipdunilevel', array('dataProvider'=>$dataProvider, 'genealogy'=>$genealogy));
    }
    
    public function actionIpdDirectEndorse()
    {
        $model = new NetworksModel();
        $reference = new ReferenceModel();
        $member_id = Yii::app()->user->getId();
        $rawData = $model->getIPDDirectEndorse($member_id);
        $count = count($rawData);
        $direct_payout = $reference->get_payout_rate(TransactionTypes::DIRECT_ENDORSE);
        $dataProvider = new CArrayDataProvider($rawData, array(
                        'keyField' => false,
                        'pagination' => array(
                        'pageSize' => 25,
                    ),
        ));
        $this->render('_ipddirectendorse', array('dataProvider'=>$dataProvider, 'counter'=>$count,'payout'=>$direct_payout));
    }
    
    public function actionUnilevelDownlines()
    {
        if (isset($_POST["postData"])) 
        {
            $member_ids = $_POST["postData"];
            Yii::app()->session['ids'] = $member_ids;
        }
        else if (Yii::app()->request->isAjaxRequest) {
            $member_ids = Yii::app()->session['ids'];
        }
        
        $array = Networks::getUnilevelDownlines($member_ids);

        $dataProvider = new CArrayDataProvider($array, array(
                        'keyField' => false,
                        'pagination' => array(
                            'pageSize' => 25,
                        ),
        ));

        $this->renderPartial('_downlines', array('dataProvider'=>$dataProvider));
    }
}
?>
