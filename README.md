<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Table Rate Shipping Plugin</h1>

<p align="center"><img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="200"></p>
<p align="center">This plugin allows to define shipping rates using weight tables.</p>

## Installation

1. Run `composer require webgriffe/sylius-table-rate-shipping-plugin`.

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
       _locale: ^[a-z]{2}(?:_[A-Z]{2})?$
   
   webgriffe_sylius_table_rate_shipping_plugin_admina:
     resource: "@WebgriffeSyliusTableRateShippingPlugin/Resources/config/admin_routing.yml"
     prefix: /admin
   
   ```

5. Finish the installation by updating the database schema and installing assets:

   ```bash
   bin/console doctrine:migrations:diff
   bin/console doctrine:migrations:migrate
   bin/console assets:install
   bin/console sylius:theme:assets:install
   ```

## Contributing

To contribute you need to:

1. Clone this repository into you development environment

2. Copy the `.env.test.dist` file inside the test application directory to the `.env` file:

   ```bash
   cp tests/Application/.env.test.dist tests/Application/.env
   ```

3. Edit the `tests/Application/.env` file by setting configuration specific for your development environment. For example, if you want to use SQLite as database driver during testing you can set the `DATABASE_URL` environment variable as follows:

   ```bash
   DATABASE_URL=sqlite:///%kernel.project_dir%/var/%kernel.environment%_db.sql
   ```

4. Then, from the plugin's root directory, run the following commands:

   ```bash
   (cd tests/Application && yarn install)
   (cd tests/Application && yarn build)
   (cd tests/Application && bin/console assets:install public)
   (cd tests/Application && bin/console doctrine:database:create)
   (cd tests/Application && bin/console doctrine:schema:create)
   (cd tests/Application && bin/console server:run localhost:8080 -d public)
   ```

5. Now at http://localhost:8080/ you have a full Sylius testing application which runs the plugin

### Testing

After your changes you must ensure that the tests are still passing. The current CI suite runs the following tests:

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

* Behat

  ```bash
  vendor/bin/behat --strict -vvv --no-interaction || vendor/bin/behat --strict -vvv --no-interaction --rerun
  ```

To run them all with a single command run:

```bash
composer suite
```

To run Behat's JS scenarios you need to setup Selenium and Chromedriver. Do the following:

1. Download [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/)

2. Download [Selenium Standalone Server](https://www.seleniumhq.org/download/)

3. Run Selenium with Chromedriver

   ```bash
   java -Dwebdriver.chrome.driver=chromedriver -jar selenium-server-standalone.jar
   ```

4. Remember that the test application webserver must be up and running as described above:

   ```bash
   cd tests/Application && bin/console server:run localhost:8080 -d public
   ```

License
-------
This library is under the MIT license. See the complete license in the LICENSE file.

Credits
-------
Developed by [Webgriffe®](http://www.webgriffe.com/).