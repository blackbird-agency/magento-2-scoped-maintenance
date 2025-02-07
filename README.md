# Blackbird Scoped Maintenance

[![Latest Stable Version](https://img.shields.io/packagist/v/blackbird/scoped-maintenance.svg?style=flat-square)](https://packagist.org/packages/blackbird/scoped-maintenance)
[![License: MIT](https://img.shields.io/github/license/blackbird-agency/magento-2-scoped-maintenance.svg?style=flat-square)](./LICENSE.txt)

This module allows you to enable maintenance mode for specific stores in a Magento instance while leaving others accessible. It provides fine-grained control over store access during maintenance mode and optionally allows specific IP addresses to bypass maintenance restrictions.

Key functionality includes:
- Activating maintenance mode for specific stores (scoped maintenance).
- Specifying IP addresses to allow access during maintenance mode.
- Automatically managing cache to reflect store maintenance state.

The source code is available at the [GitHub repository](https://github.com/blackbird-agency/magento-2-scoped-maintenance).

![Illustration](illustration.png)

---

## Setup

### Get the Package

#### **Zip Package:**

Unzip the package into `app/code/Blackbird/ScopedMaintenance`, from the root of your Magento instance.

#### **Composer Package:**

```shell
    composer require blackbird/scoped-maintenance
```

### Install the Module

Go to your Magento root directory, then run the following Magento command:

**If you are in production mode, do not forget to recompile and redeploy the static resources, or to use the `--keep-generated` option.**

```shell
    bin/magento setup:upgrade
```

### Features

#### Scoped Maintenance

The Scoped Maintenance module introduces the ability to enable maintenance mode for specific Magento stores, instead of forcing it globally across all stores. You can target individual stores or groups of store IDs to restrict access while ensuring other stores remain fully operational.
This module add a .maintenance.store file to store the list of stores in maintenance.

#### IP Whitelisting

While in maintenance mode, you can specify a list of IP addresses that will still have access to the stores under maintenance, bypassing the restrictions. This feature is useful for developers or a specific set of users who need to test or access the stores during downtime.
The native ip whitelist of the maintenance is kept.

#### Automatic Cache Purging

When maintenance mode is activated or deactivated for specific stores, the module handles purging the full-page cache for the affected stores to ensure consistency and immediate effect of the maintenance status.
A special cache tag is added to all pages to specify the store_id and clean only the full page cache of specified stores.
---

## Usage

### Enabling Maintenance for Specific Stores

To enable maintenance mode for specific stores, use the built-in Magento CLI command provided by this module:


- **`<store_ids>`**: A comma-separated list of store IDs for which maintenance mode should be enabled.
- **`--ip`**: (Optional) A comma-separated list of IP addresses that are allowed access to the stores during maintenance mode.

**Example:**

Enable maintenance mode for stores with IDs `1` and `2`, while allowing access to IPs `192.168.1.1` and `192.168.1.2`:

```shell
    bin/magento maintenance:enable-store 1,2 --ip=192.168.1.1,192.168.1.2
```


### Disabling Maintenance Mode

To disable maintenance mode for all stores, use Magento's standard maintenance disable command:

```shell
    bin/magento maintenance:disable
```

### Checking Store Maintenance Mode Status

The module includes the ability to check whether maintenance mode is enabled for a specific store programmatically. You can use the `\Blackbird\ScopedMaintenance\Service\Maintenance` service for this purpose.

```shell
    bin/magento maintenance:status
```

## Developer Notes

### Cache Behavior

When enabling or disabling maintenance mode for specific stores, the module will automatically purge cache tags related to the affected stores using Magento's full-page cache system. This ensures that the maintenance status applies immediately even if cached pages exist.

---

## Support

- If you have any issue with this code, feel free to [open an issue](https://github.com/blackbird-agency/magento-2-scoped-maintenance/issues/new).
- If you want to contribute to this project, feel free to [create a pull request](https://github.com/blackbird-agency/magento-2-scoped-maintenance/compare).

---

## Contact

For further information, contact us:

- by email: hello@bird.eu
- or by form: [https://black.bird.eu/en/contacts/](https://black.bird.eu/contacts/)

---

## Authors

- **Blackbird Team** - *Contributor* - [They're awesome!](https://github.com/blackbird-agency)

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.txt) file for details.

---

***That's all folks!***
