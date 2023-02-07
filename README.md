# Mage2 Module OrviSoft MagentoDoofinder

    `orvisoft/module-magentodoofinder`

 - [Main Functionalities](#mmain-functionalities)
 - [Installation](#installation)
 - [Configuration](#configuration)
 - [Specifications](#specifications)

## Main Functionalities
Magento 2 doofinder module that actually works on search result page.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/OrviSoft`
 - Enable the module by running `php bin/magento module:enable OrviSoft_MagentoDoofinder`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require orvisoft/module-magentodoofinder`
 - enable the module by running `php bin/magento module:enable OrviSoft_MagentoDoofinder`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Status (doofinder/general/status)

 - Zone (doofinder/general/zone)

 - Token (doofinder/general/token)

 - Hash ID (doofinder/general/hashid)

 - Results Per Page (doofinder/search_settings/rpp)

 - Minimum Search Character limit (doofinder/search_settings/min_length)

 - Include Filters (doofinder/search_settings/include_filters)

 - Exclude filters (doofinder/search_settings/exclude_attributes_list)


## Specifications

 - Block
	- Catalog\Search\Results > catalog/search/results.phtml

 - Cache
	- MagentoDoofinder - doofinder_cache_tag > OrviSoft\MagentoDoofinder\Model\Cache\MagentoDoofinder

 - Cronjob
	- orvisoft_magentodoofinder_generatefeed

 - Helper
	- OrviSoft\MagentoDoofinder\Helper\Data
	