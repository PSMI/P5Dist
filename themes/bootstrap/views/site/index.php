<?php

/* @var $this SiteController */
$this->layout = '//layouts/column2';
$this->pageTitle = Yii::app()->name;
?>
<?php

// Render them all with single `TbAlert`
$this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '&times;', // false equals no close link
    'events' => array(),
    'htmlOptions' => array(),
    'alerts' => array(// configurations per alert type
        'success',
        'info', // you don't need to specify full config
        'warning',
        'error',
    ),
));
?>

<?php
$this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
    'heading' => 'Welcome to ' . CHtml::encode(Yii::app()->name),
    'headingOptions' => array(
        'style' => 'font-size:50px',
    ),
));
?>

<?php $this->endWidget(); ?>
