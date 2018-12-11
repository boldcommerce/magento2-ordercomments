<?php

declare(strict_types=1);

/**
 * OrderComment
 *
 * @copyright Copyright © 2018 Bold Commerce BV. All rights reserved.
 * @author    dev@boldcommerce.nl
 */

namespace Bold\OrderComment\Model\Pdf;

use Bold\OrderComment\Model\Pdf\Shipment as ShipmentPdf;
use Bold\OrderComment\Model\Pdf\Invoice as InvoicePdf;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class OrderComment
{
    /**
     * @var StringUtils
     */
    protected $string;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var InvoicePdf|ShipmentPdf $documentObject
     */
    protected $documentObject;

    public function __construct(StringUtils $string, ScopeConfigInterface $scopeConfig) {
        $this->string = $string;
        $this->scopeConfig = $scopeConfig;
    }

    public function insertCommentInvoicePdf(InvoicePdf $pdf, \Zend_Pdf_Page $page, $comment, $storeId)
    {
        if (!$this->isEnabled('invoice', $storeId)) {
            return $page;
        }
        $this->documentObject = $pdf;
        return $this->insertComment($page, $comment);
    }

    public function insertCommentPackingSlipPdf(ShipmentPdf $pdf, \Zend_Pdf_Page $page, $comment, $storeId)
    {
        if (!$this->isEnabled('shipment', $storeId)) {
            return $page;
        }
        $this->documentObject = $pdf;
        return $this->insertComment($page, $comment);
    }

    protected function insertComment(\Zend_Pdf_Page $page, $comment)
    {
        if (!$comment) {
            return $page;
        }

        if ($this->documentObject->y < 50) {
            $page = $this->documentObject->newPage();
        }

        $this->drawCommentHeader($page);
        $comment = $this->formatComment($comment);
        $page = $this->drawCommentBody($page, $comment);

        return $page;
    }

    protected function drawCommentHeader(\Zend_Pdf_Page $page)
    {
        $this->documentObject->setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->documentObject->y, 570, $this->documentObject->y - 15);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));
        $page->drawText(__('Order Comment'), 35, $this->documentObject->y -10);
        $this->documentObject->y -= 30;
        return $page;
    }

    protected function drawCommentBody(\Zend_Pdf_Page $page, $commentLines)
    {
        $this->prepareCommentBodyBlock($page, count($commentLines));

        foreach($commentLines as $index => $line) {
            $page->drawText($line, 35, $this->documentObject->y);
            $this->documentObject->y -= 10;

            if ($this->documentObject->y <= 15) {
                $page = $this->documentObject->newPage();
                $remainingLines = count($commentLines) - ($index + 1);
                $this->drawCommentHeader($page);
                $this->prepareCommentBodyBlock($page, $remainingLines);
            }
        }
        return $page;
    }

    protected function prepareCommentBodyBlock(\Zend_Pdf_Page $page, $numLines)
    {
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
        $page->drawRectangle(
            25,
            $this->documentObject->y + 15,
            570,
            max($this->documentObject->y - (10 * $numLines) + 10, 15)
        );

        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));
    }

    protected function formatComment($text)
    {
        $result = [];
        foreach (explode("\n", $text) as $str) {
            foreach ($this->string->split($str, 130, true, true) as $part) {
                $result[] = $part;
            }
            $result[] = '';
        }
        return $result;
    }

    private function isEnabled($type, $store)
    {
        return $this->scopeConfig->isSetFlag(
            'sales_pdf/'.$type.'/bold_put_comment',
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
