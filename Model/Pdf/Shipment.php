<?php

declare(strict_types = 1);

/**
 * Shipment
 *
 * @copyright Copyright © 2018 Bold Commerce BV. All rights reserved.
 * @author    dev@boldcommerce.nl
 */

namespace Bold\OrderComment\Model\Pdf;

use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Sales\Model\Order\Pdf\Total\Factory;
use Magento\Sales\Model\Order\Pdf\ItemsFactory;
use Magento\Sales\Model\Order\Pdf\Shipment as CoreShipment;

class Shipment extends CoreShipment
{
    protected $orderCommentInserter;

    public function __construct(
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        Config $pdfConfig,
        Factory $pdfTotalFactory,
        ItemsFactory $pdfItemsFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        OrderComment $orderCommentInserter,
        array $data = []
    ) {
        $this->orderCommentInserter = $orderCommentInserter;
        parent::__construct($paymentData, $string, $scopeConfig, $filesystem, $pdfConfig, $pdfTotalFactory,
            $pdfItemsFactory, $localeDate, $inlineTranslation, $addressRenderer, $storeManager, $localeResolver, $data);
    }


    /**
     * Return PDF document
     *
     * @param \Magento\Sales\Model\Order\Shipment[] $shipments
     * @return \Zend_Pdf
     */
    public function getPdf($shipments = [])
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        foreach ($shipments as $shipment) {
            if ($shipment->getStoreId()) {
                $this->_localeResolver->emulate($shipment->getStoreId());
                $this->_storeManager->setCurrentStore($shipment->getStoreId());
            }
            $page = $this->newPage();
            $order = $shipment->getOrder();
            /* Add image */
            $this->insertLogo($page, $shipment->getStore());
            /* Add address */
            $this->insertAddress($page, $shipment->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $shipment,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_SHIPMENT_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );
            /* Add document text and number */
            $this->insertDocumentNumber($page, __('Packing Slip # ') . $shipment->getIncrementId());

            // CUSTOM CODE: insert order comment
            $page = $this->orderCommentInserter->insertCommentPackingSlipPdf(
                $this,
                $page,
                $order->getBoldOrderComment(),
                $order->getStoreId()
            );

            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($shipment->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
        }
        $this->_afterGetPdf();
        if ($shipment->getStoreId()) {
            $this->_localeResolver->revert();
        }
        return $pdf;
    }

    public function setFontRegular($object, $size = 7)
    {
        return $this->_setFontRegular($object, $size);
    }
}
