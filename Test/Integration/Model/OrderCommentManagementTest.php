<?php
namespace Bold\OrderComment\Test\Integration\Model;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Class OrderCommentManagementTest
 * @package Bold\OrderComment\Test\Integration\Model
 *
 * @magentoDbIsolation enabled
 */
class OrderCommentManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/Sales/_files/quote_with_bundle.php
     * @return void
     */
    public function testSaveOrderComment()
    {
        $objectManager = Bootstrap::getObjectManager();

        $comment = 'test comment';

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $objectManager->create('\Magento\Quote\Model\Quote');
        $quote->load('test01', 'reserved_order_id');
        
        $model = $objectManager->create('\Bold\OrderComment\Api\OrderCommentManagementInterface');
        $data = $objectManager->create('\Bold\OrderComment\Api\Data\OrderCommentInterface');

        $data->setComment($comment);
        
        $model->saveOrderComment($quote->getId(), $data);

        $quote->load('test01', 'reserved_order_id');

        self::assertEquals($comment, $quote->getData(OrderComment::COMMENT_FIELD_NAME));
    }
}
