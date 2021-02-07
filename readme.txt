Module Import from YML
Author: Gennady Telegin <support@itxd.ru>

The module imports data to the store from a YML file (http://help.yandex.ru/partnermarket/?id=1111425).

The following information is parsed:
1. Category tree - synchronizes with already existing categories in the store (by name and full path from the root).
2. Products (synchronized by SKU with those already existing in the store), including the following fields:
  a) Manufacturer - missing
  b) Attributes - missing ones are created
  c) Availability (in stock or pre-order)
3. Pictures (downloaded to the server).

Tasks that can be solved using the module:
1. Migration from one store to another. The module has a mode for completely deleting data before importing, which removes all categories, products, attributes and manufacturers from the store, and then makes an import.
2. Import of third-party catalogs to the site. If you import data every day, then only new products that have not been previously added to the store will be added to the store.

Installation:
0. VQMod must be installed (https://code.google.com/p/vqmod/). If you don't want to install it, you can manually add a link to the module - vqmod is only needed for this.
1. Copy the contents of the upload directory to the root of your site.
2. The module is available in the admin panel, System-> YML Import

If you have any difficulties or questions, please contact support@itxd.ru 
