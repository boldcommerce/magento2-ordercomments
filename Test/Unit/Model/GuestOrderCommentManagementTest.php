<?php
namespace Bold\OrderComment\Test\Unit\Model;

use Magento\Quote\Test\Unit\Model\GuestCart\GuestCartTestHelper;

class GuestOrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Bold\OrderComment\Model\GuestOrderCommentManagement
     */
    protected $testObject;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteIdMaskFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteIdMaskMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $orderCommentManagementMock;

    /**
     * @var string
     */
    protected $maskedCartId;

    /**
     * @var int
     */
    protected $cartId;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;
    
    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        
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
        
        $this->orderCommentManagementMock = $this->getMock(
            'Bold\OrderComment\Model\OrderCommentManagement',
            [],
            [],
            '',
            false
        );

        $this->maskedCartId = 'f216207248d65c789b17be8545e0aa73';
        $this->cartId = 123;

        $guestCartTestHelper = new GuestCartTestHelper($this);
        list($this->quoteIdMaskFactoryMock, $this->quoteIdMaskMock) = $guestCartTestHelper->mockQuoteIdMask(
            $this->maskedCartId,
            $this->cartId
        );

        $this->testObject = $objectManager->getObject(
            'Bold\OrderComment\Model\GuestOrderCommentManagement',
            [
                'orderCommentManagement' => $this->orderCommentManagementMock,
                'quoteIdMaskFactory' => $this->quoteIdMaskFactoryMock
            ]
        );
    }

    public function testSaveComment()
    {
        $comment = 'test comment';

        $orderCommentMock = $this->getMockBuilder('\Bold\OrderComment\Model\Data\OrderComment')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->orderCommentManagementMock->expects($this->once())
            ->method('saveOrderComment')
            ->with($this->cartId, $orderCommentMock)
            ->willReturn($comment);
        $result = $this->testObject->saveOrderComment($this->maskedCartId, $orderCommentMock);
        $this->assertEquals($comment, $result);
    }
}
