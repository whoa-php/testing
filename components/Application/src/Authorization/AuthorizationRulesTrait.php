<?php namespace Limoncello\Application\Authorization;

/**
 * Copyright 2015-2017 info@neomerx.com
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

use Limoncello\Auth\Contracts\Authorization\PolicyInformation\ContextInterface;
use Limoncello\Contracts\Authentication\AccountInterface;
use Limoncello\Contracts\Authentication\AccountManagerInterface;
use Psr\Container\ContainerInterface;

/**
 * @package Limoncello\Application
 */
trait AuthorizationRulesTrait
{
    /**
     * @param ContextInterface $context
     *
     * @return string
     */
    protected static function ctxGetAction(ContextInterface $context): string
    {
        assert($context->has(RequestProperties::REQ_ACTION));

        $value = $context->get(RequestProperties::REQ_ACTION);

        return $value;
    }

    /**
     * @param ContextInterface $context
     *
     * @return string|null
     */
    protected static function ctxGetResourceType(ContextInterface $context)
    {
        assert($context->has(RequestProperties::REQ_RESOURCE_TYPE));

        $value = $context->get(RequestProperties::REQ_RESOURCE_TYPE);

        assert($value === null || is_string($value));

        return $value;
    }

    /**
     * @param ContextInterface $context
     *
     * @return string|int|null
     */
    protected static function ctxGetResourceIdentity(ContextInterface $context)
    {
        assert($context->has(RequestProperties::REQ_RESOURCE_IDENTITY));

        $value = $context->get(RequestProperties::REQ_RESOURCE_IDENTITY);

        assert($value === null || is_string($value) || is_int($value));

        return $value;
    }

    /**
     * @param ContextInterface $context
     *
     * @return AccountInterface
     */
    protected static function ctxGetCurrentAccount(ContextInterface $context): AccountInterface
    {
        $container = static::ctxGetContainer($context);

        assert($container->has(AccountManagerInterface::class));

        /** @var AccountManagerInterface $manager */
        $manager = $container->get(AccountManagerInterface::class);
        $account = $manager->getAccount();

        return $account;
    }

    /**
     * @param ContextInterface $context
     *
     * @return ContainerInterface
     */
    protected static function ctxGetContainer(ContextInterface $context): ContainerInterface
    {
        assert($context->has(ContextProperties::CTX_CONTAINER));

        return $context->get(ContextProperties::CTX_CONTAINER);
    }
}