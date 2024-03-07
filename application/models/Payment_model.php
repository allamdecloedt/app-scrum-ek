<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Payment_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function stripe_payment($token_id = "", $invoice_id = "", $amount_paid = "", $stripe_secret_key = "") {
        $stripe_currency = json_decode(get_payment_settings('stripe_settings'));
        $stripe_currency = $stripe_currency[0]->stripe_currency;
        $invoice_details = $this->crud_model->get_invoice_by_id($invoice_id);
        $user_details = $this->user_model->get_student_details_by_id('student', $invoice_details['student_id']);
        require_once(APPPATH.'libraries/Stripe/init.php');
        \Stripe\Stripe::setApiKey($stripe_secret_key);

        $customer = \Stripe\Customer::create(array(
            'email' => $user_details['email'], // client email id
            'card'  => $token_id
        ));

        $charge = \Stripe\Charge::create(['customer'  => $customer->id, 'amount' => $amount_paid*100, 'currency' => $stripe_currency, 'receipt_email' => $user_details['email']]);

        die();
        if($charge->status == 'succeeded'){
            return true;
        }else {
            $this->session->set_flashdata('error_message', get_phrase('an_error_occurred_during_payment'));
            redirect(route('invoice'), 'refresh');
        }
    }
}
