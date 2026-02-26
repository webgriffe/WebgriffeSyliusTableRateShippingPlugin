<?php

declare(strict_types=1);

namespace Webgriffe\SyliusTableRateShippingPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @psalm-api
 */
final class Version20260218113714 extends AbstractMigration
{
    #[\Override]
    public function up(Schema $schema): void
    {
        if ($schema->getTable('webgriffe_sylius_shipping_table_rate')->hasIndex('UNIQ_1D5F4E4177153098')) {
            return;
        }
        $this->addSql('ALTER TABLE webgriffe_sylius_shipping_table_rate RENAME INDEX code_idx TO UNIQ_1D5F4E4177153098');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE webgriffe_sylius_shipping_table_rate RENAME INDEX uniq_1d5f4e4177153098 TO code_idx');
    }
}
