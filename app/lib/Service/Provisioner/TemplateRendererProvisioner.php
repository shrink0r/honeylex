<?php

namespace Honeybee\FrameworkBinding\Silex\Service\Provisioner;

use Auryn\Injector;
use Honeybee\FrameworkBinding\Silex\Config\ConfigProviderInterface;
use Honeybee\FrameworkBinding\Silex\Crate\CrateMap;
use Honeybee\Infrastructure\Config\SettingsInterface;
use Honeybee\Infrastructure\Template\TemplateRendererInterface;
use Honeybee\ServiceDefinitionInterface;
use Pimple\Container;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Filesystem\Filesystem;

class TemplateRendererProvisioner implements ProvisionerInterface
{
    public function provision(
        Container $app,
        Injector $injector,
        ConfigProviderInterface $configProvider,
        ServiceDefinitionInterface $serviceDefinition,
        SettingsInterface $provisionerSettings
    ) {
        $service = $serviceDefinition->getClass();

        $this->registerTwig($app, $configProvider);

        return $injector
            ->share($service)
            ->alias(TemplateRendererInterface::CLASS, $service)
            ->delegate(
                $service,
                function (Filesystem $filesystem) use ($service, $app) {
                    return new $service($app['twig'], $filesystem);
                }
            );
    }

    protected function registerTwig(Container $app, ConfigProviderInterface $configProvider)
    {
        $app->register(new TwigServiceProvider);

        $namespacedPaths = $this->getCrateTemplatesPaths($configProvider->getCrateMap());
        $projectTemplates = $configProvider->getProjectDir().'/app/templates';
        $namespacedPaths['Honeylex'] = $configProvider->getCoreDir().'/app/templates';
        $namespacedPaths['Project'] = $projectTemplates;

        $app['twig.loader.filesystem'] = function () use ($namespacedPaths, $projectTemplates) {
            $filesystem = new \Twig_Loader_Filesystem($projectTemplates);
            foreach ($namespacedPaths as $namespace => $path) {
                $filesystem->addPath($path, $namespace);
            }
            return $filesystem;
        };

        $app['twig'] = $app->extend('twig', function ($twig, $app) {
            // add custom globals, filters, tags, ...
            return $twig;
        });
    }

    protected function getCrateTemplatesPaths(CrateMap $crateMap)
    {
        $paths = [];
        foreach ($crateMap as $crate) {
            $templatesPath = $crate->getRootDir().'/templates';
            if (is_readable($templatesPath)) {
                $paths[$crate->getName()] = $templatesPath;
            }
        }

        return $paths;
    }
}
