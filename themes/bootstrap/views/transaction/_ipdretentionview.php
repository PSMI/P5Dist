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
                        ),
                        array('name'=>'member_name',
                            'header'=>'Distributor Name',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                            'footer'=>'<strong>Total</strong>',
                            'footerHtmlOptions'=>array('style'=>'font-size:14px'),
                        ),
                        array('name'=>'receipt_no',
                            'header'=>'Receipt No.',
                            'htmlOptions' => array('style' => 'text-align:center'),  
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'quantity',
                            'header'=>'Quantity',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                            'footer'=>'<strong>'.number_format($total['total_quantity'],0).'</strong>',
                            'footerHtmlOptions'=>array('style'=>'text-align:center; font-size:14px'),
                        ),
                        array('name'=>'total',
                            'header'=>'Total Price',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.number_format($total['total_amount'],2).'</strong>',
                            'footerHtmlOptions'=>array('style'=>'text-align:right; font-size:14px'),
                        ),
                        array('name'=>'savings',
                            'header'=>'Retention Money',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.number_format($total['total_savings'],2).'</strong>',
                            'footerHtmlOptions'=>array('style'=>'text-align:right; font-size:14px'),
                        ),
                        array('name'=>'payment_type_name',
                            'header'=>'Payment Type',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'date_purchased',
                            'header'=>'Date Purchased',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'status',
                            'header'=>'Status',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
            )
        ));
?>
