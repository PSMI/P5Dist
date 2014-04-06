<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 03-31-2014
------------------------*/

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'ipdcomm-grid',
        'type'=>'striped bordered condensed',
        //'filter' => $model->search(),
        'dataProvider' => $dataProvider,
        'htmlOptions'=>array('style'=>'font-size:12px'),
        'enablePagination' => true,
        //'template'=>"{items}",
        'columns' => array(
                        array(
                            'header' => '',
                            'value' => '$row + ($this->grid->dataProvider->pagination->currentPage
                            * $this->grid->dataProvider->pagination->pageSize + 1)',
                        ),
                        array('name'=>'member_name',
                            'header'=>'Member Name',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'commission_amount',
                            'header'=>'Commission Amount',
                            'value'=>'TransactionController::numberFormat($data["commission_amount"])',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'date_approved',
                            'header'=>'Date Approved',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'approved_by',
                            'header'=>'Approved By',
                            'htmlOptions' => array('style' => 'text-align:center'),
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'date_claimed',
                            'header'=>'Date Claimed',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'claimed_by',
                            'header'=>'Processed By',
                            'htmlOptions' => array('style' => 'text-align:center'),  
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'status',
                            'header'=>'Status',
                            'value' => 'TransactionController::getStatus($data["status"], 2)',
                            'htmlOptions' => array('style' => 'text-align:center'),  
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
        )
        ));
?>
