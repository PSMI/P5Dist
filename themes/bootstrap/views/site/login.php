<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$this->layout = '//layouts/login';
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<div class="login-title">Distributor Login</div>
<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
        'type'=>'horizontal',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<?php echo $form->textFieldRow($model,'username'); ?>

	<?php echo $form->passwordFieldRow($model,'password'); ?>

	<?php echo $form->checkBoxRow($model,'rememberMe'); ?>
    
    <center><?php echo CHtml::link('Forgot password?', Yii::app()->createUrl('site/forgot'), array('style'=>'cursor: pointer')); ?> | <?php echo CHtml::link('Go to P5 Website','http://www.p5partners.com') ?></center>

	<div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'=>'danger',
                'label'=>'Login',
                'size'=>'large'
            )); ?>            
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->