<?php

ini_set('display_errors','on');
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


$apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'ARE_lzAurs7MGeh7QuYnd0qkZiUkI_63msjo-Um1_o7bVVabV3T689fE-RF-Qomd5_hEGJulY5A1blzT',     // ClientID
            'EJ6ireJSPnzZko-oKuC-03Nu6PAi_5PtMUBXE0lYSkIHzDYhulMrVgS5N9wectYpj3qY6PDiVK-Yt7YN'      // ClientSecret
        )
);
$apiContext->setConfig(
      array(
        'mode' => 'sandbox',
      )
);
//set payer
$payer = new Payer();
$payer->setPaymentMethod("paypal");

//set items
$item1 = new Item();
$item1->setName('Abbonamento Mensile planningram.com - Entry Level Plan')
    ->setCurrency('EUR')
    ->setQuantity(1)
    ->setSku("AA001") // Similar to `item_number` in Classic API
    ->setPrice(8);

$itemList = new ItemList();
$itemList->setItems(array($item1));

$amount = new Amount();
$amount->setCurrency("EUR")
    ->setTotal(8);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setDescription("Payment description")
    ->setInvoiceNumber(uniqid());



$baseUrl = 'http://paypal.digitalizeweb.eu';
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true")
    ->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");



$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));


$request = clone $payment;

$payment->create($apiContext);
$approvalUrl = $payment->getApprovalLink();
die($approvalUrl);
/*
try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    print("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    exit(1);
}
*/





print_r($payer);
