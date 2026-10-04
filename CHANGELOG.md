# Changelog

## [Unreleased](https://codefloe.com/celema/wire/compare/0.8.0...HEAD)

### Breaking Changes

- `Creator::create()` and `ConstructorResolver::resolve()` throw a `WireException` when predefined arguments are given for a class without a constructor. Before, the arguments were silently dropped.

## [0.8.0](https://codefloe.com/celema/wire/src/tag/0.8.0) (2026-10-02)

### Breaking Changes

- `Creator::create()` invokes the requested class's constructor or a specified factory method instead of looking up a registered container entry for that class; the container is only used for its parameters. Direct construction produces a new instance, while a factory method may return an existing one.
- Remove the `WireContainer` interface. Containers that use Wire internally build their entries with `create()`, which never asks the container for the requested class, so they no longer need to expose raw definitions.
- `CreatorInterface` gains `resolve()`.
- Creating an interface, an abstract class, or a class without a public constructor throws a `WireException` instead of PHP's `Error`.

### Added

- `Creator::resolve()` returns the container's entry for a registered id, honoring the entry's lifetime and configuration, and creates unregistered classes like `create()`.

## [0.7.0](https://codefloe.com/celema/wire/src/tag/0.7.0) (2026-07-18)

### Breaking Changes

- Rename the package from `celemas/wire` to `celema/wire` and the root namespace from `Celemas\Wire` to `Celema\Wire`.
- Move the source repository to the Celema organization and update the project domain and contact email.

## [0.6.0](https://codefloe.com/celema/wire/src/tag/0.6.0) (2026-05-12)

### Breaking Changes

- Rename package metadata, root namespace, repository URLs, homepage, and author info.

## [0.5.0](https://codefloe.com/celema/wire/src/tag/0.5.0) (2026-04-26)

### Breaking Changes

- Exceptions thrown by user code inside constructors, factory methods, and `#[Call]` methods now bubble unchanged instead of being wrapped in `WireException`.

### Changed

- `Creator` now autowires class-string definitions returned by `WireContainer::definition()` using the mapped class name, so interface-to-class mappings work correctly in containers that use Wire internally.
- Objects fetched directly from a container are no longer post-processed with `#[Call]` hooks.
- When a callable, constructor, or factory method uses `Inject` attributes, `predefinedArgs` must be named. Positional argument lists are rejected.

## [0.4.0](https://codefloe.com/celema/wire/src/tag/0.4.0) (2026-01-30)

### Breaking Changes

- Renamed Composer package to `duon/wire` and namespaces to `Duon\Wire\*` (previously `conia/wire` / `Conia\Wire\*`).
- Required PHP 8.5.
- Added and used the `WireContainer` interface for containers that use Wire internally to avoid dependency cycles (requires implementing `WireContainer::definition()`).
- `CreatorInterface::create()` parameter `$constructor` is now a `string` defaulting to `''` instead of `string|null`.

### Changed

- Improved performance by caching `ReflectionClass` instances in `Creator`.

## [0.3.0](https://codefloe.com/celema/wire/src/tag/0.3.0) (2024-01-18)

### Breaking Changes

- Changed the `Inject` attribute so that is is now annotated to parameters instead of functions or methods.

### Added

- Add `Type::Callback`.
- The optional `injectCallback` parameter to `Creator::create`.
- The optional `injectCallback` parameter to `CallableResolver::resolve`.
- The optional `injectCallback` parameter to `ConstructorResolver::resolve`.
- `Creator` now returns the container entry of the requested class if it exists. This way it supports instantiating interfaces if they are registered in the container.

## [0.2.0](https://codefloe.com/celema/wire/src/tag/0.2.0) (2024-01-05)

Add predefined types.

### Added

- The `predefinedTypes` parameter to `Creator::create`.
- The `predefinedTypes` parameter to `CallableResolver::resolve`.
- The `predefinedTypes` parameter to `ConstructorResolver::resolve`.

## [0.1.0](https://codefloe.com/celema/wire/src/tag/0.1.0) (2023-11-11)

Initial release.

### Added

- The `Wire` factory, which produces `Creator`, `CallableResolver` and `ContstructorResolver` instances.
- The `Inject` attribute.
- The `Call` attribute.
- The ability to be combined with PSR-11 containers.
