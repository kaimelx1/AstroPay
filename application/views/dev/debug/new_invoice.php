<div style="padding: 20px; border: 1px dashed #aaa; border-radius: 3px; word-wrap: break-word;">

    <!-- Title -->
    <h3><?=$title?></h3><?=date('H:i:s')?><hr>

    <!-- Request -->
    <h4><u>Request data</u></h4>
    <div>
        <b><?=$requestUrl?> [POST]</b>
        <div><pre><?=json_encode(json_decode($requestData), JSON_PRETTY_PRINT)?></pre></div>
    </div>
    <hr>

    <!-- Response -->
    <h4><u>Response data</u></h4>
    <div><pre><?=json_encode($responseData, JSON_PRETTY_PRINT)?></pre></div>
    <hr>

    <!-- Status text -->
    <h4><u>Status</u></h4>
    <div><b><?=$statusText?></b></div>
    <hr>

    <?php if($statusText == 'SUCCESS') { ?>

        <!-- Help -->
        <h4><u>Help</u></h4>
        <div>
            New transaction was created. Try to check status of current transaction. You will see that it was not found in pay service system.
            Then push "Pay" button and accomplish actions to change transaction status. Try to check status one more time and you will see changes.
        </div>
        <hr>

        <!-- Buttons-->
        <button class="btn btn-success mt-5" type="button" title="Pay" data-link="<?=isset($responseData->link) ? $responseData->link : ''?>" onclick="pay($(this))">Pay</button>
        <button class="btn btn-success mt-5" type="button" title="Check Status" data-invoice="<?=$invoice?>" onclick="debugGetStatus($(this))">Debug Get Status Action</button>

    <?php } ?>
</div>

<hr>