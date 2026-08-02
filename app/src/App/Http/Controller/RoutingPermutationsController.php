<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Http\Controller;

use App\Http\Controller\Abstract\Controller;
use App\Http\Provider\AppHttpRouteProvider;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Http\Routing\Constant\Regex;

/**
 * Demonstrates every routing permutation the framework supports.
 *
 * Each route echoes back the value(s) bound to its parameters so the produced regex,
 * the match, and the parameter binding can all be asserted end to end. Paths are
 * namespaced under /permutations so they never collide with the catch-all
 * `/{value}` route on HomeController.
 */
class RoutingPermutationsController extends Controller
{
    /**
     * A numeric parameter.
     */
    #[Route(path: '/permutations/num/{value}', name: 'permutations.num')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsNumHandler'])]
    public function num(
        #[Parameter(name: 'value', regex: Regex::NUM)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * An id parameter.
     */
    #[Route(path: '/permutations/id/{value}', name: 'permutations.id')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsIdHandler'])]
    public function id(
        #[Parameter(name: 'value', regex: Regex::ID)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A slug parameter.
     */
    #[Route(path: '/permutations/slug/{value}', name: 'permutations.slug')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsSlugHandler'])]
    public function slug(
        #[Parameter(name: 'value', regex: Regex::SLUG)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * An alphabetic parameter.
     */
    #[Route(path: '/permutations/alpha/{value}', name: 'permutations.alpha')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAlphaHandler'])]
    public function alpha(
        #[Parameter(name: 'value', regex: Regex::ALPHA)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A lowercase alphabetic parameter.
     */
    #[Route(path: '/permutations/alpha-lowercase/{value}', name: 'permutations.alphaLowercase')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAlphaLowercaseHandler'])]
    public function alphaLowercase(
        #[Parameter(name: 'value', regex: Regex::ALPHA_LOWERCASE)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * An uppercase alphabetic parameter.
     */
    #[Route(path: '/permutations/alpha-uppercase/{value}', name: 'permutations.alphaUppercase')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAlphaUppercaseHandler'])]
    public function alphaUppercase(
        #[Parameter(name: 'value', regex: Regex::ALPHA_UPPERCASE)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * An alphanumeric parameter.
     */
    #[Route(path: '/permutations/alpha-num/{value}', name: 'permutations.alphaNum')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAlphaNumHandler'])]
    public function alphaNum(
        #[Parameter(name: 'value', regex: Regex::ALPHA_NUM)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * An alphanumeric parameter that also allows underscores.
     */
    #[Route(path: '/permutations/alpha-num-underscore/{value}', name: 'permutations.alphaNumUnderscore')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAlphaNumUnderscoreHandler'])]
    public function alphaNumUnderscore(
        #[Parameter(name: 'value', regex: Regex::ALPHA_NUM_UNDERSCORE)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A parameter that matches anything.
     */
    #[Route(path: '/permutations/any/{value}', name: 'permutations.any')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAnyHandler'])]
    public function any(
        #[Parameter(name: 'value', regex: Regex::ANY)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A uuid parameter.
     */
    #[Route(path: '/permutations/uuid/{value}', name: 'permutations.uuid')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsUuidHandler'])]
    public function uuid(
        #[Parameter(name: 'value', regex: Regex::UUID)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A ulid parameter.
     */
    #[Route(path: '/permutations/ulid/{value}', name: 'permutations.ulid')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsUlidHandler'])]
    public function ulid(
        #[Parameter(name: 'value', regex: Regex::ULID)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A vlid parameter.
     */
    #[Route(path: '/permutations/vlid/{value}', name: 'permutations.vlid')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsVlidHandler'])]
    public function vlid(
        #[Parameter(name: 'value', regex: Regex::VLID)]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * An optional parameter that falls back to its default when absent.
     */
    #[Route(path: '/permutations/optional/{value?}', name: 'permutations.optional')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsOptionalHandler'])]
    public function optional(
        #[Parameter(name: 'value', regex: Regex::ALPHA, isOptional: true, default: 'default')]
        string $value
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * Multiple parameters separated by a static segment.
     */
    #[Route(path: '/permutations/multi/{first}/{second}', name: 'permutations.multi')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsMultiHandler'])]
    public function multi(
        #[Parameter(name: 'first', regex: Regex::NUM)]
        string $first,
        #[Parameter(name: 'second', regex: Regex::ALPHA)]
        string $second
    ): TextResponseContract {
        return new TextResponse("$first-$second");
    }

    /**
     * A parameter that is matched but deliberately not captured.
     */
    #[Route(path: '/permutations/non-capture/{value}', name: 'permutations.nonCapture')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsNonCaptureHandler'])]
    public function nonCapture(
        #[Parameter(name: 'value', regex: Regex::ALPHA, shouldCapture: false)]
        string $value = 'non-capture'
    ): TextResponseContract {
        return new TextResponse($value);
    }

    /**
     * A route with no parameters at all.
     */
    #[Route(path: '/permutations/static', name: 'permutations.static')]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsStaticHandler'])]
    public function staticRoute(): TextResponseContract
    {
        return new TextResponse('static');
    }

    /**
     * A route restricted to a single request method.
     */
    #[Route(path: '/permutations/post', name: 'permutations.post', requestMethods: [RequestMethod::POST])]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsPostHandler'])]
    public function post(): TextResponseContract
    {
        return new TextResponse('post');
    }

    /**
     * A route that answers to every request method.
     */
    #[Route(path: '/permutations/any-method', name: 'permutations.anyMethod', requestMethods: [RequestMethod::ANY])]
    #[RouteHandler([AppHttpRouteProvider::class, 'permutationsAnyMethodHandler'])]
    public function anyMethod(): TextResponseContract
    {
        return new TextResponse('any-method');
    }
}
