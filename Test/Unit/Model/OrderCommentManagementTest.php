<?php
namespace Bold\OrderComment\Test\Unit\Model;

use Bold\OrderComment\Model\Data\OrderComment;
use Bold\OrderComment\Model\OrderCommentManagement;

class OrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;

    /**
     * @var OrderCommentManagement
     */
    protected $testObject;

    public function setUp()
    {
        $this->quoteRepositoryMock = $this->getMock('\Magento\Quote\Api\CartRepositoryInterface');

        $this->quoteMock = $this->getMock(
            '\Magento\Quote\Model\Quote',
            [
                'getItemsCount',
                'save',
                '__wakeup'
            ],
            [],
            '',
            false
        );

        $this->testObject = new OrderCommentManagement($this->quoteRepositoryMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Cart 123 doesn't contain products
     */
    public function testSaveCommentWithEmptyCart()
    {
        $cartId = 123;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')->with($cartId)->will($this->returnValue($this->quoteMock));
        $this->quoteMock->expects($this->once())->method('getItemsCount')->will($this->returnValue(0));
        
        $orderCommentMock = $this->getMockBuilder('\Bold\OrderComment\Model\Data\OrderComment')
            ->disableOriginalConstructor();

        $this->testObject->saveOrderComment($cartId, $orderCommentMock->getMock());
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage The order comment could not be saved
     */
    public function testSaveCommentWhenCouldNotSaveQuote()
    {
        $cartId = 123;

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')->with($cartId)->will($this->returnValue($this->quoteMock));
        $this->quoteMock->expects($this->once())->method('getItemsCount')->will($this->returnValue(12));
        
        $exceptionMessage = 'The order comment could not be saved';
        $exception = new \Magento\Framework\Exception\CouldNotSaveException(__($exceptionMessage));
        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->willThrowException($exception);

        $orderCommentMock = $this->getMockBuilder('\Bold\OrderComment\Model\Data\OrderComment')
            ->disableOriginalConstructor()
            ->getMock();

        $this->testObject->saveOrderComment($cartId, $orderCommentMock);
    }
    
    public function testSaveComment()
    {
        $cartId = 123;
        $comment = 'test comment';

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')->with($cartId)->will($this->returnValue($this->quoteMock));
        $this->quoteMock->expects($this->once())->method('getItemsCount')->will($this->returnValue(12));
        
        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->will($this->returnSelf());

        $orderCommentMock = $this->getMockBuilder('\Bold\OrderComment\Model\Data\OrderComment')
            ->disableOriginalConstructor()
            ->getMock();

        $orderCommentMock->expects($this->once())
            ->method('getComment')
            ->willReturn($comment);

        $this->testObject->saveOrderComment($cartId, $orderCommentMock);

        $this->assertEquals($comment, $this->quoteMock->getData(OrderComment::COMMENT_FIELD_NAME));
    }

    public function testSaveCommentWithTags()
    {
        $cartId = 123;
        $comment = 'test comment<script>alert("abcd");</script><?php die("qwerty")?>';

        $this->quoteRepositoryMock->expects($this->once())
            ->method('getActive')->with($cartId)->will($this->returnValue($this->quoteMock));
        $this->quoteMock->expects($this->once())->method('getItemsCount')->will($this->returnValue(12));

        $this->quoteRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->quoteMock)
            ->will($this->returnSelf());

        $orderCommentMock = $this->getMockBuilder('\Bold\OrderComment\Model\Data\OrderComment')
            ->disableOriginalConstructor()
            ->getMock();

        $orderCommentMock->expects($this->once())
            ->method('getComment')
            ->willReturn($comment);

        $this->testObject->saveOrderComment($cartId, $orderCommentMock);

        $this->assertEquals(strip_tags($comment), $this->quoteMock->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
