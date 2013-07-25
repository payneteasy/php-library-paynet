<?php

use PaynetEasy\PaynetEasyApi\PaymentData\Payment;
use PaynetEasy\PaynetEasyApi\PaymentProcessor;

require_once './common/autoload.php';
require_once './common/functions.php';

session_start();

/**
 * Обратите внимание, что для выполнения этого запроса необходимо сначала провести
 * платеж одним из следующих способов: sale, preauth, sale-form, preauth-form
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Card_Registration
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Process_Initial_Payment
 *
 * Если платеж был сохранен - получим его сохраненную версию, иначе создадим новый.
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Card_registration_request_parameters
 * @see \PaynetEasy\PaynetEasyApi\Query\CreateCardRefQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\Payment
 */
$payment = $loadPayment() ?: new Payment(array
(
    'client_payment_id'     => 'CLIENT-112244',
    'paynet_payment_id'     =>  1969595
));

/**
 * Установим конфигурацию для выполнения запроса
 *
 * @see \PaynetEasy\PaynetEasyApi\Query\CreateCardRefQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\PaymentData\QueryConfig
 * @see functions.php, $getConfig()
 */
$payment->setQueryConfig($getConfig());

/**
 * Платеж обязательно должен быть успешно завершен
 */
$payment->setProcessingStage(Payment::STAGE_FINISHED);
$payment->setStatus(Payment::STATUS_APPROVED);

/**
 * Создадим обработчик платежей и назначим обработчики для разных событий, происходящих при обработке платежа
 *
 * @see ./common/functions.php
 * @see PaynetEasy\PaynetEasyApi\PaymentProcessor::executeQuery()
 * @see PaynetEasy\PaynetEasyApi\PaymentProcessor::callHandler()
 */
$paymentProcessor = new PaymentProcessor(array
(
    PaymentProcessor::HANDLER_CATCH_EXCEPTION     => $displayException,
    PaymentProcessor::HANDLER_SAVE_PAYMENT        => $savePayment,
    PaymentProcessor::HANDLER_FINISH_PROCESSING   => $displayEndedPayment
));

/**
 * Вызов этого метода создаст в объекте Payment объект RecurrentCard
 *
 * @see \PaynetEasy\PaynetEasyApi\Query\CreateCardRefQuery::updatePaymentOnSuccess()
 */
$paymentProcessor->executeQuery('create-card-ref', $payment);