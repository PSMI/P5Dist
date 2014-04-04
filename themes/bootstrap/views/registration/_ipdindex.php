<?php

/**
 * @author : Noel Antonio
 * @date : 2014-03-25
 */

$this->breadcrumbs = array('IPD Registration');

?>
<script type="text/javascript">
function validateUpline()
{
    var text_upline_name = $("#RegistrationForm_upline_name"),
        hidden_upline = $("#RegistrationForm_upline_id");
    
    if (text_upline_name.val() != '') {
        if (hidden_upline.val() == '') {
            $("#RegistrationForm_upline_name").val("");
        }
    }
    else {
        hidden_upline.val("");
    }
}

function submitForm()
{
    $("#hidden_flag").val(1);
    $("#verticalForm").submit();
}
</script>

<?php Yii::app()->clientScript->registerScript('ui','
         
     $(\'input[rel="tooltip"]\').tooltip();     
     var upline_id = $("#RegistrationForm_upline_id"),
         upline_name = $("#RegistrationForm[upline_name]");
    
 ', CClientScript::POS_END);
 ?>

<?php
Yii::app()->user->setFlash('danger', '<strong>Important!</strong> Please make sure to fill-up all required information specially the email address as this is required for the activation of the new distributor account.');

$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true,
        'fade'=>true,
        'closeText'=>'&times;',
        'alerts'=>array(
            'danger'
        ),
)); ?>

<h3>Independent Product Distributor (IPD) Registration</h3>
<p class="note">Please take note all fields with <span class="required">*</span> are required.</p>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'verticalForm',
    'type'=>'horizontal',
    'inlineErrors'=>false,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<?php echo $form->hiddenField($model, 'member_id'); ?>
<?php echo $form->hiddenField($model, 'upline_id'); ?>

<h5>Placement Information</h5>
<?php echo $form->textFieldRow($model,'activation_code', array(
        'class'=>'span3',
        'rel'=>'tooltip',
        'title'=>'Important: Please enter the 20 alphanumeric activation codes provided.',
)); ?>

<div class="control-group">
<?php echo CHtml::label('Place under '. '<span class="required">*</span>', 'RegistrationForm_upline_id',array('class'=>'control-label required')) ?>
    <div class="controls">   
        <?php
        
        $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'model'=>$model,
                'attribute'=>'upline_name',
                'sourceUrl'=>  Yii::app()->createUrl('registration/placeUnderIPD'),
                'options'=>array(
                    'minLength'=>'2',
                    'showAnim'=>'fold',
                    'focus' => 'js:function(event, ui){upline_name.val(ui.item["value"])}',
                    'select' => 'js:function(event, ui){upline_id.val(ui.item["id"]); }',
                ),
                'htmlOptions'=>array(
                    'class'=>'span3',
                    'rel'=>'tooltip',
                    'title'=>'Please type your downline\'s name.',
                    'autocomplete'=>'off',
                    'onblur'=>'validateUpline()'
                ),        
            ));
        
        
        ?>
        <?php echo $form->error($model, 'upline_name'); ?>
    </div>    
</div>

<h5>Distributor's Personal Information</h5>
<?php echo $form->textFieldRow($model,'last_name', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'first_name', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'middle_name', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'address1', array('class'=>'span4')); ?>
<?php echo $form->textFieldRow($model,'address2', array('class'=>'span4')); ?>
<?php echo $form->textFieldRow($model,'address3', array('class'=>'span4')); ?>
<?php echo $form->dropDownListRow($model,'country_id', array(''=>Yii::t('none','Select country')) + $model->listCountries(), array('class'=>'span2')); ?>
<?php echo $form->textFieldRow($model,'zip_code', array('class'=>'span1')); ?><br />
<?php echo $form->dropDownListRow($model,'gender', array(''=>Yii::t('none','Select gender')) + array(1=>'Male',2=>'Female'), array('class'=>'span2')); ?>
<?php echo $form->dropDownListRow($model,'civil_status', array(''=>Yii::t('none','Select civil status')) + array(1=>'Single',2=>'Married',3=>'Divorced',4=>'Separated'), array('class'=>'span2')); ?>
<div class="control-group">
<?php echo CHtml::label('Birth Date '. '<span class="required">*</span>', 'RegistrationForm_birth_date',array('class'=>'control-label required')) ?>
    <div class="controls">  
    <?php 
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'birth_date',
            'htmlOptions' => array(
                'size' => '10',
                'maxlength' => '10',
                'readonly' => true,
                'value'=>date('Y-m-d'),
            ),
            'options' => array(
                'showOn'=>'button',
                'buttonImageOnly' => true,
                'changeMonth' => true,
                'changeYear' => true,
                'buttonText'=> 'Select Birth Date',
                'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
                'dateFormat'=>'yy-mm-dd',
                'maxDate' =>'0',
                'yearRange'=>'1900:' . date('Y'),
            )
        ));
        
        echo $form->error($model, 'birth_date');
    ?>
    </div>    
