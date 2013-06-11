<?php

namespace PaynetEasy\Paynet\Workflow;

/**
 * Test class for CreateRecurrentCard.
 * Generated by PHPUnit on 2012-06-21 at 11:29:17.
 */
class CreateRecurrentCardTest extends SaleTest
{
    /**
     * Test class
     * @var string
     */
    protected $class            = 'CreateRecurrentCard';

    /**
     * @var CreateRecurrentCard
     */
    protected $query;

    const     CARD_REF_ID       = '5588943';

    protected function setUp()
    {
        parent::setUp();

        $this->transport->response2 = array
        (
            'type'              => 'create-card-ref-response',
            'status'            => 'approved',
            'card-ref-id'       => self::CARD_REF_ID,
            'serial-number'     => md5(time())
        );
    }

    /**
     * @dataProvider testStatusProvider
     */
    public function testStatus($order, $server_response, $assert)
    {
        parent::testStatus($order, $server_response, $assert);

        if(!empty($assert['status']) && $assert['status'] === Sale::STATUS_APPROVED)
        {
            $card               = $this->query->getOrder()->getRecurrentCard();

            $this->assertInstanceOf('PaynetEasy\Paynet\Data\RecurrentCardInterface', $card);
            $this->assertEquals(self::CARD_REF_ID, $card->cardrefid());
        }
    }

    /**
     * @dataProvider providerCallback
     */
    public function testCallback($order, $customer, $card, $callback, $assert)
    {
        parent::testCallback($order, $customer, $card, $callback, $assert);

        if(!empty($assert['status']) && $assert['status'] === Sale::STATUS_APPROVED)
        {
            $card               = $this->query->getOrder()->getRecurrentCard();

            $this->assertInstanceOf('PaynetEasy\Paynet\Data\RecurrentCardInterface', $card);
            $this->assertEquals(self::CARD_REF_ID, $card->cardrefid());
        }
    }
}

?>
