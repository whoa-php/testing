<?php

/**
 * Copyright 2015-2020 info@neomerx.com
 * Modification Copyright 2021-2022 info@whoaphp.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Whoa\Testing;

use Closure;
use Whoa\Contracts\Container\ContainerInterface as WhoaContainerInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use function array_merge;
use function assert;
use function call_user_func_array;
use function in_array;

/**
 * @package Whoa\Testing
 */
trait ApplicationWrapperTrait
{
    /**
     * @var array
     */
    private array $events = [];

    /**
     * @var PsrContainerInterface
     */
    private PsrContainerInterface $container;

    /**
     * @param Closure $handler
     * @return ApplicationWrapperInterface
     */
    public function addOnHandleRequest(Closure $handler): ApplicationWrapperInterface
    {
        return $this->addEventHandler(ApplicationWrapperInterface::EVENT_ON_HANDLE_REQUEST, $handler);
    }

    /**
     * @param Closure $handler
     * @return ApplicationWrapperInterface
     */
    public function addOnHandleResponse(Closure $handler): ApplicationWrapperInterface
    {
        return $this->addEventHandler(ApplicationWrapperInterface::EVENT_ON_HANDLE_RESPONSE, $handler);
    }

    /**
     * @param Closure $handler
     * @return ApplicationWrapperInterface
     */
    public function addOnContainerCreated(Closure $handler): ApplicationWrapperInterface
    {
        return $this->addEventHandler(ApplicationWrapperInterface::EVENT_ON_CONTAINER_CREATED, $handler);
    }

    /**
     * @param Closure $handler
     * @return ApplicationWrapperInterface
     */
    public function addOnContainerLastConfigurator(Closure $handler): ApplicationWrapperInterface
    {
        return $this->addEventHandler(ApplicationWrapperInterface::EVENT_ON_CONTAINER_LAST_CONFIGURATOR, $handler);
    }

    /**
     * @param int $eventId
     * @param Closure $handler
     * @return ApplicationWrapperInterface
     */
    protected function addEventHandler(int $eventId, Closure $handler): ApplicationWrapperInterface
    {
        assert(
            in_array($eventId, [
                ApplicationWrapperInterface::EVENT_ON_HANDLE_REQUEST,
                ApplicationWrapperInterface::EVENT_ON_HANDLE_RESPONSE,
                ApplicationWrapperInterface::EVENT_ON_CONTAINER_CREATED,
                ApplicationWrapperInterface::EVENT_ON_CONTAINER_LAST_CONFIGURATOR,
            ]) === true
        );

        $this->events[$eventId][] = $handler;

        /** @var ApplicationWrapperInterface $self */
        assert($this instanceof ApplicationWrapperInterface);
        return $this;
    }

    /**
     * @param int $eventId
     * @param array $arguments
     * @return void
     */
    protected function dispatchEvent(int $eventId, array $arguments): void
    {
        $appAndArgs = array_merge([$this], $arguments);
        foreach ($this->events[$eventId] ?? [] as $handler) {
            call_user_func_array($handler, $appAndArgs);
        }
    }

    /**
     * @return WhoaContainerInterface
     */
    protected function createContainerInstance(): WhoaContainerInterface
    {
        $this->container = parent::createContainerInstance();

        $this->dispatchEvent(ApplicationWrapperInterface::EVENT_ON_CONTAINER_CREATED, [$this->getContainer()]);

        return $this->container;
    }

    /**
     * @param WhoaContainerInterface $container
     * @param array|null $globalConfigurators
     * @param array|null $routeConfigurators
     * @return void
     */
    protected function configureContainer(
        WhoaContainerInterface $container,
        array $globalConfigurators = null,
        array $routeConfigurators = null
    ): void {
        parent::configureContainer($container, $globalConfigurators, $routeConfigurators);

        $this->dispatchEvent(ApplicationWrapperInterface::EVENT_ON_CONTAINER_LAST_CONFIGURATOR, [$container]);
    }

    /**
     * @param Closure $handler
     * @param RequestInterface|null $request
     * @return ResponseInterface
     */
    protected function handleRequest(Closure $handler, RequestInterface $request = null): ResponseInterface
    {
        $this->dispatchEvent(ApplicationWrapperInterface::EVENT_ON_HANDLE_REQUEST, [$request]);

        $response = parent::handleRequest($handler, $request);

        $this->dispatchEvent(ApplicationWrapperInterface::EVENT_ON_HANDLE_RESPONSE, [$response]);

        return $response;
    }

    /**
     * @return PsrContainerInterface
     */
    protected function getContainer(): PsrContainerInterface
    {
        return $this->container;
    }
}
