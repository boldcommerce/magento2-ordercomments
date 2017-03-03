<?php
namespace Bold\OrderComment\Api;

/**
 * Interface for saving the checkout comment to the quote for guest orders
 */
interface GuestOrderCommentManagementInterface
{
    /**
     * @param string $cartId
     * @param \Bold\OrderComment\Api\Data\OrderCommentInterface $orderComment
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function saveOrderComment(
        $cartId,
        \Bold\OrderComment\Api\Data\OrderCommentInterface $orderComment
    );
}
