<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'update-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),        
    'htmlOptions'=>array('class'=>'well'),
    ));
?>

<table style="width: 100%;">
    <tr>
        <td><?php echo $form->textFieldRow($model, 'email', array('value'=>$data["email"])); ?></td>
        <td><?php echo $form->textFieldRow($model, 'mobile_no', array('value'=>$data["mobile_no"])); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'telephone_no', array('value'=>$data["telephone_no"])); ?></td>
        <td><?php echo $form->textFieldRow($model, 'spouse_contact_no', array('value'=>$data["spouse_contact_no"])); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'beneficiary_name', array('value'=>$data["beneficiary_name"])); ?></td>
        <td><?php echo $form->textFieldRow($model, 'relationship', array('value'=>$data["relationship"])); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'tin_no', array('value'=>$data["tin_no"])); ?></td>
    </tr>
    <tr>
        <td colspan="5"><?php 
                $model->address1 = $data['address1'];
                echo $form->textAreaRow($model, 'address1', array('class'=>'span5', 'rows'=>'5')); ?></td>
    </tr>
    <tr>
        <td><?php 
                  echo $form->hiddenField($model, 'member_id', array('value'=>$data['member_id']));
                  
                  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'ajaxSubmit', 
                        'url'=>Yii::app()->createUrl('profile/update'),
                        'ajaxOptions'=>array(
                            'type'=>'POST',
                            'dataType'=>'json',
                            'data'=>'js:$("#update-form").serialize()',
                            'success'=>'function(data){
                                    if (data.code == 1){
                                        $("#dialog-msg2").html(data.msg);
                                        $("#update-redirect-box").dialog("open");
                                    } else {
                                        $("#dialog-msg").html(data.msg);
                                        $("#update-dialog-box").dialog("open");
                                    }
                            }'
                        ),
                        'label'=>'Update', 
                        'htmlOptions'=>array('id'=>'btnUpdate', 'name'=>'btnUpdate')));
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>'Cancel', 'htmlOptions'=>array('onclick'=>'$("#update-profile").dialog("close")')));
            ?></td>
    </tr>
</table>

<?php $this->endWidget(); ?>