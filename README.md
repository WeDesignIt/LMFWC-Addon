# License Manager for WooCommerce – Endpoint Addon

This plugin extends the [License Manager for WooCommerce][link-lmfwc] by adding additional REST API endpoints.

## Requirements

- WordPress 6.0+
- WooCommerce
- License Manager for WooCommerce 2.0+
- PHP 8.0+

## Installation

1. Download or clone this repository into your WordPress plugins directory:  
   `wp-content/plugins/lmfwc-addon`  
2. Activate **License Manager for WooCommerce – Endpoint Addon** in your WordPress admin.  
3. Ensure that **License Manager for WooCommerce** is active.

## Usage

After activation, new REST API endpoints will be available for managing licenses.  

The endpoints use the same authentication method as the core License Manager for WooCommerce plugin.  

Check the [documentation](docs/endpoints.md) for details.

## Changelog

### 1.0.2
- Pagination correction (was returning the wrong offset value)

### 1.0.1
- Bug fixes

### 1.0.0
- Initial release.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## About WeDesignIt

[WeDesignIt][link-wdi] is a web agency from Oude-Tonge (reserve-Zeeland), the Netherlands specialized in custom web applications, API development, and integrations.

[link-lmfwc]: https://licensemanager.at
[link-wdi]: https://www.wedesignit.nl
