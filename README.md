# Celema Wire

<!-- prettier-ignore-start -->
[![ci](https://codeberg.org/celema/wire/badges/workflows/ci.yml/badge.svg?style=flat&logo=codeberg&logoColor=white&label=ci)](https://codeberg.org/celema/wire/actions)
[![code coverage](https://img.shields.io/endpoint?url=https%3A%2F%2Fcov.celema.dev%2Fcelema%2Fwire%2Fcode%2Fbadge.json)](https://cov.celema.dev/celema/wire/code)
[![type coverage](https://img.shields.io/endpoint?url=https%3A%2F%2Fcov.celema.dev%2Fcelema%2Fwire%2Ftypes%2Fbadge-cover.json)](https://cov.celema.dev/celema/wire/types)
[![psalm level](https://img.shields.io/endpoint?url=https%3A%2F%2Fcov.celema.dev%2Fcelema%2Fwire%2Ftypes%2Fbadge-level.json)](https://cov.celema.dev/celema/wire/types)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)
<!-- prettier-ignore-end -->

**_Wire_** provides an autowiring object creator that utilizes PHP's reflection capabilities to automatically resolve constructor arguments recursively. It additionally comes with classes that assist in resolving arguments of callables such as functions, methods, closures or class constructors. It can be combined with a PSR-11 dependency injection container.

**Wire** is a PHP dependency injection tool that automatically constructs objects by resolving their dependencies. Using PHP's reflection API, **Wire** recursively analyzes and fulfills constructor arguments without manual configuration. It additionally includes utilities for resolving dependencies in various callable types—including functions, methods, closures, and class constructors. **_Wire_** seamlessly integrates with PSR-11 compliant dependency injection containers.

Documentation can be found on the website: [celema.dev/wire](https://celema.dev/wire/)

## Installation

```bash
composer require celema/wire
```

## Basic usage

```php
use Celema\Wire\Wire;

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
