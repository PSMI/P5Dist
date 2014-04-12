<?php

/**
 * @author Noel Antonio
 * @date 02/11/2014
 */

?>
<style type="text/css">
    table#summary{font-size:14px; width:100%;}
    table#summary, table#summary th, table#summary td{border:1px solid #e1e1e1; border-collapse: collapse; padding: 2px 10px 2px 10px}
    table#summary td.data{color:#0088cc}
</style>
<?php $this->breadcrumbs = array('Networks'=>'#',
    'Unilevel'=>  Yii::app()->createUrl('network/unilevel'),
);
?>
<?php Yii::app()->clientScript->registerScript('ui','
            function goto_data(id){
                    $("html,body").animate({scrollTop: $("#"+id).offset().top},"slow");
            }', CClientScript::POS_END);
?>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
    'heading'=>'My Unilevel',
    'headingOptions'=>array('style'=>'font-size:200%')
)); ?>

<p style="font-size:14px"> All your direct endorsements will become your first level and the direct endorsements of your first level will become your second level and so on and so forth. 
    To qualify in Unilevel Bonus, you must complete Five (5) direct endorsements.</p>
  <table with="100%" id="summary">
      <tr>
          <td width="15%" align="right">Unilevel of</td>
          <td width="75%" class="data"><?php echo $genealogy['member']; ?></td>
      </tr>
      <tr>
          <td width="15%" align="right">Endorser</td>
          <td width="75%" class="data"><?php echo $genealogy['endorser']; ?></td>
      </tr>
      <tr>
          <td width="15%" align="right">Upline</td>
          <td width="75%" class="data"><?php echo $genealogy['upline']; ?></td>
      </tr>
      <tr>
          <td align="right">Total network</td>
          <td class="data"><?php echo $genealogy['total']; ?></td>
      </tr>
  </table>
<?php $this->endWidget(); ?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'index-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
            'validateOnSubmit' => true,
    ),
));
/*
//echo '<h3><a href="" style="text-decoration: none; color: black">Unilevel</a></h3>';
echo '<table>';
echo '<tr>';
echo '<td>';
$this->widget('bootstrap.widgets.TbLabel', array(
    'type'=>'default',
    'label'=>'Genealogy of',
));
echo '</td>';
echo '<td>';
$this->widget('bootstrap.widgets.TbLabel', array(
    'type'=>'info',
    'label'=>$member_name,
));
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td>';
$this->widget('bootstrap.widgets.TbLabel', array(
    'type'=>'default',
    'label'=>'Total Network:',
));
echo '</td>';
echo '<td>';
$this->widget('bootstrap.widgets.TbLabel', array(
    'type'=>'info',
    'label'=>$counter,
));
echo '</td>';
echo '</tr>';
echo '</table>';
$this->endWidget(); 
*/

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'unilevel-grid',
        'type'=>'striped bordered condensed',
        'dataProvider'=>$dataProvider,
        'enablePagination' => false,
        'columns' => array(
                array('name'=>'Level',
                    'header'=>'<center>Level</center>',
                    'type'=>'raw',
                    'value'=>'CHtml::encode($data["Level"])',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ), 
                array('name'=>'IBOCount',
                    'header'=>'<center>IBO Count</center>',
                    'type'=>'raw',
                    'value'=>'CHtml::ajaxLink($data["Total"],
                              Yii::app()->createUrl("network/unilevelDownlines"), 
                              array(
                                    "type"=>"post",
                                    "data" => array("postData"=>$data["Members"]),
                                    "success" => "function(data){
                                        $(\"#data\").html(data);
                                        $(\"#data\").show();
                                        goto_data(\"data\");
                                    }"
                              )
                        )',
                    'htmlOptions' => array('style' => 'text-align:center'),    
                ),
        )
        ));

echo CHtml::hiddenField('hidden_member_id', '', array('id'=>'hidden_member_id'));
?>
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Back',
    'icon'=>'icon-chevron-left',
    'type'=>'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'htmlOptions'=>array('onclick'=>'history.back()'),
)); ?>
   
<?php $this->endWidget(); ?>

<div id="data" style="display: none"><?php echo $this->renderPartial('_downlines', array('dataProvider'=>$dataProvider)); ?></div>