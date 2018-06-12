/*---------------------------------------------------------------------
                                    JQUERY DIALOGS
 -----------------------------------------------------------------------*/

/**
 *
 * Error alert
 *
 * @param content
 */
function jError(content) {

    content = content || '';

    $.alert({
        title: 'Error',
        content: content,
        type: 'red',
        icon: 'fa fa-thumbs-o-down'
    });
}

/**
 *
 * Success alert
 *
 * @param content
 */
function jSuccess(content) {

    content = content || '';

    $.alert({
        title: 'Success',
        content: content,
        type: 'green',
        icon: 'fa fa-thumbs-o-up'
    });
}

/**
 * Confirm
 *
 * @param content
 * @param onConfirmFunction
 * @param onCancelFunction
 */
function jConfirm(content, onConfirmFunction, onCancelFunction) {

    $.confirm({
        title: 'Confirmation',
        content: content,
        type: 'orange',
        icon: 'fa fa-question',
        buttons: {
            ok: {
                text: 'Ok',
                action: function () {
                    if( typeof onConfirmFunction == 'function') onConfirmFunction();
                }
            },
            cancel: {
                text: 'Cancel',
                action: function () {
                    if( typeof onCancelFunction == 'function') onCancelFunction();
                }
            }
        }
    });
}

/**
 * Dialog
 *
 * @param title
 * @param content
 */
function jDialog(title, content) {

    title = title || '';
    content = content || '';

    $.dialog({
        columnClass: 'col-md-8 col-md-offset-2',
        title: title,
        content: content
    });
}

/*---------------------------------------------------------------------
                                        OTHER
 -----------------------------------------------------------------------*/

/**
 * Create Iframe
 */
function createIframe(name, src) {
    src = src || 'javascript:false'; // пустой src

    var tmpElem = document.createElement('div');

    // в старых IE нельзя присвоить name после создания iframe
    // поэтому создаём через innerHTML
    tmpElem.innerHTML = '<iframe style="width: 100%; height: 350px;" name="' + name + '" id="' + name + '" src="' + src + '">';
    return tmpElem.firstChild;
}

/**
 * Set spinner to button
 */
function setSpinner(btn) {
    btn.html('<i class="fa fa-spinner fa-spin"></i>');
}

/**
 * Set value to button
 */
function setValue(btn, value) {
    btn.html(value);
}

/**
 * Capitalize first letter
 */
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/**
 * Проверка номера карты по алгоритму Луна
 */
function Moon(card_number) {
    var arr = [];
    card_number = card_number.toString();

    for(var i = 0; i < card_number.length; i++) {
        if(i % 2 === 0) {
            var m = parseInt(card_number[i]) * 2;
            if(m > 9) {
                arr.push(m - 9);
            } else {
                arr.push(m);
            }
        } else {
            var n = parseInt(card_number[i]);
            arr.push(n)
        }
    }
    //console.log(arr);
    var summ = arr.reduce(function(a, b) { return a + b; });
    return Boolean(!(summ % 10));
}