<div align="center">
    <table style='font-size: 10px' border='1'>
        <tr>
            <th style='text-align: center; padding: 5px;'>ID</th>
            <th style='text-align: center; padding: 5px;'>INVOICE ID</th>
            <th style='text-align: center; padding: 5px;'>TIME</th>
            <th style='text-align: center; padding: 5px;'>CURRENCY</th>
            <th style='text-align: center; padding: 5px;'>AMOUNT</th>
            <th style='text-align: center; padding: 5px;'>DESCRIPTION</th>
            <th style='text-align: center; padding: 5px;'>STATUS</th>
            <th style='text-align: center; padding: 5px;'>CALLBACK</th>
        </tr>

        <?php foreach($data as $row) {  ?>
            <tr>
                <td style='text-align: center; padding: 5px;'><?=$row['id']?></td>
                <td style='text-align: center; padding: 5px;'><?=$row['invoice_id']?></td>
                <td style='text-align: center; padding: 5px;'><?=date('d.m.Y H:I:s', $row['time'])?></td>
                <td style='text-align: center; padding: 5px;'><?=$row['currency']?></td>
                <td style='text-align: center; padding: 5px;'><?=$row['amount']?></td>
                <td style='text-align: center; padding: 5px;'><?=$row['description']?></td>
                <td style='text-align: center; padding: 5px;'><?=$CI->formStatusText($row['status'])?></td>
                <td style='text-align: center; padding: 5px;'><?=$row['callback'] ? 'YES' : 'NO'?></td>
            </tr>
        <?php } ?>

    </table>
</div>