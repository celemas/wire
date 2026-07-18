# Changelog

## [Unreleased](https://codeberg.org/celema/wire/compare/0.6.0...HEAD)

### Breaking Changes

- Rename the package from `celemas/wire` to `celema/wire` and the root namespace from `Celemas\Wire` to `Celema\Wire`.
- Move the source repository to `codeberg.org/celema/wire` and update the project domain and contact email.

## [0.6.0](https://codeberg.org/celemas/wire/src/tag/0.6.0) (2026-05-12)

### Breaking Changes

- Rename package metadata, root namespace, repository URLs, homepage, and author info.

## [0.5.0](https://codeberg.org/celemas/wire/src/tag/0.5.0) (2026-04-26)

### Breaking Changes

- Exceptions thrown by user code inside constructors, factory methods, and `#[Call]` methods now bubble unchanged instead of being wrapped in `WireException`.

### Changed

- `Creator` now autowires class-string definitions returned by `WireContainer::definition()` using the mapped class name, so interface-to-class mappings work correctly in containers that use Wire internally.
- Objects fetched directly from a container are no longer post-processed with `#[Call]` hooks.
- When a callable, constructor, or factory method uses `Inject` attributes, `predefinedArgs` must be named. Positional argument lists are rejected.

## [0.4.0](https://codeberg.org/celemas/wire/src/tag/0.4.0) (2026-01-30)

### Breaking Changes

- Renamed Composer package to `duon/wire` and namespaces to `Duon\Wire\*` (previously `conia/wire` / `Conia\Wire\*`).
- Required PHP 8.5.
- Added and used the `WireContainer` interface for containers that use Wire internally to avoid dependency cycles (requires implementing `WireContainer::definition()`).
- `CreatorInterface::create()` parameter `$constructor` is now a `string` defaulting to `''` instead of `string|null`.

### Changed

- Improved performance by caching `ReflectionClass` instances in `Creator`.

## [0.3.0](https://codeberg.org/celemas/wire/src/tag/0.3.0) (2024-01-18)

### Breaking Changes

- Changed the `Inject` attribute so that is is now annotated to parameters instead of functions or methods.

### Added

- Add `Type::Callback`.
- The optional `injectCallback` parameter to `Creator::create`.
- The optional `injectCallback` parameter to `CallableResolver::resolve`.
- The optional `injectCallback` parameter to `ConstructorResolver::resolve`.
- `Creator` now returns the container entry of the requested class if it exists. This way it supports instantiating interfaces if they are registered in the container.

## [0.2.0](https://codeberg.org/celemas/wire/src/tag/0.2.0) (2024-01-05)

Add predefined types.

### Added

- The `predefinedTypes` parameter to `Creator::create`.
- The `predefinedTypes` parameter to `CallableResolver::resolve`.
- The `predefinedTypes` parameter to `ConstructorResolver::resolve`.

## [0.1.0](https://codeberg.org/celemas/wire/src/tag/0.1.0) (2023-11-11)

Initial release.

### Added

- The `Wire` factory, which produces `Creator`, `CallableResolver` and `ContstructorResolver` instances.
- The `Inject` attribute.
- The `Call` attribute.
- The ability to be combined with PSR-11 containers.
