<?php

/*
 * @author : owliber
 * @date : 2014-04-07
 */
?>

<?php

$this->breadcrumbs = array('Transactions'=>'#','Retention Money');

?>

<h3>Retention Money</h3>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'searchForm',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well'),
));
$this->widget("bootstrap.widgets.TbButton", array(
                                            "label"=>"Export to PDF",
                                            //"icon"=>"icon-chevron-left",
                                            "type"=>"info",
                                            'url'=>'ipdpdfretentionsummary',
                                            //"htmlOptions"=>array("style"=>"float: right"),
                                        ));
$this->endWidget(); 


//display table
if (isset($dataProvider))
{
    $this->renderPartial('_ipdretentionview', array(
                'dataProvider'=>$dataProvider,
                'total'=>$total
        ));
}
else
{
    
}
?>

