<?php

namespace Honeybee\FrameworkBinding\Silex\Provisioner;

use Auryn\Injector;
use Honeybee\Common\Error\ConfigError;
use Honeybee\Infrastructure\Config\SettingsInterface;
use Honeybee\ServiceDefinitionInterface;
use Symfony\Component\Security\Core\User\User;

class EnvironmentProvisioner implements ProvisionerInterface
{
    public function provision(
        Injector $injector,
        ServiceDefinitionInterface $serviceDefinition,
        SettingsInterface $provisionerSettings
    ) {
        $service = $serviceDefinition->getClass();
        $state = [
            ':config' => $serviceDefinition->getConfig(),
            ':user' => new User('hodor', 'srsly?', [ 'default' ], true, true, true, true)
        ];
        $injector->define($service, $state);
        // there will only be one instance of the service when the "share" setting is true
        if ($provisionerSettings->get('share', true) === true) {
            $injector->share($service);
        }

        if ($provisionerSettings->has('alias')) {
            $alias = $provisionerSettings->get('alias');
            if (!is_string($alias) && !class_exists($alias)) {
                throw new ConfigError('Alias given must be an existing class or interface name (fully qualified).');
            }
            $injector->alias($alias, $service);
        }

        return $injector;
    }
}
