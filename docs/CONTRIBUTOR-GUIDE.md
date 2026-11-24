# Contributor guide

Use the setup in the [README](../README.md). Create a branch from current `main`, use Conventional Commits, and keep each change independently reviewable. Commit the Composer lockfile with dependency changes so contributor and CI installs are reproducible. Composer consumers resolve against `composer.json` using their own lockfiles. The platform override keeps tool resolution compatible with PHP 8.3. The verification script checks actual PHP and extensions.

Use strict types, PSR-4, PSR-12, and PHPStan level 8. Put isolated tests in `tests/Unit` and component or filesystem tests in `tests/Integration`. Put deterministic synthetic data in `tests/Fixtures`. Keep tests offline and design for injected dependencies. Use explicit extension requirements when production code first needs a non-core extension.

Before opening a PR, run `composer verify` and record output or blockers. Describe changes to public API signatures, archive encodings, and retry semantics in the relevant specifications. Keep credentials and customer data out of fixtures, commits, exception messages, and diagnostics.
