<div class="container">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 pay-block">

            <!-- Header -->
            <div align="center">
                <h3>Astro Pay Test Method</h3>
                <a class="showAction" data-type="transactions">Show Transactions</a> | <a class="showAction" data-type="actions">Show Actions</a> | <a href="/pay/debug">Debug Form</a>
            </div>
            <hr>

            <!-- Data form -->
            <form id="astroForm">
                <b>Amount</b>
                <input type="text" name="astroAmount" class="form-control mb-7" placeholder="Amount" value="3">
                <b>Currency</b><br>
                <select name="astroCurrency" class="selectpicker mb-7 w-100">
                    <option value="USD">USD</option>
                    <option value="UAH">UAH</option>
                </select><br>
                <b>Country</b><br>
                <select name="astroCountry" class="selectpicker mb-7 w-100">
                    <option value="BR">Brasilia</option>
                </select><br>
                <b>Name</b>
                <input type="text" name="astroName" class="form-control mb-7" placeholder="Name" value="ASTROPAY TESTING">
                <b>Email</b>
                <input type="text" name="astroEmail" class="form-control mb-7" placeholder="Email" value="testing@astropaycard.com">
                <b>Birthdate</b>
                <div class='input-group date mb-7' id='datetimepicker'>
                    <input type="text" name="astroBirthdate" class="form-control" placeholder="Birthdate" value="1984/03/04">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                </div>

                <!-- Mandatory fields -->
                <div class="mandatoryBlock mb-7">
                    <div align="center"><b class="red">Don't change values below if expect success result</b></div>
                    <hr class="custom">
                    <b>Bank</b>
                    <input type="text" name="astroBank" class="form-control mb-7" placeholder="Bank" value="TE">
                    <b>Cpf</b>
                    <input type="text" name="astroCpf" class="form-control mb-7" placeholder="Cpf" value="00003456789">
                </div>

                <!-- Action block -->
                <button class="btn btn-success actionBtn payBtn" type="button" title="Pay" onclick="astroPay($(this))">Pay</button>
                <div id="iframe-block"></div>
                <button class="btn btn-danger cancelTransactionBtn" type="button" title="Cancel transaction">Cancel transaction</button>
            </form>

        </div>
    </div>
</div>