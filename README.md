# Bold Commerce: Magento 2 Order Comments

## Description
This extension allows customers to place a comment during the checkout.
The comment field is displayed in the billing step right above the place order button.

Additionally, there is also the option of showing the comment field on the cart page.

Store owners can then see these comments in the backend on the order grid and on the order view page.

### Checkout view
![comment box closed](docs/checkout_comment_closed.png)


![comment box opened](docs/checkout_comment_opened.png)

### Admin panel
![admin panel](docs/admin_panel.png)

## Emails

Add the "order comment" to new order emails by referencing [the code here](https://github.com/boldcommerce/magento2-ordercomments/issues/6#issuecomment-328515806).

## Configuration

There are several [configuration options](https://github.com/boldcommerce/magento2-ordercomments/blob/master/etc/adminhtml/system.xml) for this extension, which can be found at **STORES > Configuration > SALES > Sales > Order Comments**.

## Installation
```
composer require boldcommerce/magento2-ordercomments
php bin/magento module:enable Bold_OrderComment
php bin/magento setup:upgrade
```

## Changelog
1.8.2
=============
* Third party contribution: Bengali translations [#65](https://github.com/boldcommerce/magento2-ordercomments/pull/65)

1.8.1
=============
* fix bug introduced with 1.8.0 in checkout `Cannot read property 'length' of null`

1.8.0
=============
* new feature: ability to show the comment field on the cart page based on a admin configuration setting.

1.7.1
=============
* upgrade tests to phpunit 6

1.7.0
=============
* Added website scope configuration setting to toggle visibility of comment field. [#59](https://github.com/boldcommerce/magento2-ordercomments/pull/59)

1.6.5
=============
* Third party contribution: PHP 7.4 support added in composer [#55](https://github.com/boldcommerce/magento2-ordercomments/pull/55)
* Third party contribution: Japanese translations added [#52](https://github.com/boldcommerce/magento2-ordercomments/pull/52)
* Third party contribution: Fix typo in Italian translation [#51](https://github.com/boldcommerce/magento2-ordercomments/pull/51)
* Third party contribution: Hungarian Translations added [#50](https://github.com/boldcommerce/magento2-ordercomments/pull/50)
* Third party contribution: New sections added in readme [#48](https://github.com/boldcommerce/magento2-ordercomments/pull/48)

1.6.4
=============
* Third party contribution: php 7.3 support in composer [#45](https://github.com/boldcommerce/magento2-ordercomments/pull/45)
* Third party contribution: French translations [#43](https://github.com/boldcommerce/magento2-ordercomments/pull/43)
* Third party contribution: Polish translations [#40](https://github.com/boldcommerce/magento2-ordercomments/pull/40)
* Third party contribution: Czech translations [#39](https://github.com/boldcommerce/magento2-ordercomments/pull/39)

1.6.3
=============
* Third party contribution: move form selector in order-comment-validator.js to a separate method to improve extensibility through mixins [#36](https://github.com/boldcommerce/magento2-ordercomments/pull/36)

1.6.2
=============
* Third party contribution: fix duplicate comment field on admin sales invoice view [#31](https://github.com/boldcommerce/magento2-ordercomments/pull/31)
* Third party contribution: fix typo and added some code improvements to the install script [#30](https://github.com/boldcommerce/magento2-ordercomments/pull/30)

1.6.1
=============
* Third party contribution: Enabled PHP 7.2 support [#29](https://github.com/boldcommerce/magento2-ordercomments/pull/29)

1.6.0
=============
* Third party contribution: Hebrew translations [#28](https://github.com/boldcommerce/magento2-ordercomments/pull/28)

1.5.0
=============
* Third party contribution: Form selector fallback for compatability with external changes that move the comment field [#24](https://github.com/boldcommerce/magento2-ordercomments/pull/24)

1.4.1
=============
* Third party contribution: Fixed it_IT translation csv [#20](https://github.com/boldcommerce/magento2-ordercomments/pull/20)

1.4.0
=============
* Third party contribution: Made the comment available in the order list web api `V1/orders` [#18](https://github.com/boldcommerce/magento2-ordercomments/pull/18)

1.3.0
=============
* UX changes to the max comment length feature [#15](https://github.com/boldcommerce/magento2-ordercomments/issue/15)
* Made the comment available in the order detail web api `V1/orders/{id}` [#15](https://github.com/boldcommerce/magento2-ordercomments/issue/15)

1.2.0
=============
* added setting to change initial collapse state of comment field (closed/opened/no collapse) [#14](https://github.com/boldcommerce/magento2-ordercomments/issue/14)

1.1.4
=============
* updated composer.json to allow PHP 7.1

1.1.3
=============
* Third party contribution: Dutch translations [#10](https://github.com/boldcommerce/magento2-ordercomments/pull/10)
* Third party contribution: Italian translations [#11](https://github.com/boldcommerce/magento2-ordercomments/pull/11)

1.1.2
=============
* Fix for fatal error on admin order view page when used with some other extensions [#9](https://github.com/boldcommerce/magento2-ordercomments/issues/9)

1.1.1
=============
* Third party contribution: Swedish translations and fixes in German translations [#5](https://github.com/boldcommerce/magento2-ordercomments/pull/5)

1.1.0
=============
* Third party contribution: German translations [#2](https://github.com/boldcommerce/magento2-ordercomments/pull/2)
* Third party contribution: Optional configuration for maximum comment length [#3](https://github.com/boldcommerce/magento2-ordercomments/pull/3)
* Third party contribution: Show order comments in customer account [#4](https://github.com/boldcommerce/magento2-ordercomments/pull/4)

1.0.0
=============
initial version

## Technical
To take in account third party payment extensions using custom implementations of Magento_Checkout/js/action/place-order.js to submit the order, this extension sends
the order comment in a separate request during the validation, before the order is placed. It should therefore work out of
the box.




## Uninstall
If you installed this module through composer, then you can run `php bin/magento module:uninstall Bold_OrderComment` to automatically
remove the code and drop the columns added by this extension.

*note:* the uninstall command seems bugged and might get stuck at `Removing code from Magento codebase:` (It worked fine for me on a 2.1.0 install but not on a 2.1.4 install). When this happens you should
exit with `ctrl+c` and run 
```
composer update
php bin/magento maintenance:disable
```
See [github issue 3544](https://github.com/magento/magento2/issues/3544)

Alternatively you can manually remove the extension and remove the column `bold_order_comment` from the tables
* quote
* sales_order
* sales_order_grid

## License
MIT
