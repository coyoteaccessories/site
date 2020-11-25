[coyoteaccessories.com](https://coyoteaccessories.com) (Magento 2).

## How to deploy the static content
```shell
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f
bin/magento cache:clean
```