<?php

/**
 * @author Noel Antonio
 * @date 02/11/2014
 */

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'placement-grid',
        'type'=>'striped bordered condensed',
        'dataProvider'=>$dataProvider,
        'enablePagination' => true,
        'selectionChanged'=>'function(id){
                var cellValue = $("#placement-grid .items tbody tr.selected td").find(".downline_id").attr("downline_attr");
                $("#hidden_member_id").val(cellValue);
                $("#index-form").submit();
        }',
        'columns' => array(
                array('name'=>'DownlineName',
                    'header'=>'<center>Downline Name</center>',
                    'type'=>'raw',
                    'value'=>'CHtml::link($data["Name"], "", array("class"=>"downline_id", "downline_attr"=>$data["ID"], "style"=>"cursor: pointer"))',                    
                    'htmlOptions' => array('style' => 'text-align:left'),  
                ), 
                array('name'=>'DateJoined',
                    'header'=>'Date Joined',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["DateEnrolled"])',                    
                    'htmlOptions' => array('style' => 'text-align:left'),  
                ), 
                array('name'=>'DateApproved',
                    'header'=>'Date Approved',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["Placement_Date"])',                    
                    'htmlOptions' => array('style' => 'text-align:left'),  
                ), 
                array('name'=>'EndorsedBy',
                    'header'=>'Endorser Name',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["Endorser"])',                    
                    'htmlOptions' => array('style' => 'text-align:left'),  
                ),
        )
));
?>
