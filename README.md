<p align="center"><a href="https://valkyrja.io" target="_blank">
    <img src="https://raw.githubusercontent.com/valkyrjaio/art/refs/heads/master/long-banner/orange/php.png" width="100%">
</a></p>

# Valkyrja Starter (App)

Starter template for building PHP applications on the
[Valkyrja][Valkyrja url] framework.

This repository gives you a working Valkyrja application as a starting point —
HTTP and CLI kernels pre-wired, example controllers and commands, configuration
scaffolding, and a ready-to-customize `App/` namespace. The starter passes the
same linting, static analysis, and architectural rules as the Valkyrja
framework itself, so you can focus on building your application rather than
cleaning up the foundation.

<p>
    <a href="https://packagist.org/packages/valkyrja/application"><img src="https://poser.pugx.org/valkyrja/application/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/valkyrja/application"><img src="https://poser.pugx.org/valkyrja/application/require/php" alt="PHP Version Require"></a>
    <a href="https://packagist.org/packages/valkyrja/application"><img src="https://poser.pugx.org/valkyrja/application/license" alt="License"></a>
    <a href="https://github.com/valkyrjaio/valkyrja-starter-app-php/actions/workflows/ci.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/valkyrja-starter-app-php/actions/workflows/ci.yml/badge.svg?branch=26.x" alt="CI Status"></a>
    <a href="https://scrutinizer-ci.com/g/valkyrjaio/valkyrja-starter-app-php/?branch=26.x"><img src="https://scrutinizer-ci.com/g/valkyrjaio/valkyrja-starter-app-php/badges/quality-score.png?b=26.x" alt="Scrutinizer"></a>
    <a href="https://shepherd.dev/github/valkyrjaio/valkyrja-starter-app-php"><img src="https://shepherd.dev/github/valkyrjaio/valkyrja-starter-app-php/coverage.svg" alt="Psalm Shepherd"></a>
    <a href="https://sonarcloud.io/summary/new_code?id=valkyrjaio_application"><img src="https://sonarcloud.io/api/project_badges/measure?project=valkyrjaio_application&metric=sqale_rating" alt="Maintainability Rating" /></a>
</p>

What's in the Box
-----------------

- **Pre-wired HTTP and CLI kernels** — the application boots and responds to
  both web requests and command-line invocations out of the box
- **Example controllers and commands** — working code showing typical routing,
  request handling, and command dispatch patterns
- **Configuration scaffolding** — `Config/` and `Data/` layers with example
  files and environment-driven overrides
- **Testing setup** — PHPUnit configured with example tests and the same
  structure used across Valkyrja's own components
- **Full CI pipeline** — PHPStan, Psalm, PHPCodeSniffer, PHP CS Fixer,
  PHPArkitect, and Rector all configured and passing on a clean clone
- **Worker runtime integrations** _(optional)_ — OpenSwoole, FrankenPHP, or
  RoadRunner for persistent-worker deployments

Installation
------------

### Use this template _(recommended)_

This repository is a GitHub template. Click the **Use this template** button
at the top of the repo to create a new repository in your own account,
pre-populated with the starter code.

### Via Composer

```
composer create-project valkyrja/application your-project
cd your-project
```

### Clone manually _(for contributing to the starter itself)_

```
git clone git@github.com:valkyrjaio/valkyrja-starter-app-php.git
cd valkyrja-starter-app-php
composer install
```

Getting Started
---------------

### Project Structure

The key directories you'll work in:

```
app/
├── src/
│   └── App/           # your application code lives here
│       ├── Cli/       # CLI commands, providers, and configuration
│       └── Http/      # HTTP controllers, providers, and configuration
├── bootstrap/         # application entry points (HTTP and CLI)
├── config/            # environment and runtime configuration
└── tests/             # your test suite
```

Your application code goes in the `App\` namespace under `app/src/App/`. The
starter provides example HTTP controllers and CLI commands you can study,
modify, or replace.

### Running Your Application

**HTTP (built-in PHP server):**

```
php -S localhost:8080 -t bootstrap/http
```

Navigate to `http://localhost:8080` to see the example routes.

**CLI:**

```
php bootstrap/cli/valkyrja
```

Run with no arguments to see the list of available commands.

### Writing Code

**Adding a route:** see the example controller in `app/src/App/Http/Controller/`
and the route definitions it registers. For the full routing API, see the
[Valkyrja HTTP documentation][http docs url].

**Adding a command:** see the example command in `app/src/App/Cli/Command/`.
For the full CLI API, see the
[Valkyrja CLI documentation][cli docs url].

**Binding services:** the dependency injection container is configured in the
`Provider` classes under each `App/` subdirectory. See the
[Valkyrja container documentation][container docs url] for the full API.

### Running Tests

```
composer phpunit
```

For coverage:

```
composer phpunit-coverage
```

### Running CI Checks Locally

The starter ships with the same CI pipeline as the Valkyrja framework. Run
any check via its composer script:

```
composer phpstan
composer psalm
composer phpcodesniffer
composer phpcsfixer
composer phparkitect
composer rector
```

Deployment
----------

The starter runs on any PHP 8.4+ environment. For production, Valkyrja
supports several persistent-worker runtimes for significantly better
performance than traditional PHP-FPM:

- [**OpenSwoole**][openswoole url] — persistent worker via the OpenSwoole
  extension
- [**FrankenPHP**][frankenphp url] — persistent worker via the FrankenPHP
  embedded runtime
- [**RoadRunner**][roadrunner url] — persistent worker via the Go-based
  RoadRunner manager

See each integration's README for setup instructions specific to that runtime.
For containerized deployment, [`valkyrja-docker-php`][docker url] provides
ready-made Docker configurations.

Documentation
-------------

Full Valkyrja documentation lives in the [framework repository][docs url] and
is baked into the source tree so you can browse it offline.

For starter-specific questions, open an issue on this repository. For
framework questions, open an issue on the
[Valkyrja framework repository][framework url].

Contributing
------------

Contributions to the starter itself — improvements to the example code,
bug fixes, CI improvements — are welcome. See
[`CONTRIBUTING.md`][contributing url] for the submission process and
[`VOCABULARY.md`][vocabulary url] for the terminology used across Valkyrja.

License
-------

The Valkyrja framework and this starter are open-source software licensed
under the [MIT license][MIT license url]. See [`LICENSE.md`](./LICENSE.md).

[Valkyrja url]: https://valkyrja.io
[framework url]: https://github.com/valkyrjaio/valkyrja-php
[docker url]: https://github.com/valkyrjaio/valkyrja-docker-php
[openswoole url]: https://github.com/valkyrjaio/valkyrja-openswoole-php
[frankenphp url]: https://github.com/valkyrjaio/valkyrja-frankenphp-php
[roadrunner url]: https://github.com/valkyrjaio/valkyrja-roadrunner-php
[docs url]: https://github.com/valkyrjaio/valkyrja-php/tree/26.x/src/Valkyrja/README.md
[http docs url]: https://github.com/valkyrjaio/valkyrja-php/tree/26.x/src/Valkyrja/Http/README.md
[cli docs url]: https://github.com/valkyrjaio/valkyrja-php/tree/26.x/src/Valkyrja/Cli/README.md
[container docs url]: https://github.com/valkyrjaio/valkyrja-php/tree/26.x/src/Valkyrja/Container/README.md
[contributing url]: https://github.com/valkyrjaio/.github/blob/master/CONTRIBUTING.md
[vocabulary url]: https://github.com/valkyrjaio/.github/blob/master/VOCABULARY.md
[MIT license url]: https://opensource.org/licenses/MIT
