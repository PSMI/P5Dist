<link href="css/report.css" type="text/css" rel="stylesheet" />
<page>
    <h4>Distributor Retention Payout Summary</h4>
    <h5>Member Name: <?php echo $member_name; ?></h5>
    <table id="tbl-lists2">
        <tr>
            <th class="ctr">&nbsp;</th>
            <th class="name">Distributor Name</th>
            <th>Receipt No.</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Retention Money</th>
            <th>Payment Type</th>
            <th class="date">Date Purchased</th>
            <th>Status</th>            
        </tr>
        <?php
        $ctr = 1;
        foreach ($direct_details as $row) {
            ?>
            <tr>
                <td><?php echo $ctr; ?></td>
                <td><?php echo $row['member_name']; ?></td>
                <td><?php echo $row['receipt_no']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['total']; ?></td>
                <td><?php echo $row['savings']; ?></td>
                <td><?php echo $row['payment_type_name']; ?></td>
                <td><?php echo $row['date_purchased']; ?></td>
                <td><?php echo TransactionController::getStatus($row['status'], 5); ?></td>
            </tr>
            <?php
            $ctr++;
        }
        ?>
        <tr>
            <th class="date" colspan="3">Total</th>
            <td><?php echo $total['total_quantity']; ?></td>
            <td><?php echo $total['total_amount']; ?></td>
            <td><?php echo $total['total_savings']; ?></td>
        </tr>
    </table>
</page>