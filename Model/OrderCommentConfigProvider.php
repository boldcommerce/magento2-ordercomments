<?php

namespace Bold\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class OrderCommentConfigProvider implements ConfigProviderInterface
{
    const CONFIG_MAX_LENGTH = 'sales/ordercomments/max_length';
    
    const CONFIG_FIELD_COLLAPSE_STATE = 'sales/ordercomments/collapse_state';

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
            'max_length' => (int) $this->scopeConfig->getValue(self::CONFIG_MAX_LENGTH),
            'comment_initial_collapse_state' => (int) $this->scopeConfig->getValue(self::CONFIG_FIELD_COLLAPSE_STATE)
        ];
    }

}
