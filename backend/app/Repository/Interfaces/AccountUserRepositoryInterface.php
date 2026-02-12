<?php

declare(strict_types=1);

namespace Ciencia\Repository\Interfaces;

use Ciencia\DomainObjects\AccountUserDomainObject;
use Ciencia\Repository\Eloquent\BaseRepository;

/**
 * @extends BaseRepository<AccountUserDomainObject>
 */
interface AccountUserRepositoryInterface extends RepositoryInterface
{
}
