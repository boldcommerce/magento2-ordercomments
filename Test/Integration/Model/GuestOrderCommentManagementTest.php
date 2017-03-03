<?php
namespace Bold\OrderComment\Test\Integration\Model;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class GuestOrderCommentManagementTest
 * @package Bold\OrderComment\Test\Integration\Model
 *
 * @magentoDbIsolation enabled
 */
class GuestOrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/Sales/_files/quote_with_bundle.php
     * @return void
     */
    public function testGuestSaveOrderComment()
    {
        $objectManager = Bootstrap::getObjectManager();

        $comment = 'test comment guest';

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $objectManager->create('\Magento\Quote\Model\Quote');
        $quote->load('test01', 'reserved_order_id');

        /** @var \Magento\Quote\Model\QuoteIdMask $quoteMask */
        $quoteMask = $objectManager->create('\Magento\Quote\Model\QuoteIdMask');
        $quoteMask->load($quote->getId(), 'quote_id');
        
        $model = $objectManager->create('\Bold\OrderComment\Api\GuestOrderCommentManagementInterface');

        $data = $objectManager->create('\Bold\OrderComment\Api\Data\OrderCommentInterface');

        $data->setComment($comment);

        $model->saveOrderComment($quoteMask->getMaskedId(), $data);

        $quote->load('test01', 'reserved_order_id');

        self::assertEquals($comment, $quote->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
