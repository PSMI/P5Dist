<link href="css/report.css" type="text/css" rel="stylesheet" />
<page>
    <h4>Distributor Direct Endorsement Payout Summary</h4>
    <h5>Member Name: <?php echo $member_name; ?></h5>
    <table id="tbl-lists2">
        <tr>
            <th class="ctr">&nbsp;</th>
            <th class="date">Date Endorsed</th>
            <th class="name">Distributor Name</th>
            <th>Commission</th>
            <th>Date Approved</th>
            <th>Approved By</th>
            <th>Date Claimed</th>
            <th>Status</th>
        </tr>
        <?php
        $ctr = 1;
        foreach ($direct_details as $row) {
            ?>
            <tr>
                <td><?php echo $ctr; ?></td>
                <td><?php echo $row['date_created']; ?></td>
                <td><?php echo $row['member_name']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['date_approved']; ?></td>
                <td><?php echo $row['approved_by']; ?></td>
                <td><?php echo $row['date_claimed']; ?></td>
                <td><?php echo TransactionController::getStatus($row['status'], 3); ?></td>
            </tr>
            <?php
            $ctr++;
        }
        ?>
        <tr>
            <th class="date" colspan="3">Total Commission</th>
            <td><?php echo $total['total']; ?></td>
        </tr>
    </table>
</page>