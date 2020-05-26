<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Server;

use Hyperf\Server\Exception\InvalidArgumentException;
use Hyperf\Utils\Contracts\Arrayable;

/**
 * @method ServerConfig setMode(int $mode)
 * @method ServerConfig setServers(array $servers)
 * @method ServerConfig setProcesses(array $processes)
 * @method ServerConfig setSettings(array $settings)
 * @method ServerConfig setCallbacks(array $callbacks)
 * @method int getMode()
 * @method array getServers()
 * @method array getProcesses()
 * @method array getSettings()
 * @method array getCallbacks()
 */
class ServerConfig implements Arrayable
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;

        if (empty($config['servers'] ?? [])) {
            throw new InvalidArgumentException('Config server.servers not exist.');
        }

        $servers = [];
        foreach ($config['servers'] as $item) {
            $servers[] = Port::build($item);
        }

        $this->setMode($config['mode'] ?? SWOOLE_BASE)
            ->setServers($servers)
            ->setProcesses($config['processes'] ?? [])
            ->setSettings($config['settings'] ?? [])
            ->setCallbacks($config['callbacks'] ?? []);
    }

    public function __set($name, $value): self
    {
        if (! $this->isAvailableProperty($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid property %s', $name));
        }
        $this->config[$name] = $value;
        return $this;
    }

    public function __get($name)
    {
        if (! $this->isAvailableProperty($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid property %s', $name));
        }
        return $this->config[$name] ?? null;
    }

    public function __call($name, $arguments)
    {
        $prefix = strtolower(substr($name, 0, 3));
        if (in_array($prefix, ['set', 'get'])) {
            $propertyName = strtolower(substr($name, 3));
            if (! $this->isAvailableProperty($propertyName)) {
                throw new \InvalidArgumentException(sprintf('Invalid property %s', $propertyName));
            }
            return $prefix === 'set' ? $this->__set($propertyName, ...$arguments) : $this->__get($propertyName);
        }
    }

    public function addServer(Port $port): ServerConfig
    {
        $this->config['servers'][] = $port;
        return $this;
    }

    public function toArray(): array
    {
        return $this->config;
    }

    private function isAvailableProperty(string $name)
    {
        return in_array($name, [
            'mode', 'servers', 'processes', 'settings', 'callbacks',
        ]);
    }
}
