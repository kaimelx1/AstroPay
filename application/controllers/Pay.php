<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends CI_Controller
{

    /**
     * Internal transaction statuses
     *
     * @const integer
     */
    const TR_PENDING = 1;
    const TR_FAILURE = 2;
    const TR_PAID = 3;
    const TR_REJECTED = 4;
    const TR_CANCELED = 5;
    const TR_NOT_FOUND = 6;
    const TR_DEBUG = 7;

    /**
     * AstroPay transaction results
     *
     * @const integer
     */
    const AP_SUCCESS = 0;
    const AP_NOT_FOUND = 6;
    const AP_PENDING = 7;
    const AP_REJECTED = 8;
    const AP_PAID = 9;

    /**
     * Response data
     *
     * @var array
     */
    private $serverResponse = [
        'status' => 1, // err
        'res' => '',
        'msg' => '',
    ];

    /**
     * Set server response
     *
     * @param mixed
     */
    private function setServerResponse($data) {
        $this->serverResponse['res'] = $data;
    }

    /**
     * Set server message
     *
     * @param mixed
     */
    private function setServerMessage($data) {
        $this->serverResponse['msg'] = $data;
    }

    /**
     * Set server status
     *
     * @param boolean
     */
    private function setServerStatus($boolean) {
        if($boolean) $this->serverResponse['status'] = 0; // success
        else $this->serverResponse['status'] = 1; // err
    }

    /**
     * Echo server response in JSON
     */
    private function echoServerResponse() {
        echo json_encode($this->serverResponse);
        exit();
    }

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->load->library('astroPayStreamline', null, 'astroStreamline');
    }

    /**
     * Render front page
     */
    public function index() {
        $this->data['page_center'] = '/dev/pay';
        $this->__render();
    }

    /*---------------------------------------------------------------------
                                        PUBLIC METHODS
    -----------------------------------------------------------------------*/

    /**
     * Callback function for all types of redirection
     */
    public function callback() {
        $invoice = $this->input->post('x_invoice', true);
        $status = $this->input->post('result', true);
        $this->dbChangeTransactionStatus($invoice, $status, true);
        $this->dbChangeCallbackStatus($invoice, $status, true);
        echo 'Please wait while we finish your transaction. If it is debug mode - please, close this window.';
    }

    /**
     * New Invoice transaction
     */
    public function newInvoice() {

        // get values
        $amount = intval($this->input->post('astroAmount', true));
        $bank = $this->input->post('astroBank', true);
        $country = $this->input->post('astroCountry', true);
        $cpf = $this->input->post('astroCpf', true);
        $name = $this->input->post('astroName', true);
        $email = $this->input->post('astroEmail', true);
        $currency = $this->input->post('astroCurrency', true);
        $birthdate = str_replace('-', '', $this->input->post('astroBirthdate', true));
        $invoice = $this->formInvoiceID();

        // save internal transaction
        $this->dbSaveTransaction(['invoice_id' => $invoice, 'currency' => $currency, 'amount' => $amount, 'description' => 'AstroPay Streamline', 'status' => self::TR_PENDING]);

        // get response from astroPay service
        $astroResult = $this->astroStreamline->newinvoice($invoice, $amount, $bank, $country, time(), $cpf, $name, $email, $currency, '' , $birthdate, '', '', '', '', 'https://dev.salvatory.info/pay/callback', 'https://dev.salvatory.info/pay/callback');
        $response = json_decode($astroResult['responseData']);

        // log action
        $this->logAction( ['vendor' => 'AstroPay', 'request_url' => $astroResult['requestUrl'], 'request_data' => $astroResult['requestData'], 'response_data' => $astroResult['responseData'], 'invoice_id' => $invoice]);

        // process actions
        if(is_object($response)) {
              if($response->status == self::AP_SUCCESS) {
                  $this->setServerStatus(true);
                  $this->serverResponse['link'] = $response->link;
                  $this->serverResponse['invoice'] = $invoice;
                  $this->setServerMessage('Success');
                  $this->setServerResponse(json_encode($response));
              } else {
                  $this->setServerMessage($response->desc);
                  $this->dbChangeTransactionStatus($invoice, self::TR_FAILURE, true);
              }
        } else $this->setServerMessage('Service is temporary unavailable');

        $this->echoServerResponse();
    }

    /**
     * Check invoice's status
     *
     * @param string
     */
    public function checkInvoiceStatus($invoice = '') {
        $astroResult = $this->astroStreamline->get_status($invoice);
        $response = explode('|', $astroResult['responseData']);
        $transactionResult = $response[0];
        $internalTransactionStatus = $this->compareTransactionStatus($transactionResult);
        if($internalTransactionStatus) $this->dbChangeTransactionStatus($invoice, $internalTransactionStatus, true);

        // log action
        $this->logAction( ['vendor' => 'AstroPay', 'request_url' => $astroResult['requestUrl'], 'request_data' => $astroResult['requestData'], 'response_data' => $astroResult['responseData'], 'invoice_id' => $invoice]);

        // set response
        $this->setServerStatus(true);
        $this->serverResponse['invoiceStatus'] = $transactionResult;
        $this->setServerResponse(json_encode($response));
        $this->echoServerResponse();
    }

    /**
     * Cancel transaction
     *
     * @param string
     */
    public function cancelTransaction($invoice = '') {
        if($this->dbChangeTransactionStatus($invoice, self::TR_CANCELED, true)) $this->setServerStatus(true);
        //$this->setServerMessage($this->db->get_where('transactions', ['invoice_id' => $invoice])->result_array());
        $this->echoServerResponse();
    }

    /*---------------------------------------------------------------------
                                SHOW ACTIONS  METHODS
    -----------------------------------------------------------------------*/

    /**
     * Show transactions
     */
    public function showTransactions() {
        $response = $this->db->order_by('id', 'DESC')->get('transactions')->result_array();
        $html = $this->load->view('dev/transactions', ['data' => $response, 'CI' => $this], true);
        $this->setServerStatus(true);
        $this->setServerResponse($html);
        $this->echoServerResponse();
    }

    /**
     * Show transactions
     */
    public function showActions() {
        $response = $this->db->order_by('id', 'DESC')->get('actions')->result_array();
        $html = $this->load->view('dev/actions', ['data' => $response, 'CI' => $this], true);
        $this->setServerStatus(true);
        $this->setServerResponse($html);
        $this->echoServerResponse();
    }

    /*---------------------------------------------------------------------
                                        HELPERS METHODS
    -----------------------------------------------------------------------*/

    /**
     * Form status text
     *
     * @param mixed
     *
     * @return string
     */
    public function formStatusText($status) {
        $text = '';
        if($status == self::TR_PENDING) $text = 'PENDING';
        elseif($status == self::TR_FAILURE) $text = 'FAILURE';
        elseif($status == self::TR_PAID) $text = 'PAID';
        elseif($status == self::TR_REJECTED) $text = 'REJECTED BY BANK';
        elseif($status == self::TR_CANCELED) $text = 'CANCELED BY CUSTOMER';
        elseif($status == self::TR_NOT_FOUND) $text =  'NOT FOUND IN PAY SERVICE SYSTEM';
        elseif($status == self::TR_DEBUG) $text =  'DEBUG';
        return $text;
    }

    /**
     * Form Invoice ID
     *
     * @return string
     */
    private function formInvoiceID() {
        return 'invoice' . uniqid();
    }

    /*---------------------------------------------------------------------
                               ACTION PRIVATE METHODS
    -----------------------------------------------------------------------*/

    /**
     * Find equal internal status
     *
     * @param integer
     *
     * @return integer
     */
    private function compareTransactionStatus($transactionResult) {
        $response = false;
        if($transactionResult == self::AP_NOT_FOUND) $response = self::TR_NOT_FOUND;
        elseif($transactionResult == self::AP_PENDING) $response = self::TR_PENDING;
        elseif($transactionResult == self::AP_REJECTED) $response = self::TR_REJECTED;
        elseif($transactionResult == self::AP_PAID) $response = self::TR_PAID;
        return $response;
    }

    /*---------------------------------------------------------------------
                                    DB PRIVATE METHODS
    -----------------------------------------------------------------------*/

    /**
     * Change internal transaction status
     *
     * @param integer
     * @param integer
     * @param boolean
     *
     * @return boolean
     */
    private function dbChangeTransactionStatus($id, $status, $invoice = false) {
        if($invoice) $this->db->where('invoice_id', $this->db->escape_str($id));
        else $this->db->where('id', intval($id));
        return $this->db->update('transactions', ['status' => intval($status)]);
    }

    /**
     * Change internal transaction callback status
     *
     * @param integer
     * @param integer
     * @param boolean
     *
     * @return boolean
     */
    private function dbChangeCallbackStatus($id, $status, $invoice = false) {
        if($invoice) $this->db->where('invoice_id', $this->db->escape_str($id));
        else $this->db->where('id', intval($id));
        return $this->db->update('transactions', ['callback' => 1]);
    }

    /**
     * Save transaction to DB
     *
     * @param array
     *
     * @return boolean
     */
    private function dbSaveTransaction($data) {
        $insertArray = array(
            'invoice_id' => $this->db->escape_str(isset($data['invoice_id']) ? $data['invoice_id'] : ''),
            'currency' => $this->db->escape_str(isset($data['currency']) ? $data['currency'] : ''),
            'amount' => intval(isset($data['amount']) ? $data['amount'] : ''),
            'description' => $this->db->escape_str(isset($data['description']) ? $data['description'] : ''),
            'status' => intval(isset($data['status']) ? $data['status'] : ''),
            'time' => time(),
        );

        return $this->db->insert('transactions', $insertArray);
    }

    /**
     * Log action
     *
     * @param array
     *
     * @return boolean
     */
    private function logAction($data) {
        $insertArray = array(
            'vendor' => $this->db->escape_str(isset($data['vendor']) ? $data['vendor'] : ''),
            'request_url' => $this->db->escape_str(isset($data['request_url']) ? $data['request_url'] : ''),
            'request_data' => $this->db->escape_str(isset($data['request_data']) ? $data['request_data'] : ''),
            'response_data' => $this->db->escape_str(isset($data['response_data']) ? $data['response_data'] : ''),
            'invoice_id' => $this->db->escape_str(isset($data['invoice_id']) ? $data['invoice_id'] : ''),
            'time' => time(),
        );

        return $this->db->insert('actions', $insertArray);
    }

    /*---------------------------------------------------------------------
                                   OTHER PRIVATE METHODS
   -----------------------------------------------------------------------*/

    /**
     * Add JSON header
     */
    private function addJSONHeader() {
        header('Content-Type: application/json');
    }

    /*---------------------------------------------------------------------
                                   DEBUG FORM METHODS
   -----------------------------------------------------------------------*/

    /**
     * Debug form primary interface
     */
    public function debug() {
        $this->data['page_center'] = '/dev/debug/index';
        $this->__render();
    }

    /**
     * Debug New Invoice action
     */
    public function debugNewInvoice() {

        // form invoice ID
        $invoice = $this->formInvoiceID();

        // save internal transaction
        $this->dbSaveTransaction(['invoice_id' => $invoice, 'currency' => 'USD', 'amount' => 5, 'description' => 'AstroPay Streamline Debug', 'status' => self::TR_DEBUG]);

        // get response from astroPay service
        $astroResult = $this->astroStreamline->newinvoice($invoice, 5, 'TE', 'BR', time(), '00003456789', 'ASTROPAY TESTING', 'testing@astropaycard.com', 'USD', '' , '19840304', '', '', '', '', 'https://dev.salvatory.info/pay/callback', 'https://dev.salvatory.info/pay/callback');
        $response = json_decode($astroResult['responseData']);

        // log action
        $this->logAction( ['vendor' => 'AstroPay Debug', 'request_url' => $astroResult['requestUrl'], 'request_data' => $astroResult['requestData'], 'response_data' => $astroResult['responseData'], 'invoice_id' => $invoice]);

        // process actions
        if(is_object($response)) {
            $statusText = $response->status == self::AP_SUCCESS ? 'SUCCESS' : 'FAILURE';
            $this->setServerStatus(true);
            $html = $this->load->view('/dev/debug/new_invoice', ['title' => 'New Invoice Action', 'statusText' => $statusText, 'invoice' => $invoice,'requestUrl' =>$astroResult['requestUrl'], 'requestData' => $astroResult['requestData'], 'responseData' => json_decode($astroResult['responseData'])], true);
            $this->setServerResponse($html);
        } else $this->setServerResponse('Service is temporary unavailable');

        $this->echoServerResponse();
    }

    /**
     * Debug Get Status action
     *
     * @param string
     */
    public function debugGetStatus($invoice = '') {
        // get result
        $astroResult = $this->astroStreamline->get_status($invoice);
        $response = explode('|', $astroResult['responseData']);
        $transactionResult = $response[0];
        $statusText = $this->formStatusText($this->compareTransactionStatus($transactionResult));
        // log action
        $this->logAction( ['vendor' => 'AstroPay Debug', 'request_url' => $astroResult['requestUrl'], 'request_data' => $astroResult['requestData'], 'response_data' => $astroResult['responseData'], 'invoice_id' => $invoice]);
        // set response
        $this->setServerStatus(true);
        $html = $this->load->view('/dev/debug/get_status', ['title' => 'Get Status Action','statusText' => $statusText, 'requestUrl' =>$astroResult['requestUrl'], 'requestData' => $astroResult['requestData'], 'responseData' => $astroResult['responseData']], true);
        $this->setServerResponse($html);
        $this->echoServerResponse();
    }

}