<p align="center">
    <a href="https://www.webgriffe.com" target="_blank">
        <img src="https://sylius.com/wp-content/uploads/2018/08/webgriffe_logo.png" height="120" />
    </a>
</p>

<h1 align="center">Table Rate Shipping Plugin</h1>

<p align="center"><a href="https://sylius.com/plugins/" target="_blank"><img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="200"></a></p>
<p align="center">This plugin allows to define shipping rates using weight tables.</p>
<p align="center"><a href="https://github.com/webgriffe/WebgriffeSyliusTableRateShippingPlugin/actions"><img src="https://github.com/webgriffe/WebgriffeSyliusTableRateShippingPlugin/workflows/Build/badge.svg" alt="Build Status" /></a></p>

## Installation

1. Run `composer require --no-scripts webgriffe/sylius-table-rate-shipping-plugin`: it's normal that the "cache:clear" command, that is executed automatically at the end, fails because you have to do the next steps.

2. If they have not been added automatically, you have to add these bundles to `config/bundles.php` file:

   ```php
   Webgriffe\SyliusTableRateShippingPlugin\WebgriffeSyliusTableRateShippingPlugin::class => ['all' => true],
   ```

3. Add the plugin's configs by creating the file `config/packages/webgriffe_sylius_table_rate_shipping_plugin.yaml` with the following content:

   ```yaml
   imports:
       - { resource: "@WebgriffeSyliusTableRateShippingPlugin/config/config.yaml" }
   ```

4. Add the plugin's routes by creating the file `config/routes/webgriffe_sylius_table_rate_shipping_plugin.yaml` with the following content:

   ```yaml   
   webgriffe_sylius_table_rate_shipping_plugin_admin:
     resource: "@WebgriffeSyliusTableRateShippingPlugin/config/routes/admin.yaml"
     prefix: /%sylius_admin.path_name%
   
   ```

5. Finish the installation by updating the database schema:

   ```bash
   bin/console cache:clear
   bin/console doctrine:migrations:migrate
   ```

## Contributing

To contribute you need to:

1. Clone this repository into you development environment and go to the plugin's root directory,

2. Then, from the plugin's root directory, run the following commands:

   ```bash
   composer install
   ```

3. Copy `tests/TestApplication/.env` in `tests/TestApplication/.env.local` and set configuration specific for your development environment.

4. Link node_modules:

    ```bash
    ln -s vendor/sylius/test-application/node_modules node_modules
    ```

5. Run docker (create a `compose.override.yml` if you need to customize services):

    ```bash
    docker-compose up -d
    ```

6. Then, from the plugin's root directory, run the following commands:

    ```bash
    composer test-app-init
    ```

7. Run your local server:

      ```bash
      symfony server:ca:install
      symfony server:start -d
      ```

8. Now at http://localhost:8080/ you have a full Sylius testing application which runs the plugin

### Static checks

  - Coding Standard
    ```bash
    vendor/bin/ecs check --fix
    ```

  - Psalm
  
    ```bash
    vendor/bin/psalm
    ```

  - PHPStan

    ```bash
    vendor/bin/phpstan analyse
    ```

### Testing

After your changes you must ensure that the tests are still passing.

First setup your test database:

    ```bash
    APP_ENV=test vendor/bin/console doctrine:database:create
    APP_ENV=test vendor/bin/console doctrine:migrations:migrate -n
    # Optionally load data fixtures
    APP_ENV=test vendor/bin/console sylius:fixtures:load -n
    ```

And build assets:

```bash
    (cd vendor/sylius/test-application && yarn install)
    (cd vendor/sylius/test-application && yarn build)
    vendor/bin/console assets:install
```

The current CI suite runs the following tests:

  - PHPUnit

  ```bash
  vendor/bin/phpunit
  ```

  - PHPSpec

  ```bash
  vendor/bin/phpspec run
  ```

  - Behat (non-JS scenarios)

  ```bash
    vendor/bin/behat --strict --tags="~@javascript"
  ```

  - Behat (JS scenarios)

    1. [Install Symfony CLI command](https://symfony.com/download).

    2. Start Headless Chrome:

      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```

    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:

      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/TestApplication/public --daemon
      ```

    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript"
      ```

## License

This library is under the MIT license. See the complete license in the LICENSE file.

## Credits

Developed by [Webgriffe®](http://www.webgriffe.com/).
