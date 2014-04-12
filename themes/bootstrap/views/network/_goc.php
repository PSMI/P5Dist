<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3>Group Override Commission</h3>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'index-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
            'validateOnSubmit' => true,
    ),
    'htmlOptions'=>array('class'=>'well'),
));

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'goc-grid',
        'type'=>'striped bordered condensed',
        'dataProvider'=>$dataProvider,
        'enablePagination' => true,
        'columns' => array(
                array('name'=>'LoanLevel',
                    'header'=>'Loan Level',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["member_id"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ), 
                array('name'=>'IBOCount',
                    'header'=>'IBO Count',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["last_name"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ),
                array('name'=>'CurrentIBO',
                    'header'=>'Current IBO',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["last_name"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ),
                array('name'=>'RemainingIBO',
                    'header'=>'Remaining IBO',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["last_name"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ),
                array('name'=>'Amount',
                    'header'=>'Amount',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["last_name"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ),
                array('name'=>'Status',
                    'header'=>'Status',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["last_name"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ),
        )
));

$this->endWidget(); 
?>