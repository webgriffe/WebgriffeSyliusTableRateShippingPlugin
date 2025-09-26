<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Table Rate Shipping Plugin</h1>

<p align="center"><a href="https://sylius.com/plugins/" target="_blank"><img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="200"></a></p>
<p align="center">This plugin allows to define shipping rates using weight tables.</p>
<p align="center"><a href="https://github.com/webgriffe/WebgriffeSyliusTableRateShippingPlugin/actions"><img src="https://github.com/webgriffe/WebgriffeSyliusTableRateShippingPlugin/workflows/Build/badge.svg" alt="Build Status" /></a></p>

## Installation

1. Run `composer require --no-scripts webgriffe/sylius-table-rate-shipping-plugin`.

2. Add the plugin to the `config/bundles.php` file:

   ```php
   Webgriffe\SyliusTableRateShippingPlugin\WebgriffeSyliusTableRateShippingPlugin::class => ['all' => true],
   ```

3. Add the plugin's config to by creating the file `config/packages/webgriffe_sylius_table_rate_shipping_plugin.yaml` with the following content:

   ```yaml
   imports:
       - { resource: "@WebgriffeSyliusTableRateShippingPlugin/Resources/config/config.yml" }
   ```

4. Add the plugin's routing by creating the file `config/routes/webgriffe_sylius_table_rate_shipping_plugin.yaml` with the following content:

   ```yaml
   webgriffe_sylius_table_rate_shipping_plugin_shop:
     resource: "@WebgriffeSyliusTableRateShippingPlugin/Resources/config/shop_routing.yml"
     prefix: /{_locale}
     requirements:
       _locale: ^[A-Za-z]{2,4}(_([A-Za-z]{4}|[0-9]{3}))?(_([A-Za-z]{2}|[0-9]{3}))?$
   
   webgriffe_sylius_table_rate_shipping_plugin_admin:
     resource: "@WebgriffeSyliusTableRateShippingPlugin/Resources/config/admin_routing.yml"
     prefix: /%sylius_admin.path_name%
   
   ```

5. Finish the installation by updating the database schema and installing assets:

   ```bash
   bin/console cache:clear
   bin/console doctrine:migrations:diff
   bin/console doctrine:migrations:migrate
   bin/console assets:install
   bin/console sylius:theme:assets:install
   ```

## Contributing

To contribute you need to:

1. Clone this repository into you development environment and go to the plugin's root directory,

2. Then, from the plugin's root directory, run the following commands:

   ```bash
   composer install
   ```

3. Copy `tests/TestApplication/.env` in `tests/TestApplication/.env.local` and set configuration specific for your development environment.

4. Then, from the plugin's root directory, run the following commands:

    ```bash
    (cd vendor/sylius/test-application && yarn install)
    (cd vendor/sylius/test-application && yarn build)
    vendor/bin/console assets:install
   
    vendor/bin/console doctrine:database:create
    vendor/bin/console doctrine:migrations:migrate -n
    # Optionally load data fixtures
    vendor/bin/console sylius:fixtures:load -n
    ```

5. Run your local server:

      ```bash
      symfony server:ca:install
      symfony server:start -d
      ```

6. Now at http://localhost:8080/ you have a full Sylius testing application which runs the plugin

### Testing

After your changes you must ensure that the tests are still passing.

First setup your test database:

    ```bash
    APP_ENV=test vendor/bin/console doctrine:database:create
    APP_ENV=test vendor/bin/console doctrine:migrations:migrate -n
    # Optionally load data fixtures
    APP_ENV=test vendor/bin/console sylius:fixtures:load -n
    ```

This plugin's test application already comes with a test configuration that uses SQLite as test database.
If you don't want this you can create a `tests/TestApplication/.env.test.local` with a different `DATABASE_URL`.

The current CI suite runs the following tests:

* Easy Coding Standard

  ```bash
  vendor/bin/ecs check src/ tests/Behat/
  ```

* PHPStan

  ```bash
  vendor/bin/phpstan analyse -c phpstan.neon -l max src/
  ```

* PHPUnit

  ```bash
  vendor/bin/phpunit
  ```

* PHPSpec

  ```bash
  vendor/bin/phpspec run
  ```

* Behat (without Javascript)

  ```bash
  vendor/bin/behat --tags="~@javascript"
  ```

* Behat (only Javascript)

  ```bash
  vendor/bin/behat --tags="@javascript"
  ```

To run them all with a single command run:

```bash
composer suite
```

To run Behat's Javascript scenarios you need to setup Selenium and Chromedriver. Do the following:

1. Start Headless Chrome:

      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```

2. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:

      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/TestApplication/public --daemon
      ```

License
-------
This library is under the MIT license. See the complete license in the LICENSE file.

Credits
-------
Developed by [Webgriffe®](http://www.webgriffe.com/).
