<div align="center">
    <table style='font-size: 10px' border='1'>
        <tr>
            <th style='text-align: center; padding: 5px;'>INFO</th>
            <th style='text-align: center; padding: 5px;'>REQUEST URL</th>
            <th style='text-align: center; padding: 5px;'>REQUEST DATA</th>
            <th style='text-align: center; padding: 5px;'>RESPONSE DATA</th>
        </tr>

        <?php foreach($data as $row) {  ?>
            <tr>
                <td style='padding: 5px;'>
                    <b>VENDOR:</b> <?=$row['vendor']?><br>
                    <b>ID:</b> <?=$row['id']?><br>
                    <b>INVOICE ID:</b> <?=$row['invoice_id']?><br>
                    <b>TIME:</b> <?=date('d.m.Y H:I:s', $row['time'])?>
                </td>
                <td style='padding: 5px; max-width: 100px; word-wrap: break-word;'><?=$row['request_url']?></td>
                <td style='padding: 5px; max-width: 200px; word-wrap: break-word;'><?=$row['request_data']?></td>
                <td style='padding: 5px; max-width: 200px; word-wrap: break-word;'><?=$row['response_data']?></td>
            </tr>
        <?php } ?>

    </table>
</div>