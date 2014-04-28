<?php

/*
 * @author : owliber
 * @date : 2014-04-07
 */
?>
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'retention-grid',
        'type'=>'striped bordered condensed',
        'dataProvider' => $dataProvider,
        'htmlOptions'=>array('style'=>'font-size:12px'),
        'enablePagination' => true,
        'columns' => array( 
                        array(
                            'header' => '',
                            'value' => '$row + ($this->grid->dataProvider->pagination->currentPage
                            * $this->grid->dataProvider->pagination->pageSize + 1)',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'transaction_date',
                            'header'=>'Transaction Date',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                            'footer'=>'<strong>Total</strong>',
                            'footerHtmlOptions'=>array('style'=>'font-size:14px'),
                        ),
                        array('name'=>'purchase_retention',
                            'header'=>'Purchase Retention',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.$total['total_purchase_retention'].'</strong>',
                            'footerHtmlOptions'=>array('style'=>'font-size:14px; text-align:right'),
                        ),
                        array('name'=>'other_retention',
                            'header'=>'Other Retention',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.$total['total_other_retention'].'</strong>',
                            'footerHtmlOptions'=>array('style'=>'font-size:14px; text-align:right'),
                        ),
                        array('name'=>'total_retention',
                            'header'=>'Total Retention',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.$total['total_retention'].'</strong>',
                            'footerHtmlOptions'=>array('style'=>'font-size:14px; text-align:right'),
                        ),
                        
                        array('name'=>'status',
                            'header'=>'Status',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
            )
        ));
?>
