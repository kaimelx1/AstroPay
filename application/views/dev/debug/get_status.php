<div style="padding: 20px; border: 1px dashed #aaa; border-radius: 3px; word-wrap: break-word;">

    <!-- Title -->
    <h3><?=$title?></h3> <?=date('H:i:s')?><hr>

    <!-- Request-->
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

</div>

<hr>