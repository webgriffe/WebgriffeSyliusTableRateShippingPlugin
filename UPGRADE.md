# Upgrade plugin guide

## Upgrade from version v1.x to v2.x

The v2 is now compatible with Sylius 2.x, so you need to update your Sylius version to 2.x before upgrading the plugin. Some changes not listed here may be required, so please refer to the Sylius 2.x upgrade guide for more details.

- The route `@WebgriffeSyliusTableRateShippingPlugin/Resources/config/config.yml` has been renamed to `@WebgriffeSyliusTableRateShippingPlugin/config/config.yaml`.
- The route `@WebgriffeSyliusTableRateShippingPlugin/Resources/config/admin_routing.yml` has been renamed to `@WebgriffeSyliusTableRateShippingPlugin/config/routes/admin.yaml`.
- The route `webgriffe_sylius_table_rate_shipping_plugin_shop` has been removed as it was unnecessary
- The migrations are now stored inside the plugin in `src/Migrations`. Your application will probably detect these but you probably already have your own migration with the same changes. So
  - For [Version20250923083844.php](src/Migrations/Version20250923083844.php) you should add this to your migration table by running the following command:
  
    ```bash
    bin/console doctrine:query:sql "INSERT INTO sylius_migrations VALUES ('Webgriffe\\\SyliusTableRateShippingPlugin\\\Migrations\\\Version20250923083844', '2026-02-18 11:30:00', 1)"
    ```
    
    You must remove the same changes made by this migration from your own migration if you want, but it is not required as the changes are the same and will not cause any issues if executed twice.
  - For [Version20260218113714.php](src/Migrations/Version20260218113714.php) you should do nothing as it must be detected by doctrine migrations and executed when running the migrate command.
