# Magento 2 - Backend search by Metagento

When your website is big and you have many modules installed, sometimes you may want to find a menu item or a setting but you don’t remember where it is.
It will take a lot of time because each module has many settings.

Magento provides a search box in backend, however it only gives results for Product, Customer, Order. It doesn’t search in Menu and Configuration, our module can help you with this.

Features:

- Search menu items in backend

- Search setting fields in Configuration ( with link and path )

Installation via Composer:

composer require metagento/backend-search-magento2:*

php bin/magento setup:upgrade

php bin/magento setup:static-content:deploy

Any question, please visit https://www.metagento.com/magento-2-backend-search.html
