### How to install:

1 First add repo to composer.json of magento.

"repositories": [
        {
            "type": "vcs",
            "url": "http://products.git.devoffice.com/magento/featured-product-last.git"
        }
    ],

2 Run command:

bin/magento cache:disable

composer require Zemez/featured-product:dev-default

3 Run command:

bin/magento module:enable --clear-static-content Zemez_FeaturedProduct

bin/magento setup:upgrade


### How to remove module:

1 Run command:

bin/magento module:disable --clear-static-content Zemez_FeaturedProduct

2 Run command:

composer remove Zemez/ajax-search



### How to configure module:

1 Go to Admin Panel:

2 After modification:

Clear magento cache.

Clear browser cache.