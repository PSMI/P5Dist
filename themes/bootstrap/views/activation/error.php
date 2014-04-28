<?php

/*
 * @author : owliber
 * @date : 2014-02-06
 */
$this->pageTitle=Yii::app()->name;
?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'Account Activation Failed!',
    'headingOptions'=>array(
        'style'=>'font-size:40px',
    ),
)); ?>
<br />
<?php

Yii::app()->user->setFlash('error', '<strong>Oops!</strong> Something went wrong. Please call P5 support.');

$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'X', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'X'), // success, info, warning, error or danger
        ),
)); ?>

<?php $this->endWidget(); ?>
