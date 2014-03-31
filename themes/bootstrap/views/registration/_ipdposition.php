<?php

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'confirm-grid',
        'type'=>'striped bordered condensed',
        'dataProvider' => $dataProvider,
        'htmlOptions'=>array('style'=>'font-size:12px'),
        'enablePagination' => false,
        'columns' => array(
                        array('name'=>'distributor_name',
                            'header'=>'Distributor Name',
                            'value'=>'CHtml::encode($data["member_name"])',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'endorser_name',
                            'header'=>'IBO Endorser Name',
                            'value'=>'CHtml::encode($data["endorser_name"])',
                            'htmlOptions' => array('style' => 'text-align:center'),
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
        )
));
?>
