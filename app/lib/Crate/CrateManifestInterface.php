<?php

namespace Honeybee\FrameworkBinding\Silex\Crate;

interface CrateManifestInterface
{
    public function getClass();

    public function getPrefix();

    public function getName();
}