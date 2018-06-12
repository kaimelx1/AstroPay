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

    var invoice;
    var link;

    /*---------------------------------------------------------------------
                                            JQUERY CODE
     -----------------------------------------------------------------------*/

    $(function() {


    }); // end of document.ready()

    /*---------------------------------------------------------------------
                                    ACTION FUNCTIONS
     -----------------------------------------------------------------------*/

    /**
     * Open iframe with response link
     */
    function pay(that) {
        jConfirm('Pay?',
            function() {
                jDialog('Payment Service', createIframe('astroPay', that.data('link')));
            }, null);
    }

    /**
     * Debug Get Status action
     */
    function debugGetStatus(that) {
        if(!dblclick) {

            jConfirm('Debug Get Status Action?',
                function() {
                    dblclick = true;
                    setSpinner(that);

                    // аякс запрос
                    $.ajax({
                        type: 'POST',
                        url: '/pay/debugGetStatus/' + that.data('invoice'),

                        // принудительно указываем заголовок Content-type для POST
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                        },

                        // если запрос прошел успешно
                        success: function (responseData) {
                            try {
                                responseData = JSON.parse(responseData);
                                if (responseData.status === SUCCESS_STATUS) {
                                    $('.statusDataBlock').prepend(responseData.res);
                                    $('html, body').animate({scrollTop: $(".statusDataBlock").offset().top - 25}, 2000);
                                } else jError();
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
                        setValue(that, 'Check Status');
                        dblclick = false;
                    });

                }, null);

        }
    }

    /**
     * Debug New Invoice action
     */
    function debugNewInvoice(that) {
        if(!dblclick) {

            jConfirm('Debug New Invoice Action?',
                function() {
                    dblclick = true;
                    setSpinner(that);

                    // аякс запрос
                    $.ajax({
                        type: 'POST',
                        url: '/pay/debugNewInvoice/',

                        // принудительно указываем заголовок Content-type для POST
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                        },

                        // если запрос прошел успешно
                        success: function (responseData) {
                            try {
                                responseData = JSON.parse(responseData);
                                if (responseData.status === SUCCESS_STATUS) {
                                    $('.newInvoiceDataBlock').append(responseData.res);
                                } else jError();
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
                        that.remove();
                        dblclick = false;
                    });

                }, null);
        }
    }

</script>