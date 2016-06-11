<?php

namespace Honeybee\FrameworkBinding\Silex\Config\Handler;

class MigrationConfigHandler extends ArrayConfigHandler
{
    protected function handleConfigFile($configFile)
    {
        return $this->parse($configFile);
    }

    protected function mergeConfigs(array $out, array $in)
    {
        return array_merge($out, $in);
    }
}
