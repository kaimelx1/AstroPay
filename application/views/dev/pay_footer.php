<!--Adv functions-->
<script src="/files/js/adv.js"></script>

<script>

    /*---------------------------------------------------------------------
                                                GLOBAL
     -----------------------------------------------------------------------*/

    var dblclick = false; // защита от двойного клика
    var payflag = false; // флаг для остановки цикла проверки статуса

    var SUCCESS_STATUS = 0;
    var NOT_FOUND = 6;
    var PENDING = 7;
    var REJECTED_BY_BANK = 8;
    var PAID = 9;

    /*---------------------------------------------------------------------
                                        JQUERY CODE
     -----------------------------------------------------------------------*/

    $(function() {

        //------------> SETTINGS

        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        /**
         * Card mask
         *
         * Not used
         */
        $('.backCardNumber').mask('0000000000000000');

        //------------> ACTIONS

        /**
         * Show transactions
         */
        $('.showAction').on('click', function () {

            if (!dblclick) {
                dblclick = true;
                var that = $(this);
                var type = capitalizeFirstLetter(that.data('type'));
                var method = 'show' + type;
                setValue(that, '<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    type: 'POST',
                    url: '/pay/' + method,

                    // принудительно указываем заголовок Content-type для POST
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                    },

                    // если запрос прошел успешно
                    success: function (responseData) {
                        try {
                            responseData = JSON.parse(responseData);
                            if (responseData.status === SUCCESS_STATUS) jDialog(type, responseData.res);
                            else jError();
                        } catch (e) {
                            jError(e);
                        }
                    },

                    // если не удалось отправить запрос
                    error: function (jqXHR, textStatus, errorThrown) {
                        jError(textStatus);
                    }

                    // код, который выполнится при любом исходе
                }).always(function (responseData) {
                    setValue(that, 'Show ' + type);
                    dblclick = false;
                });
            }
        });

        /**
         * Cancel transaction
         */
        $('.cancelTransactionBtn').on('click', function() {

            var that = $(this);

            jConfirm('Cancel current transaction?', function() {

                setValue(that, '<i class="fa fa-spinner fa-spin"></i>');
                var invoice = that.attr('data-invoice');
                var payBtn = $('.payBtn');

                // аякс запрос
                $.ajax({
                    type: 'POST',
                    url: '/pay/cancelTransaction/' + invoice,

                    // принудительно указываем заголовок Content-type для POST
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                    },

                    // если запрос прошел успешно
                    success: function (responseData) {
                        finishCheckingStatus('Transaction was canceled.', invoice, payBtn, 'success');
                    },

                    // если не удалось отправить запрос
                    error: function (jqXHR, textStatus, errorThrown) {
                        finishCheckingStatus('Transaction was not canceled.', invoice, payBtn, 'error');
                    }

                    // код, который выполнится при любом исходе
                }).always(function () {
                        dblclick = false;
                        payflag = true;
                        setValue(that, 'Cancel transaction');
                    });

            }, null);

        });

    }); // end of document.ready()

    /*---------------------------------------------------------------------
                                         ACTION FUNCTIONS
     -----------------------------------------------------------------------*/

    /**
     * Send data and process pay action
     */
    function astroPay(that) {
        if(!dblclick) {

            jConfirm('Pay?',
                function() {
                    dblclick = true;
                    setSpinner(that);

                    $("#astroForm").ajaxSubmit({
                        url: '/pay/newInvoice',
                        type: 'post',

                        // принудительно указываем заголовок Content-type для POST
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            jError(textStatus);
                            setValue(that, 'Pay');
                            dblclick = false;
                        },
                        success: function(responseData) {

                            try {
                                responseData = JSON.parse(responseData);
                                if(responseData.status === SUCCESS_STATUS) {
                                    // create iframe
                                    var iframeBlock = $('#iframe-block');
                                    iframeBlock.html(createIframe('paymentService', responseData.link));
                                    iframeBlock.slideDown('slow');

                                    // show cancel transaction btn
                                    var cancelTransactionBtn = $('.cancelTransactionBtn');
                                    cancelTransactionBtn.show();
                                    cancelTransactionBtn.attr('data-invoice', responseData.invoice) ;

                                    // run checking transaction status method
                                    setTimeout(function() {
                                        payflag = false;
                                        checkInvoiceStatus(responseData.invoice, that);
                                    });

                                } else {
                                    jError(responseData.msg);
                                }
                            } catch(e) {
                                jError(e);
                            }

                        },
                        complete: function () {
                            setValue(that, 'Pay');
                            dblclick = false;
                        }
                    });
                }, null);

        }
    }

    /**
     * Check invoice status
     */
    function checkInvoiceStatus(invoice, that) {
        if(!dblclick && !payflag) {

            dblclick = payflag = true;
            setValue(that, '<i class="fa fa-spinner fa-spin"></i> Pending transaction awaiting approval');

            // аякс запрос
            $.ajax({
                type: 'POST',
                url: '/pay/checkInvoiceStatus/' + invoice,

                // принудительно указываем заголовок Content-type для POST
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                },

                // если запрос прошел успешно
                success: function (responseData) {
                    try {
                        responseData = JSON.parse(responseData);
                        if (responseData.status === SUCCESS_STATUS) {

                            // show status result or run next check
                            if(responseData.invoiceStatus == NOT_FOUND) finishCheckingStatus('Transaction not found in the system.', invoice, that, 'error');
                            else if(responseData.invoiceStatus == REJECTED_BY_BANK) finishCheckingStatus('Operation rejected by the bank.', invoice, that, 'error');
                            else if(responseData.invoiceStatus == PAID) finishCheckingStatus('Amount Paid. Transaction successfully concluded.', invoice, that, 'success');
                            else setTimeout(function () { dblclick = false; checkInvoiceStatus(invoice, that); }, 5000);

                        } else {
                            jError();
                            setValue(that, 'Pay');
                            $('.cancelTransactionBtn').hide();
                            dblclick = false;
                        }
                    } catch (e) {
                        jError(e);
                        setValue(that, 'Pay');
                        $('.cancelTransactionBtn').hide();
                        dblclick = false;
                    }
                },

                // если не удалось отправить запрос
                error: function (jqXHR, textStatus, errorThrown) {
                    jError(textStatus);
                    setValue(that, 'Pay');
                    $('.cancelTransactionBtn').hide();
                    dblclick = false;
                }

                // код, который выполнится при любом исходе
            }).always(function (responseData) { payflag = false; });
        }
    }

    /**
     * Actions when finish with checking status
     */
    function finishCheckingStatus(msg, invoice, that, type) {
        if(type === 'success') jSuccess('[' + invoice + '] ' + msg);
        if(type === 'error') jError('[' + invoice + '] ' + msg);
        setValue(that, 'Pay');
        dblclick = false;
        // скрываем  и очищаем iframe
        var iframeBlock = $('#iframe-block');
        iframeBlock.slideUp('slow');
        iframeBlock.empty();
        $('.cancelTransactionBtn').hide();
    }

</script>