<?php

namespace PaynetEasy\PaynetEasyApi\Query;

use PaynetEasy\PaynetEasyApi\Transport\Response;
use PaynetEasy\PaynetEasyApi\PaymentData\PaymentTransaction;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-12 at 16:43:22.
 */
class CreateCardRefQueryTest extends SaleQueryTest
{
    protected $successType = 'create-card-ref-response';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CreateCardRefQuery('_');
    }

    /**
     * @expectedException \PaynetEasy\PaynetEasyApi\Exception\ValidationException
     * @expectedExceptionMessage Only approved and finished payment transaction can be used for create-card-ref-id
     */
    public function testCreateWithNotEndedPayment()
    {
        $this->object->createRequest(parent::getPaymentTransaction());
    }

    public function testCreateRequestProvider()
    {
        return array(array
        (
            sha1
            (
                self::LOGIN .
                self::CLIENT_PAYMENT_ID .
                self::PAYNET_PAYMENT_ID .
                self::SIGNING_KEY
            ),
            'recurrent'
        ));
    }

    /**
     * @expectedException \PaynetEasy\PaynetEasyApi\Exception\ValidationException
     * @expectedExceptionMessage Some required fields missed or empty in Response: card-ref-id
     */
    public function testProcessResponseWithException()
    {
        $paymentTransaction = $this->getPaymentTransaction();

        $this->object->processResponse($paymentTransaction, new Response(array
        (
            'type'              =>  $this->successType,
            'status'            => 'processing',
            'paynet-order-id'   =>  self::PAYNET_PAYMENT_ID,
            'merchant-order-id' =>  self::CLIENT_PAYMENT_ID,
            'serial-number'     =>  md5(time())
        )));
    }

    /**
     * @dataProvider testProcessResponseApprovedProvider
     */
    public function testProcessResponseApproved(array $response)
    {
        $paymentTransaction = $this->getPaymentTransaction();

        $this->object->processResponse($paymentTransaction, new Response($response));

        $this->assertTrue($paymentTransaction->isApproved());
        $this->assertTrue($paymentTransaction->isFinished());
    }

    public function testProcessResponseApprovedProvider()
    {
        return array(array(array
        (
            'type'              =>  $this->successType,
            'status'            => 'approved',
            'card-ref-id'       =>  self::RECURRENT_CARD_FROM_ID,
            'serial-number'     =>  md5(time())
        )));
    }

    public function testProcessResponseDeclinedProvider()
    {
        return array(array(array
        (
            'type'              =>  $this->successType,
            'status'            => 'filtered',
            'card-ref-id'       =>  self::RECURRENT_CARD_FROM_ID,
            'serial-number'     =>  md5(time()),
            'error-message'     => 'test filtered message',
            'error-code'        => '8876'
        )));
    }

    public function testProcessResponseProcessingProvider()
    {
        return array(array(array
        (
            'type'              =>  $this->successType,
            'status'            => 'processing',
            'card-ref-id'       =>  self::RECURRENT_CARD_FROM_ID,
            'serial-number'     =>  md5(time())
        )));
    }

    public function testProcessResponseErrorProvider()
    {
        return array(
        array(array
        (
            'type'              =>  $this->successType,
            'status'            => 'error',
            'card-ref-id'       =>  self::RECURRENT_CARD_FROM_ID,
            'serial-number'     =>  md5(time()),
            'error-message'     => 'test error message',
            'error-code'        => '2'
        )),
        array(array
        (
            'type'              => 'validation-error',
            'serial-number'     =>  md5(time()),
            'error-message'     => 'validation-error message',
            'error-code'        => '1000'
        )),
        array(array
        (
            'type'              => 'error',
            'error-message'     => 'test type error message',
            'error-code'        => '5'
        )));
    }

    /**
     * {@inheritdoc}
     */
    protected function getPaymentTransaction()
    {
        return parent::getPaymentTransaction()
            ->setStatus(PaymentTransaction::STATUS_APPROVED);
    }
}
