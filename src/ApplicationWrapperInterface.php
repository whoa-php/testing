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
use Whoa\Contracts\Core\ApplicationInterface;

/**
 * @package Whoa\Testing
 */
interface ApplicationWrapperInterface extends ApplicationInterface
{
    /** Called right before request will be passed through all middleware, controller and back */
    public const EVENT_ON_HANDLE_REQUEST = 0;

    /** Called when response has passed back all middleware right before sending to client */
    public const EVENT_ON_HANDLE_RESPONSE = self::EVENT_ON_HANDLE_REQUEST + 1;

    /** Called on empty container created (before it's set up) */
    public const EVENT_ON_CONTAINER_CREATED = self::EVENT_ON_HANDLE_RESPONSE + 1;

    /** Called right before controller is called */
    public const EVENT_ON_CONTAINER_LAST_CONFIGURATOR = self::EVENT_ON_CONTAINER_CREATED + 1;

    /**
     * @param Closure $handler
     * @return self
     */
    public function addOnHandleRequest(Closure $handler): self;

    /**
     * @param Closure $handler
     * @return self
     */
    public function addOnHandleResponse(Closure $handler): self;

    /**
     * @param Closure $handler
     * @return self
     */
    public function addOnContainerCreated(Closure $handler): self;

    /**
     * @param Closure $handler
     * @return self
     */
    public function addOnContainerLastConfigurator(Closure $handler): self;
}
