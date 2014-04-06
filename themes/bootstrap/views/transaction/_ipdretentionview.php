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
                        array('name'=>'date_purchased',
                            'header'=>'Date Purchased',
                            'value'=>'date("M d, Y",strtotime($data["date_purchased"]))',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                            'footer'=>'<strong>Total</strong>',
                            'footerHtmlOptions'=>array('style'=>'font-size:14px'),
                        ),
                        array('name'=>'product_name',
                            'header'=>'Product',
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
                        array('name'=>'srp',
                            'header'=>'SRP',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                        ),
                        array('name'=>'discount',
                            'header'=>'Discount (%)',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'net_price',
                            'header'=>'Net Price',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                        ),
                        array('name'=>'total',
                            'header'=>'Total Price',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.number_format($total['total_amount'],2).'</strong>',
                            'footerHtmlOptions'=>array('style'=>'text-align:right; font-size:14px'),
                        ),
                        array('name'=>'savings',
                            'header'=>'Savings',
                            'htmlOptions' => array('style' => 'text-align:right'), 
                            'headerHtmlOptions' => array('style' => 'text-align:right'),
                            'footer'=>'<strong>'.number_format($total['total_savings'],2).'</strong>',
                            'footerHtmlOptions'=>array('style'=>'text-align:right; font-size:14px'),
                        ),
            )
        ));
?>
