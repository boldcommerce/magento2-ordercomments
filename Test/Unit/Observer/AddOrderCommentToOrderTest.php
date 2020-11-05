<?php
namespace Bold\OrderComment\Test\Unit\Observer;

use Bold\OrderComment\Observer\AddOrderCommentToOrder;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Bold\OrderComment\Model\Data\OrderComment;
use PHPUnit\Framework\TestCase;

class AddOrderCommentToOrderTest extends TestCase
{
    protected $objectManager;

    /**
     * @var AddOrderCommentToOrder
     */
    protected $observer;
    
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->observer = new AddOrderCommentToOrder();
    }
    
    public function testExecute()
    {
        $comment = 'test comment';

        $observerMock = $this->createMock('Magento\Framework\Event\Observer');
        $eventMock = $this->createPartialMock('Magento\Framework\Event', ['getData']);

        $quoteMock = $this->createPartialMock('Magento\Quote\Model\Quote', ['getData']);
        $orderMock = $this->createPartialMock('Magento\Sales\Model\Order', []);

        $map = [
            ['quote', null, $quoteMock],
            ['order', null, $orderMock]
        ];
        
        $observerMock->expects($this->atLeast(2))
            ->method('getEvent')
            ->willReturn($eventMock);
        $eventMock->expects($this->atLeast(2))
            ->method('getData')
            ->will($this->returnValueMap($map));

        $quoteMock->expects($this->atLeastOnce())
            ->method('getData')
            ->with(OrderComment::COMMENT_FIELD_NAME)
            ->willReturn($comment);
        
        $this->observer->execute($observerMock);

        $this->assertEquals($comment, $orderMock->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
