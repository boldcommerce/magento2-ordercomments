<?php
/**
 * Config
 *
 * @copyright Copyright © 2020 Bold Commerce BV. All rights reserved.
 * @author    dev@boldcommerce.nl
 */
declare(strict_types=1);

namespace Bold\OrderComment\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_CONFIG_MAX_LENGTH = 'sales/ordercomments/max_length';

    const XML_PATH_CONFIG_FIELD_COLLAPSE_STATE = 'sales/ordercomments/collapse_state';

    const XML_PATH_CONFIG_SHOW_IN_CHECKOUT = 'sales/ordercomments/show_in_checkout';

    const XML_PATH_CONFIG_SHOW_IN_ACCOUNT = 'sales/ordercomments/show_in_account';

    const XML_PATH_CONFIG_SHOW_IN_CART = 'sales/ordercomments/show_in_cart';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param mixed $website
     * @return bool
     */
    public function canShowInCheckout($website = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_CONFIG_SHOW_IN_CHECKOUT, ScopeInterface::SCOPE_WEBSITE, $website);
    }

    /**
     * @param mixed $website
     * @return bool
     */
    public function canShowInAccount($website = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_CONFIG_SHOW_IN_ACCOUNT, ScopeInterface::SCOPE_WEBSITE, $website);
    }

    /**
     * @param mixed $website
     * @return bool
     */
    public function canShowInCart($website = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_CONFIG_SHOW_IN_CART, ScopeInterface::SCOPE_WEBSITE, $website);
    }

    /**
     * @param mixed $website
     * @return mixed
     */
    public function getMaximumCharacterLength($website = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONFIG_MAX_LENGTH, ScopeInterface::SCOPE_WEBSITE, $website);
    }

    /**
     * @param mixed $website
     * @return mixed
     */
    public function getInitialCollapseState($website = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CONFIG_FIELD_COLLAPSE_STATE, ScopeInterface::SCOPE_WEBSITE, $website);
    }
}
