<?php

namespace lib\Old\Legacy\CacheWarmer\Resolver;

class Method implements ResolverInterface
{
    public function getIp($hostname)
    {
        return gethostbyname($hostname);
    }
}