<?php

namespace eZ\Publish\Core\MVC\Symfony\Security\Authentication;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\User\User;
use eZ\Publish\API\Repository\Values\User\User as APIUser;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\Repository\User\PasswordHashServiceInterface;

class PasswordHashMigrationService
{
    const PASSWORD_HASH_MD5_PASSWORD = 1;
    const PASSWORD_HASH_MD5_USER = 2;
    const PASSWORD_HASH_MD5_SITE = 3;
    const PASSWORD_HASH_PLAINTEXT = 5;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var PasswordHashServiceInterface
     */
    private $passwordHashService;

    /**
     * PasswordHashMigrationService constructor.
     * @param Repository $repository
     * @param PasswordHashServiceInterface $passwordHashService
     */
    public function __construct(Repository $repository, PasswordHashServiceInterface $passwordHashService)
    {
        $this->repository = $repository;
        $this->passwordHashService = $passwordHashService;
    }

    public  function isMigrationRequired(int $hashType): bool
    {
        return !in_array(
            $hashType,
            [
                User::PASSWORD_HASH_BCRYPT,
                User::PASSWORD_HASH_PHP_DEFAULT
            ]
        );
    }

    public function verifyPassword(User $user, $password, $site, $hastType): bool {
        $hash = $this->generatePasswordHash($user, $hastType, $password, $site);

        return $user->passwordHash === $hash;
    }


    /**
     * Update password hash to the type configured for the service, if they differ.
     *
     * @param string $login User login
     * @param string $plainPassword User password
     * @param \eZ\Publish\SPI\Persistence\User $spiUser
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\BadStateException if the password is not correctly saved, in which case the update is reverted
     */
    public function updatePasswordHash(User $user, string $plainPassword)
    {
        $hashType = $this->passwordHashService->getDefaultHashType();
        $userService = $this->repository->getUserService();

        $updateStruct = $userService->newUserUpdateStruct();
        $updateStruct->password = $plainPassword;
        $updateStruct->hashType = $hashType;

        $this->repository->beginTransaction();

        try {
            $this->repository->sudo(function () use ($userService, $user, $updateStruct) {
                $userService->updateUser($user, $updateStruct);
            });

            $this->repository->commit();

        } catch (\Exception $e) {
            $this->repository->rollback();

            throw $e;
        }
    }

    /**
     * @param APIUser $user
     * @param $hastType
     * @param $password
     * @param $site
     * @return string
     * @throws InvalidArgumentException
     */
    private function generatePasswordHash(APIUser $user, $hastType, $password, $site): string
    {
        $login = $user->login;

        switch ($hastType) {
            case self::PASSWORD_HASH_MD5_PASSWORD:

                return md5($password);

            case self::PASSWORD_HASH_MD5_USER:

                return md5("$login\n$password");

            case self::PASSWORD_HASH_MD5_SITE:

                return md5("$login\n$password\n$site");

            case self::PASSWORD_HASH_PLAINTEXT:

                return $password;

            default:
                throw new InvalidArgumentException('type', "Password hash type '$hastType' is not recognized");
        }
    }
}
