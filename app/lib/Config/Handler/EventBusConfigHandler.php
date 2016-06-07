<?php

namespace Honeybee\FrameworkBinding\Silex\Config\Handler;

class EventBusConfigHandler extends ArrayConfigHandler
{
    protected function handlConfigFile($configFile)
    {
        return $this->parse($configFile);
    }

    protected function mergeConfigs(array $out, array $in)
    {
        return array_merge($out, $in);
    }
}
