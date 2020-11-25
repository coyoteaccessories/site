Description
===========
Magento2 Sample Data Installer implementation.

Install
=======

1. Add repo to composer.json file:
```json
{
    "repositories": [
            {
                "type": "composer",
                "url": "https://repo.magento.com/"
            },
            {
                "type": "vcs",
                "url": "http://products.git.devoffice.com/magento/magento2-sample-data-installer.git"
            }
        ]
}
```

2. Add the module to composer:
```bash
composer require zemez/magento2-sample-data-installer:dev-master
```

3. Enable the module:
```bash
bin/magento module:enable --clear-static-content Zemez_SampleDataInstaller
bin/magento setup:upgrade
```

Configure
=========

Please navigate to the **Stores -> Settings -> Configuration -> Zemez -> Sample Data Installer** in order to configure the module.

Uninstall
=========

1. Disable the module:
```bash
bin/magento module:disable --clear-static-content Zemez_SampleDataInstaller
```

2. Remove the module from composer:
```bash
composer remove zemez/magento2-sample-data-installer
```