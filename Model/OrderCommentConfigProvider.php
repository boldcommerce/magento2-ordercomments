<?php

namespace Bold\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class OrderCommentConfigProvider implements ConfigProviderInterface
{
    const CONFIG_MAX_LENGTH = 'sales/ordercomments/max_length';
    
    const CONFIG_FIELD_COLLAPSE_STATE = 'sales/ordercomments/collapse_state';

    const CONFIG_SHOW_IN_CHECKOUT = 'sales/ordercomments/show_in_checkout';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        return [
            'show_in_checkout' => $this->scopeConfig->isSetFlag(self::CONFIG_SHOW_IN_CHECKOUT, ScopeInterface::SCOPE_WEBSITE),
            'max_length' => (int) $this->scopeConfig->getValue(self::CONFIG_MAX_LENGTH, ScopeInterface::SCOPE_WEBSITE),
            'comment_initial_collapse_state' => (int) $this->scopeConfig->getValue(self::CONFIG_FIELD_COLLAPSE_STATE, ScopeInterface::SCOPE_WEBSITE)
        ];
    }

}
