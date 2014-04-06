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

