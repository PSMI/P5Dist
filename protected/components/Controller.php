<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        /*
        public function init(){
            Yii::app()->onEndRequest = array('Controller','end');
        }
        
        
        public function end(){
            Yii::app()->db->setActive(false);
            gc_collect_cycles(); //Free up memory resources
        }
        
         * 
         */
        
        public function getStatus($status_id, $trans_type)
        {
            if ($trans_type == 1)
            {
                //loan transaction
                if ($status_id == 0)
                {
                    return "Pending";
                }
                else if($status_id == 1)
                {
                    return "Completed";
                }
                else if($status_id == 2)
                {
                    return "Filed";
                }
                else if($status_id == 3)
                {
                    return "Approved";
                }
                else
                {
                    return "Claimed";
                }
            }
            else if ($trans_type == 2)
            {
                //goc transaction
                if ($status_id == 0)
                {
                    return "Pending";
                }
                else if($status_id == 1)
                {
                    return "Approved";
                }
                else
                {
                    return "Claimed";
                }
            }
            else if ($trans_type == 3)
            {
                //direct endorsement transaction
                if ($status_id == 0)
                {
                    return "Unclaimed";
                }
                else if($status_id == 1)
                {
                    return "Approved";
                }
                else
                {
                    return "Claimed";
                }
            }
            else if ($trans_type == 4)
            {
                //bonus transaction
                if($status_id == 1)
                {
                    return "Completed";
                }
                else if($status_id == 2)
                {
                    return "Approved";
                }
                else
                {
                    return "Claimed";
                }
            }
        }
        
        public function dateFormat($date)
        {
            if ($date == '')
            {
                return false;
            }
            else
            {
                $new_date = new DateTime($date);

                return date_format($new_date, 'F j, Y, g:i a');
            }
        }

        public function numberFormat($amount)
        {
            return number_format($amount, 2);
        }
}