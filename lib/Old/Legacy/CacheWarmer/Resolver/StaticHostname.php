<?php

namespace lib\Old\Legacy\CacheWarmer\Resolver;

class StaticHostname implements ResolverInterface
{
    private $ip;

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getIp($hostname)
    {
        return $this->ip;
    }
}