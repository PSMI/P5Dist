<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 03-29-2014
------------------------*/

$this->breadcrumbs = array('Transactions'=>'#','Repeat Purchase Commission');

Yii::app()->user->setFlash('info', '<strong>Information </strong>| Next cut-off date is on '.$next_cutoff.'.');

$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'info'//=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
));
?>

<h3>Repeat Purchase Commission</h3>

<?php
//display table
if (isset($dataProvider))
{
    $this->renderPartial('_ipdrpcommissionview', array(
                'dataProvider'=>$dataProvider,
        ));
}
else
{
    
}
?>

