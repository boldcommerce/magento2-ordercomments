<?php
namespace Bold\OrderComment\Model;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\ValidatorException;

class OrderCommentManagement implements \Bold\OrderComment\Api\OrderCommentManagementInterface
{
    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     *
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository Quote repository.
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $cartId
     * @param \Bold\OrderComment\Api\Data\OrderCommentInterface $orderComment
     * @return null|string
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function saveOrderComment(
        $cartId,
        \Bold\OrderComment\Api\Data\OrderCommentInterface $orderComment
    ) {
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }
        $comment = $orderComment->getComment();

        $this->validateComment($comment);

        try {
            $quote->setData(OrderComment::COMMENT_FIELD_NAME, strip_tags($comment));
            $this->quoteRepository->save($quote);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('The order comment could not be saved'));
        }

        return $comment;
    }

    /**
     * @param string $comment
     * @throws ValidatorException
     */
    protected function validateComment($comment)
    {
        $maxLength = $this->scopeConfig->getValue(OrderCommentConfigProvider::CONFIG_MAX_LENGTH);
        if ($maxLength && (mb_strlen($comment) > $maxLength)) {
            throw new ValidatorException(__('Comment is too long'));
        }
    }
}
