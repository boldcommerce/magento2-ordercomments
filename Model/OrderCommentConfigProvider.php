<?php

namespace Bold\OrderComment\Model;

use Bold\OrderComment\Model\Data\OrderComment;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class OrderCommentConfigProvider implements ConfigProviderInterface
{
    /**
     * @deprecated
     */
    const CONFIG_MAX_LENGTH = 'sales/ordercomments/max_length';

    /**
     * @deprecated
     */
    const CONFIG_FIELD_COLLAPSE_STATE = 'sales/ordercomments/collapse_state';

    /**
     * @deprecated
     */
    const CONFIG_SHOW_IN_CHECKOUT = 'sales/ordercomments/show_in_checkout';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    private $checkoutSession;

    /**
     * OrderCommentConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Session $checkoutSession)
    {
        $this->scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Prepare data for use in checkout javascript component
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfig()
    {
        $comment = '';
        if ($this->checkoutSession->getQuoteId()) {
            $comment = $this->checkoutSession->getQuote()->getData(OrderComment::COMMENT_FIELD_NAME) ?: '';
        }

        return [
            'show_in_checkout' => $this->scopeConfig->isSetFlag(self::CONFIG_SHOW_IN_CHECKOUT, ScopeInterface::SCOPE_WEBSITE),
            'max_length' => (int) $this->scopeConfig->getValue(self::CONFIG_MAX_LENGTH, ScopeInterface::SCOPE_WEBSITE),
            'comment_initial_collapse_state' => (int) $this->scopeConfig->getValue(self::CONFIG_FIELD_COLLAPSE_STATE, ScopeInterface::SCOPE_WEBSITE),
            'existing_comment' => $comment
        ];
    }
}
