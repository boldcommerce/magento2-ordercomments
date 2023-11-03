<?php

namespace Bold\OrderComment\Plugin\Block\Checkout;

use Bold\OrderComment\Model\Config;
use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ModifyLayout
{
    private Config $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param LayoutProcessor $layoutProcessor
     * @param callable $proceed
     * @param array<int, mixed> $args
     * @return array
     */
    public function aroundProcess(LayoutProcessor $layoutProcessor, callable $proceed, ...$args)
    {
        $jsLayout = $proceed(...$args);

        if ($this->config->isMoveCommentAfterPaymentMethods()) {
            $this->moveOrderCommentComponentAfterPaymentMethods($jsLayout);
        }

        return $jsLayout;
    }

    /**
     * Make changes on layout tree
     * move Order comment component after payment methods
     * @param &$jsLayout
     */
    public function moveOrderCommentComponentAfterPaymentMethods(&$jsLayout) {
        $orderCommentComponentParentReference =& $jsLayout['components']['checkout']['children']
            ['steps']['children']['billing-step']['children']
            ['payment']['children']['payments-list']['children']
            ['before-place-order']['children'];

        // Make copy of component so that we can unset original
        $orderCommentComponent = (array) $orderCommentComponentParentReference['comment'];
        unset($orderCommentComponentParentReference['comment']);

        // In case we miss sortOrder make it first element inside afterMethods
        if (!isset($orderCommentComponent['sortOrder'])) {
            $orderCommentComponent['sortOrder'] = 0;
        }

        $jsLayout['components']['checkout']['children']
        ['steps']['children']['billing-step']['children']
        ['payment']['children']['afterMethods']['children']['comment'] = $orderCommentComponent;
    }

}
