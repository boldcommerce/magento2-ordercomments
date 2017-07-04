<?php

namespace Bold\OrderComment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class OrderCommentConfigProvider implements ConfigProviderInterface
{
    public function getConfig()
    {
        return [
            'max_length' => 360,
        ];
    }

}