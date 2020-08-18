<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\PlatformInstallerBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckUnsupportedPasswordHashTypesCommand extends Command
{
    private const PASSWORD_HASH_MD5_PASSWORD = 1;
    private const PASSWORD_HASH_MD5_USER = 2;
    private const PASSWORD_HASH_MD5_SITE = 3;
    private const PASSWORD_HASH_PLAINTEXT = 5;

    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('ezplatform:check-unsupported-password-hash-types');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $unsupportedHashesCounter = $this->countUnsupportedHashTypes();

        if ($unsupportedHashesCounter > 0) {
            $output->writeln(sprintf('<error>Found %s users with unsupported password hash types</error>', $unsupportedHashesCounter));
            $output->writeln('<info>For more details check documentation:</info> <href=https://doc.ezplatform.com/en/latest/releases/ez_platform_v3.0_deprecations/#password-hashes>https://doc.ezplatform.com/en/latest/releases/ez_platform_v3.0_deprecations/#password-hashes</>');
        } else {
            $output->writeln('OK - <info>All users have supported password hash types</info>');
        }

        return 0;
    }

    private function countUnsupportedHashTypes(): int
    {
        $selectQuery = $this->connection->createQueryBuilder();

        $selectQuery
            ->select('count(u.login)')
            ->from('ezuser', 'u')
            ->andWhere(
                $selectQuery->expr()->in('u.password_hash_type', [
                    self::PASSWORD_HASH_MD5_PASSWORD,
                    self::PASSWORD_HASH_MD5_USER,
                    self::PASSWORD_HASH_MD5_SITE,
                    self::PASSWORD_HASH_PLAINTEXT,
                ])
            );

        return $selectQuery
            ->execute()
            ->fetchColumn();
    }
}
