<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="span2">
        <div id="sidebar">
        <?php 
            /* Get user access rights by account type */
            if(!Yii::app()->user->isGuest) UserMenu::userMenus(Yii::app()->session['account_type_id']);
            
            $this->beginWidget('zii.widgets.CPortlet');
            $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'pills',
                'stacked'=>true,
                'items'=>$this->menu,
                //'htmlOptions'=>array('class'=>'operations'),
            ));
            
            $this->endWidget();
        ?>
        </div><!-- sidebar -->
    </div>
    <div class="span10">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    
</div>
<?php $this->endContent(); ?>