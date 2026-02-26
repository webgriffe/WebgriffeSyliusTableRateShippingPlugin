# Upgrade plugin guide

## Upgrade from version v1.x to v2.x

The v2 is now compatible with Sylius 2.x, so you need to update your Sylius version to 2.x before upgrading the plugin. Some changes not listed here may be required, so please refer to the Sylius 2.x upgrade guide for more details.

- The route `@WebgriffeSyliusTableRateShippingPlugin/Resources/config/config.yml` has been renamed to `@WebgriffeSyliusTableRateShippingPlugin/config/config.yaml`.
- The route `@WebgriffeSyliusTableRateShippingPlugin/Resources/config/admin_routing.yml` has been renamed to `@WebgriffeSyliusTableRateShippingPlugin/config/routes/admin.yaml`.
- The route `webgriffe_sylius_table_rate_shipping_plugin_shop` has been removed as it was unnecessary
- The migrations are now stored inside the plugin in `src/Migrations`. These should be idempotent, so if the changes made by these migrations are already present, they will do nothing.
