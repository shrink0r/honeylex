<?php

namespace Foh\SystemAccount\User\Model\Task\ModifyUser;

use Foh\SystemAccount\User\Model\Aggregate\UserType;
use Honeybee\Infrastructure\Event\Bus\EventBusInterface;
use Honeybee\Model\Task\ModifyAggregateRoot\ModifyAggregateRootCommandHandler;
use Honeybee\Infrastructure\DataAccess\DataAccessServiceInterface;
use Psr\Log\LoggerInterface;

class ModifyUserCommandHandler extends ModifyAggregateRootCommandHandler
{
    public function __construct(
        UserType $user_type,
        DataAccessServiceInterface $data_access_service,
        EventBusInterface $event_bus,
        LoggerInterface $logger
    ) {
        parent::__construct($user_type, $data_access_service, $event_bus, $logger);
    }
}