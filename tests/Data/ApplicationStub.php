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
use Whoa\Contracts\Core\ApplicationInterface;
use Whoa\Contracts\Core\SapiInterface;
use Mockery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @package Whoa\Tests\Testing
 */
class ApplicationStub implements ApplicationInterface
{
    /**
     * @inheritdoc
     */
    public function setSapi(SapiInterface $sapi): ApplicationInterface
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function createContainer(string $method = null, string $path = null): WhoaContainerInterface
    {
        $container = $this->createContainerInstance();

        $this->configureContainer($container);

        return $container;
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
    }

    /**
     * @return WhoaContainerInterface
     */
    protected function createContainerInstance(): WhoaContainerInterface
    {
        /** @var WhoaContainerInterface $mock */
        return Mockery::mock(WhoaContainerInterface::class);
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
    ) {
        $container && $globalConfigurators && $routeConfigurators ?: null;
    }

    /**
     * @param Closure $handler
     * @param RequestInterface|null $request
     *
     * @return ResponseInterface
     */
    protected function handleRequest(Closure $handler, RequestInterface $request = null): ResponseInterface
    {
        $handler && $request ?: null;

        /** @var ResponseInterface $mock */
        return Mockery::mock(ResponseInterface::class);
    }
}
