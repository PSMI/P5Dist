<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'brand' => '<img src ="' . Yii::app()->request->baseUrl . '/images/p5-header.png" />',
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Welcome '.Yii::app()->user->getDistributorName().'!','url'=>'','visible'=>!Yii::app()->user->isGuest),
                array('label'=>'www.p5partners.com', 'url'=>'http://www.p5partners.com'),
                array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest,'icon'=>'icon-off'),
            ),
        ),
    ),
    'htmlOptions'=>array('style'=>'text-align:bottom'),
    
)); ?>
    
<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by <?php echo Yii::app()->params['companyName']; ?>.<br/>
		All Rights Reserved.
                <?php
                $this->widget('ext.scrolltop.ScrollTop', array(
                    //Default values
                    'fadeTransitionStart'=>10,
                    'fadeTransitionEnd'=>200,
                    'speed' => 'slow'
                ));
                ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
