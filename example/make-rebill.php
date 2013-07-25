<?php

use PaynetEasy\PaynetEasyApi\PaymentData\Payment;
use PaynetEasy\PaynetEasyApi\PaymentData\Customer;
use PaynetEasy\PaynetEasyApi\PaymentProcessor;

require_once './common/autoload.php';
require_once './common/functions.php';

session_start();

/**
 * Обратите внимание, что для выполнения этого запроса необходимо сначала
 * получить id кредитной карты, выполнив запрос create-card-ref
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Process_Recurrent_Payment
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Card_Registration
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Process_Initial_Payment
 *
 * Если платеж был сохранен - получим его сохраненную версию, иначе создадим новый.
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Recurrent_Payment_request_parameters
 * @see \PaynetEasy\PaynetEasyApi\Query\MakeRebillQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\Payment
 */
$payment = $loadPayment() ?: new Payment(array
(
    'client_payment_id'     => 'CLIENT-112244',
    'description'           => 'This is test payment',
    'amount'                =>  0.99,
    'currency'              => 'USD',
    'ip_address'            => '127.0.0.1'
));

/**
 * Установим конфигурацию для выполнения запроса
 *
 * @see \PaynetEasy\PaynetEasyApi\Query\MakeRebillQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\QueryConfig
 * @see functions.php, $getConfig()
 */
$payment->setQueryConfig($getConfig());

/**
 * Для этого запроса необходимо передать данные клиента
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Recurrent_Payment_request_parameters
 * @see \PaynetEasy\PaynetEasyApi\Query\MakeRebillQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\Customer
 */
$payment->setCustomer(new Customer(array
(
    'ip_address'            => '127.0.0.1'
)));

/**
 * Для этого запроса необходимо передать ID кредитной карты
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Recurrent_Payment_request_parameters
 * @see \PaynetEasy\PaynetEasyApi\Query\MakeRebillQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\RecurrentCard
 */
$payment->setRecurrentCardFrom(new RecurrentCard(array('cardrefid' => 8058)));

/**
 * Создадим обработчик платежей и назначим обработчики для разных событий, происходящих при обработке платежа
 *
 * @see ./common/functions.php
 * @see PaynetEasy\PaynetEasyApi\PaymentProcessor::executeWorkflow()
 * @see PaynetEasy\PaynetEasyApi\PaymentProcessor::callHandler()
 */
$paymentProcessor = new PaymentProcessor(array
(
    PaymentProcessor::HANDLER_CATCH_EXCEPTION     => $displayException,
    PaymentProcessor::HANDLER_SAVE_PAYMENT        => $savePayment,
    PaymentProcessor::HANDLER_STATUS_UPDATE       => $displayWaitPage,
    PaymentProcessor::HANDLER_SHOW_HTML           => $displayResponseHtml,
    PaymentProcessor::HANDLER_FINISH_PROCESSING   => $displayEndedPayment
));

/**
 * Каждый вызов этого метода выполняет определенный запрос к API Paynet,
 * выбор запроса зависит от этапа обработки платежа
 *
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\Payment::$processingStage
 * @see \PaynetEasy\PaynetEasyApi\PaymentProcessor::executeWorkflow()
 * @see \PaynetEasy\PaynetEasyApi\Workflow\AbstractWorkflow::processPayment()
 */
$paymentProcessor->executeWorkflow('make-rebill', $payment, $_REQUEST);
