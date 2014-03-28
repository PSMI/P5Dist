<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'change-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),        
    'htmlOptions'=>array('class'=>'well'),
    ));
?>

<table style="width: 100%;">
    <tr>
        <td><?php echo CHtml::label('Current Password: ', 'txtCurrentPass'); ?></td>
        <td><?php echo CHtml::passwordField('txtCurrentPass'); ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::label('New Password: ', 'txtNewPass'); ?></td>
        <td><?php echo CHtml::passwordField('txtNewPass'); ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::label('Confirm Password: ', 'txtConfirmPass'); ?></td>
        <td><?php echo CHtml::passwordField('txtConfirmPass'); ?></td>
    </tr>
    <tr>
        <td><?php   
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Change', 'htmlOptions'=>array('id'=>'btnChange', 'name'=>'btnChange')));
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>'Cancel', 'htmlOptions'=>array('onclick'=>'$("#change-password").dialog("close")')));
            ?></td>
    </tr>
</table>

<?php $this->endWidget(); ?>