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

namespace Whoa\Tests\Testing\Data;

use Closure;
use Whoa\Contracts\Container\ContainerInterface as WhoaContainerInterface;
use Whoa\Testing\ApplicationWrapperInterface;
use Whoa\Testing\ApplicationWrapperTrait;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @package Whoa\Tests\Testing
 */
class ApplicationWrapper extends ApplicationStub implements ApplicationWrapperInterface
{
    use ApplicationWrapperTrait;

    /**
     * @return WhoaContainerInterface
     */
    public function invokeCreateContainer(): WhoaContainerInterface
    {
        return $this->createContainerInstance();
    }

    /**
     * @param WhoaContainerInterface $container
     * @param array|null             $globalConfigurators
     * @param array|null             $routeConfigurators
     *
     * @return void
     */
    public function invokeConfigureContainer(
        WhoaContainerInterface $container,
        array $globalConfigurators = null,
        array $routeConfigurators = null
    )
    {
        $this->configureContainer($container, $globalConfigurators, $routeConfigurators);
    }

    /**
     * @param Closure               $handler
     * @param RequestInterface|null $request
     *
     * @return ResponseInterface
     */
    public function invokeHandleRequest(Closure $handler, RequestInterface $request = null): ResponseInterface
    {
        return $this->handleRequest($handler, $request);
    }

    /**
     * @return PsrContainerInterface
     */
    public function invokeGetContainer(): PsrContainerInterface
    {
        return $this->getContainer();
    }
}
