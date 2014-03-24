<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'reset-form',
        'type'=>'vertical',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
));
?>
<table>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'reset_username'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'email'); ?></td>
    </tr>
</table>
<br/>
<?php 
$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit', 
        'label'=>'Submit',
        'type'=>'primary',
)); 

$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button', 
        'label'=>'Back',
        'type'=>'primary',
        'htmlOptions'=>array('onclick'=>'location.href="' . Yii::app()->createUrl("site/login") . '";')
)); 
?> 

<?php $this->endWidget(); ?>

<!-- dialog box -->
<?php 
$trigger = false;
$buttons = array();
if ($this->showDialog) 
{
    $buttons = array(
                'OK'=>'js:function(){
                    $(this).dialog("close");
                }'
            );
    $trigger = $this->showDialog;
}
else if ($this->showRedirect) 
{
    $buttons = array(
                'OK'=>'js:function(){
                    location.href = "' . Yii::app()->createUrl("site/login") . '";
                }'
            );
    $trigger = $this->showRedirect;
}

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'dialog-box',
        'options'=>array(
            'title'=>$this->title,
            'modal'=>true,
            'width'=>'350',
            'height'=>'auto',
            'resizable'=>false,
            'autoOpen'=>$trigger,
            'buttons'=>$buttons
        ),
)); ?>

<br />
<?php echo $this->msg; ?>
<br />

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!-- dialog box -->