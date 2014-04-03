<link href="css/report.css" type="text/css" rel="stylesheet" />
<?php
/* Payee Information */
$payee_name = $payee['last_name'] . ', ' . $payee['middle_name'] . ' ' . $payee['first_name'];
$payee_username = $payee['username'];
$payee_email = $payee['email'];
$payee_mobile_no = $payee['mobile_no'];
$payee_tel_no = $payee['telephone_no'];

$endoser_name = $endorser['last_name'] . ', ' . $endorser['middle_name'] . ' ' . $endorser['first_name'];
$cutoff_date = $cutoff['cutoff_date'];
$curdate = date('M d, Y h:ia');
?>
<page>
    <div id="header" align="center">
        <div class="logo">&nbsp;</div>
        <p class="address">Unit 6 2nd Flr. Maclane Centre, Nat�l Hi-way<br />
        San Antonio, San Pedro, Laguna<br />
        www.p5partners.com<br />
        (02)553-68-19
        </p>
    </div>
    <h4>IPD Unilevel Payout Summary </h4>
    <table id="tbl-summary">
        <tr>
            <th>Name of Payee</th>
            <td><?php echo $payee_name; ?></td>
        </tr>
        <tr>
            <th>Endorser Name</th>
            <td><?php echo $endoser_name; ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo $payee_username; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $payee_email; ?></td>
        </tr>

        <tr>
            <th>Mobile No</th>
            <td><?php echo $payee_mobile_no; ?></td>
        </tr>

        <tr>
            <th>Telephone No</th>
            <td><?php echo $payee_tel_no; ?></td>
        </tr>        
        <tr>
            <th>Date Generated</th>
            <td><?php echo $curdate; ?></td>
        </tr>
        <tr>
            <th>Cut-Off Date</th>
            <td><?php echo $cutoff_date; ?></td>
        </tr>
        <tr>
            <th>Total IPD</th>
            <td align="right"><?php echo $payout['ibo_count']; ?></td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td align="right"><?php echo number_format($payout['total_amount'], 2); ?></td>
        </tr>
        <tr>
            <th colspan="2">Deductions</th>
        </tr>
        <tr>
            <th>Tax Withheld</th>
            <td align="right">(<?php echo number_format($payout['tax_amount'], 2); ?>)</td>
        </tr>
        <tr>
            <th>Net Amount</th>
            <td align="right"><strong><?php echo number_format($payout['net_amount'], 2); ?></strong></td>
        </tr>
    </table>
    <br />
    <table id="tbl-signature">
        <tr>
            <th>Released By</th>
            <td>&nbsp;</td>
            <th>Received By</th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th>Date Released</th>
            <td>&nbsp;</td>
            <th>Date Received</th>
            <td>&nbsp;</td>
        </tr>
    </table>
    <div id="footer">
        <div class="slogan" align="center">�Finding ways in helping others is our top priority.�</div>
    </div>
</page>
<page>
    <h4>Unilevel Payout </h4>
    <table id="tbl-details">
        <tr>
            <th>Name of Payee</th>
            <td><?php echo $payee_name; ?></td>
            <th>Email</th>
            <td><?php echo $payee_email; ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo $payee_username; ?></td>
            <th>Mobile No</th>
            <td><?php echo $payee_mobile_no; ?></td>
        </tr>
        <tr>
            <th>Endorser Name</th>
            <td><?php echo $endoser_name; ?></td>
            <th>Telephone No</th>
            <td><?php echo $payee_tel_no; ?></td>
        </tr>
        <tr>
            <th>Cut-Off Date</th>
            <td><?php echo $cutoff_date; ?></td>
            <th>Date Generated</th>
            <td><?php echo $curdate; ?></td>
        </tr>
    </table> 
    <br />
    <table id="tbl-lists2">
        <tr>
            <th class="ctr">&nbsp;</th>
            <th class="ctr">Lvl</th>
            <th class="name">Name of Endorsed IPD</th>
            <th class="name">Endorser</th>
            <th class="name">Place Under</th>
            <th class="date">Date Joined</th>
        </tr>
        <?php
        if (count($downlines) > 0) {
            $ctr = 1;
            foreach ($downlines as $rows) {
                $level = $rows['level'];

                foreach ($rows['downlines'] as $row) {
                    ?>
                    <tr>
                        <td class="ctr"><?php echo $ctr; ?></td>
                        <td class="ctr"><?php echo $level; ?></td>
                        <td class="name"><?php echo $row['Name'] ?></td>
                        <td class="name"><?php echo $row['Endorser']; ?></td>
                        <td class="name"><?php echo $row['Upline']; ?></td>
                        <td class="date"><?php echo $row['DateEnrolled']; ?></td>
                    </tr>
                    <?php
                    $ctr++;
                }
            }
        }
        ?>
    </table>
    <br />
    <table id="tbl-details">
        <tr>
            <th>Total IPD</th>
            <td align="right"><?php echo $payout['ibo_count']; ?></td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td align="right"><?php echo number_format($payout['total_amount'], 2); ?></td>
        </tr>
        <tr>
            <th colspan="2" >Deductions</th>
        </tr>
        <tr>
            <th>Tax Withheld</th>
            <td align="right">(<?php echo number_format($payout['tax_amount'], 2); ?>)</td>
        </tr>
        <tr>
            <th>Net Amount</th>
            <td align="right"><strong><?php echo number_format($payout['net_amount'], 2); ?></strong></td>
        </tr>
    </table>
</page>

