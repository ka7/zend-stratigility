<?php
/**
 * @link      https://github.com/zendframework/zend-stratigility for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://framework.zend.com/license New BSD License
 */

namespace Zend\Stratigility;

use Interop\Http\Middleware\MiddlewareInterface as InteropMiddlewareInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use InvalidArgumentException;
use OutOfRangeException;

/**
 * Value object representing route-based middleware
 *
 * Details the subpath on which the middleware is active, and the
 * handler for the middleware itself.
 *
 * @property-read callable $handler Handler for this route
 * @property-read string $path Path for this route
 */
class Route
{
    /**
     * @var InteropMiddlewareInterface|ServerMiddlewareInterface
     */
    protected $handler;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path
     * @param InteropMiddlewareInterface|ServerMiddlewareInterface $handler
     * @throws Exception\InvalidMiddlewareException if the $handler provided is
     *     not an http-interop middleare type.
     */
    public function __construct($path, $handler)
    {
        if (! is_string($path)) {
            throw new InvalidArgumentException('Path must be a string');
        }

        if (! ($handler instanceof ServerMiddlewareInterface
                || $handler instanceof InteropMiddlewareInterface
            )
        ) {
            throw new Exception\InvalidMiddlewareException(sprintf(
                'Middleware must implement an http-interop middleware interface; received %s',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }

        $this->path    = $path;
        $this->handler = $handler;
    }

    /**
     * @param mixed $name
     * @return mixed
     * @throws OutOfRangeException for invalid properties
     */
    public function __get($name)
    {
        if (! property_exists($this, $name)) {
            throw new OutOfRangeException('Only the path and handler may be accessed from a Route instance');
        }
        return $this->{$name};
    }
}