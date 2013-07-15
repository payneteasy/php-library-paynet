<?php
namespace PaynetEasy\PaynetEasyApi\Query;

use PaynetEasy\PaynetEasyApi\Utils\Validator;
use PaynetEasy\PaynetEasyApi\OrderData\OrderInterface;
use PaynetEasy\PaynetEasyApi\Transport\Response;
use PaynetEasy\PaynetEasyApi\Exception\ValidationException;

/**
 * @see http://wiki.payneteasy.com/index.php/PnE:Recurrent_Transactions#Get_Cardholder_details_with_Card_Reference_Identifier
 */
class GetCardInfoQuery extends AbstractQuery
{
    /**
     * {@inheritdoc}
     */
    static protected $requestFieldsDefinition = array
    (
        // mandatory
        array('cardrefid',          'recurrentCardFrom.cardReferenceId',    true,    Validator::ID),
        // generated
        array('control',             null,                                  true,    null),
        // from config
        array('login',               null,                                  true,    null)
    );

    /**
     * {@inheritdoc}
     */
    static protected $controlCodeDefinition = array
    (
        'login',
        'recurrentCardFrom.cardReferenceId',
        'control'
    );

    /**
     * {@inheritdoc}
     */
    static protected $responseFieldsDefinition = array
    (
        'type',
        'card-printed-name',
        'expire-year',
        'expire-month',
        'bin',
        'last-four-digits',
        'serial-number'
    );

    /**
     * {@inheritdoc}
     */
    static protected $successResponseType = 'get-card-info-response';

    /**
     * {@inheritdoc}
     */
    protected function updateOrderOnSuccess(OrderInterface $order, Response $response)
    {
        parent::updateOrderOnSuccess($order, $response);

        $order->getRecurrentCardFrom()
            ->setCardPrintedName($response['card-printed-name'])
            ->setExpireYear($response['expire-year'])
            ->setExpireMonth($response['expire-month'])
            ->setBin($response['bin'])
            ->setLastFourDigits($response['last-four-digits']);
    }

    protected function validateResponseOnSuccess(OrderInterface $order, Response $response)
    {
        parent::validateResponseOnSuccess($order, $response);

        if(!$order->getRecurrentCardFrom())
        {
            throw new ValidationException('Recurrent card must be defined in Order');
        }
    }
}