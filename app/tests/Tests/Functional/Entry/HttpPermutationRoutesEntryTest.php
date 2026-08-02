<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Functional\Entry;

use App\Tests\Functional\Abstract\RuntimeServerTestCase;

use const PHP_BINARY;

/**
 * End-to-end test for every routing permutation the application defines.
 *
 * Serves `app/public` through PHP's built-in web server and drives a real HTTP
 * request for each permutation, asserting the route matches, binds its
 * parameter(s), and echoes the expected body — and that values which must not
 * match are rejected. This runs against the sindri-generated routing data, so it
 * proves the generated cache routes exactly as the framework does at runtime.
 */
final class HttpPermutationRoutesEntryTest extends RuntimeServerTestCase
{
    private const string UUID = '66a39476-b630-4b95-8bfb-355f3d4843c5';
    private const string ULID = '01KYGBV64MKWPK63CC1QH0VGF7';
    private const string VLID = '04YHJMN6F5XHM497ZW';

    public function testServesEveryRoutingPermutationOverHttp(): void
    {
        $this->port = $this->findFreePort();

        $this->startServer([
            PHP_BINARY,
            '-S',
            "127.0.0.1:{$this->port}",
            '-t',
            'app/public',
        ]);

        // Each permutation echoes the value bound to its parameter(s).
        $matches = [
            'num'                  => ['/permutations/num/42', '42'],
            'id'                   => ['/permutations/id/7', '7'],
            'slug'                 => ['/permutations/slug/my-slug-1', 'my-slug-1'],
            'alpha'                => ['/permutations/alpha/abc', 'abc'],
            'alpha lowercase'      => ['/permutations/alpha-lowercase/abc', 'abc'],
            'alpha uppercase'      => ['/permutations/alpha-uppercase/ABC', 'ABC'],
            'alpha num'            => ['/permutations/alpha-num/abc123', 'abc123'],
            'alpha num underscore' => ['/permutations/alpha-num-underscore/abc_123', 'abc_123'],
            'any'                  => ['/permutations/any/anything-1.x', 'anything-1.x'],
            'uuid'                 => ['/permutations/uuid/' . self::UUID, self::UUID],
            'ulid'                 => ['/permutations/ulid/' . self::ULID, self::ULID],
            'vlid'                 => ['/permutations/vlid/' . self::VLID, self::VLID],
            'multi parameters'     => ['/permutations/multi/12/two', '12-two'],
            // The parameter matches but is not captured, so the default is used.
            'non capture'          => ['/permutations/non-capture/abc', 'non-capture'],
            // An absent optional parameter falls back to its default.
            'optional absent'      => ['/permutations/optional', 'default'],
            'optional present'     => ['/permutations/optional/present', 'present'],
            'static'               => ['/permutations/static', 'static'],
            'any method'           => ['/permutations/any-method', 'any-method'],
        ];

        foreach ($matches as $label => [$path, $expected]) {
            $response = $this->httpRequest($path);

            self::assertSame(200, $response['status'], "The $label route did not match $path");
            self::assertSame($expected, $response['body'], "The $label route returned an unexpected body");
        }

        // A value that does not satisfy the parameter's regex must not match.
        $rejections = [
            'num rejects alpha'                 => '/permutations/num/abc',
            'alpha rejects a digit'             => '/permutations/alpha/abc1',
            'alpha lowercase rejects uppercase' => '/permutations/alpha-lowercase/Abc',
            'alpha uppercase rejects lowercase' => '/permutations/alpha-uppercase/abc',
            'alpha num rejects a hyphen'        => '/permutations/alpha-num/abc-1',
            'uuid rejects a non uuid'           => '/permutations/uuid/not-a-uuid',
            'ulid rejects a non ulid'           => '/permutations/ulid/notaulid',
        ];

        foreach ($rejections as $label => $path) {
            self::assertSame(404, $this->httpRequest($path)['status'], "The $label case unexpectedly matched");
        }

        // A route restricted to POST answers POST, and rejects GET as method not allowed.
        $post = $this->httpRequest('/permutations/post', 'POST');

        self::assertSame(200, $post['status']);
        self::assertSame('post', $post['body']);
        self::assertSame(405, $this->httpRequest('/permutations/post')['status']);

        // A route declared for any method answers a method it never named explicitly.
        $anyMethod = $this->httpRequest('/permutations/any-method', 'DELETE');

        self::assertSame(200, $anyMethod['status']);
        self::assertSame('any-method', $anyMethod['body']);
    }
}