</div>
<?php echo $form->textFieldRow($model,'mobile_no', array('class'=>'span2')); ?>
<?php echo $form->textFieldRow($model,'telephone_no', array('class'=>'span2')); ?>
<?php echo $form->textFieldRow($model,'email', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'tin_no', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'company', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'occupation', array('class'=>'span3')); ?>
<?php echo $form->textFieldRow($model,'spouse_name', array('class'=>'span4')); ?>
<?php echo $form->textFieldRow($model,'spouse_contact_no', array('class'=>'span2')); ?>
<?php echo $form->textFieldRow($model,'beneficiary_name', array('class'=>'span4')); ?>
<?php echo $form->textFieldRow($model,'relationship', array('class'=>'span4')); ?>

<h5>Purchased Product Information</h5>
<?php echo $form->dropDownListRow($model,'product_code', array(''=>Yii::t('none','Select product')) + $model->listProductsByPackage(2), array('class'=>'span2')); ?>
<div class="control-group">
<?php echo CHtml::label('Date Purchased '. '<span class="required">*</span>', 'RegistrationForm_date_purchased',array('class'=>'control-label required')) ?>
    <div class="controls">  
    <?php 
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'date_purchased',
            'htmlOptions' => array(
                'size' => '10',
                'maxlength' => '10',
                'readonly' => true,
                'value'=>date('Y-m-d'),
            ),
            'options' => array(
                'showOn'=>'button',
                'buttonImageOnly' => true,
                'changeMonth' => true,
                'changeYear' => true,
                'buttonText'=> 'Select Date Purchased',
                'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
                'dateFormat'=>'yy-mm-dd',
                'maxDate' =>'0',
                'yearRange'=>'1900:' . date('Y'),
            )
        ));
        
        echo $form->error($model, 'date_purchased');
    ?>
    </div>    
</div>
<?php echo $form->dropDownListRow($model,'payment_mode_id', array(''=>Yii::t('none','Select payment type')) + $model->listPaymentTypes(), array('class'=>'span2')); ?>

<?php echo CHtml::hiddenField('hidden_flag'); ?>

<!-- Button Group -->
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'reset',
    'label'=>'Reset Information',
    'type'=>'danger',
    'size'=>'large',
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'submit',
    'label'=>'Register Business Partner',
    'type'=>'primary',
    'size'=>'large',
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', 
        array('id'=>'confirm-dialog',
              'autoOpen'=>$this->showConfirm,
              'fade'=>true,
)); ?>
 
<!-- NETWORK CONFIRMATION DIALOG -->
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>IPD CONFIRMATION</h4>
</div>
 
<div id="cont-msg" class="modal-body"><p></p></div>
<div id="confirm-msg" class="modal-body">
</div>
 
<div class="modal-footer">
    <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'button',
            'label' => 'YES',
            'type' => 'default',
            'htmlOptions'=>array('onclick'=>'submitForm()')
        ));
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'button',
            'label' => 'NO',
            'type' => 'default',
            'htmlOptions'=>array('data-dismiss'=>'modal')
        ));
    ?>
</div>
<?php $this->endWidget(); // end of confirm dialog ?>

<?php $this->endWidget(); // form end widget ?>

<!-- AJAX LOADER -->
<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'ajaxloader',
        'options'=>array(
            'title'=>'Loading',
            'modal'=>true,
            'width'=>'200',
            'height'=>'45',
            'resizable'=>false,
            'autoOpen'=>false,
        ),
)); ?>

<div class="loading"></div><div class="loadingtext">Loading, please wait...</div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<!-- MESSAGE DIALOG -->
<?php $this->beginWidget('bootstrap.widgets.TbModal', 
        array('id'=>'message-dialog',
              'autoOpen'=>$this->showDialog,
              'fade'=>true,
)); ?>
 
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $this->dialogTitle; ?></h4>
</div>
 
<div class="modal-body">
    <p><?php echo $this->dialogMessage; ?></p>
</div>
 
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Close',
        'url'=>$this->errorCode > 0 ? '#' : array('registration/ipdindex'),
        'htmlOptions'=>$this->errorCode > 0 ? array('data-dismiss'=>'modal') : "",
    )); ?>
</div>
 
<?php $this->endWidget(); ?>

<?php if ($this->showConfirm): ?>
<script type="text/javascript">
    $.ajax({
        url: 'ipdconfirm',
        type: 'post',
        data: { last_name: $("#RegistrationForm_last_name").val(),
                first_name: $("#RegistrationForm_first_name").val(),
                middle_name: $("#RegistrationForm_middle_name").val()
        },
        success: function(data){
            $("#cont-msg").html("Kindly check the table below to verify the \n\
                    possible location of the new distributor. Once you proceed, it will\n\
                    be FINAL. Do you wish to continue?");
            $("#confirm-msg").html(data);
        },
        error: function(e){
            alert("Error:" + e);
        }
    });
</script>
<?php endif; ?>