# Celemas Wire

<!-- prettier-ignore-start -->
[![ci](https://github.com/celemas/wire/actions/workflows/ci.yml/badge.svg)](https://github.com/celemas/wire/actions)
[![codecov](https://codecov.io/github/celemas/wire/graph/badge.svg?token=NPBYNZ7P2B)](https://codecov.io/github/celemas/wire)
[![psalm coverage](https://shepherd.dev/github/celemas/wire/coverage.svg?)](https://shepherd.dev/github/celemas/wire)
[![psalm level](https://shepherd.dev/github/celemas/wire/level.svg?)](https://shepherd.dev/github/celemas/wire)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
<!-- prettier-ignore-end -->

**_Wire_** provides an autowiring object creator that utilizes PHP's reflection capabilities to automatically resolve constructor arguments recursively. It additionally comes with classes that assist in resolving arguments of callables such as functions, methods, closures or class constructors. It can be combined with a PSR-11 dependency injection container.

**Wire** is a PHP dependency injection tool that automatically constructs objects by resolving their dependencies. Using PHP's reflection API, **Wire** recursively analyzes and fulfills constructor arguments without manual configuration. It additionally includes utilities for resolving dependencies in various callable types—including functions, methods, closures, and class constructors. **_Wire_** seamlessly integrates with PSR-11 compliant dependency injection containers.

Documentation can be found on the website: [celemas.dev/wire](https://celemas.dev/wire/)

## Installation

```bash
composer require celemas/wire
```

## Basic usage

```php
use Celemas\Wire\Wire;

class Value
{
    public function get(): string
    {
        return 'Autowired Value';
    }
}

class Model
{
    public function __construct(protected Value $value) {}

    public function value(): string
    {
        return $this->value->get();
    }
}

$creator = Wire::creator();
$model = $creator->create(Model::class);

assert($model instanceof Model);
assert($model->value() === 'Autowired Value');
```

## Scoped container usage

When you pass a scoped container to `Creator`, callable resolvers and `Inject` entry lookups resolve against that current scope first.

This allows parent-owned definitions (for example root shared services) and scope-local overrides (for example request-local values) to work together safely.

## License

This project is licensed under the [MIT license](LICENSE.md).
