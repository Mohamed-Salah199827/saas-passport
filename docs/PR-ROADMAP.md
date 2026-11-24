# 103-pull-request roadmap

Items 001 through 030 were delivered in the bootstrap. IDs group related work by phase. Core work is underway. **049** is complete. The paged read contracts from **050** work, with cursor persistence semantics still pending. The scalar end-to-end workflow covers parts of later items. Planned items can proceed as their prerequisites are completed. See [milestone gates](ROADMAP.md).

## Phase A — Repository foundation and governance (001–012)

### 001 — docs(readme): explain SaaS Passport

- **Status** · Completed in bootstrap
- **Purpose** · Orient prospective users before they try the checkout.
- **Implementation scope and acceptance criteria** · State pre-alpha scope and local setup.
- **Expected files or modules** · `README.md`
- **Dependencies** · None
- **Required tests** · Follow onboarding commands.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 002 — docs(license): retain Apache terms

- **Status** · Completed in bootstrap
- **Purpose** · Make redistribution terms clear.
- **Implementation scope and acceptance criteria** · License and package metadata agree.
- **Expected files or modules** · `LICENSE`, `composer.json`
- **Dependencies** · None
- **Required tests** · Validate SPDX metadata.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 003 — docs(contributing): define contribution workflow

- **Status** · Completed in bootstrap
- **Purpose** · Make changes easy to review.
- **Implementation scope and acceptance criteria** · Link backlog, synthetic data policy and review criteria.
- **Expected files or modules** · `CONTRIBUTING.md`
- **Dependencies** · 001
- **Required tests** · Walk contributor links.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 004 — docs(conduct): define community standards

- **Status** · Completed in bootstrap
- **Purpose** · Maintain welcoming contribution spaces.
- **Implementation scope and acceptance criteria** · Document reporting and enforcement for public project spaces.
- **Expected files or modules** · `CODE_OF_CONDUCT.md`
- **Dependencies** · 003
- **Required tests** · Review reporting routes.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 005 — docs(security): establish private disclosure

- **Status** · Completed in bootstrap
- **Purpose** · Keep security details out of public threads.
- **Implementation scope and acceptance criteria** · Specify private reporting and report contents.
- **Expected files or modules** · `SECURITY.md`
- **Dependencies** · 001
- **Required tests** · Check advisory link.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 006 — docs(changelog): record bootstrap changes

- **Status** · Completed in bootstrap
- **Purpose** · Give contributors a record of user-visible changes.
- **Implementation scope and acceptance criteria** · Unreleased entries reflect current features.
- **Expected files or modules** · `CHANGELOG.md`
- **Dependencies** · 001
- **Required tests** · Compare against source.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 007 — chore(issues): add issue intake forms

- **Status** · Completed in bootstrap
- **Purpose** · Collect the information needed for triage.
- **Implementation scope and acceptance criteria** · Require useful bug and feature context.
- **Expected files or modules** · `.github/ISSUE_TEMPLATE`
- **Dependencies** · 005
- **Required tests** · Parse issue YAML.
- **Suggested labels** · `foundation`
- **Size** · S

### 008 — chore(pr): collect review evidence

- **Status** · Completed in bootstrap
- **Purpose** · Capture evidence for review.
- **Implementation scope and acceptance criteria** · Prompt for checks, compatibility and roadmap tracking.
- **Expected files or modules** · `.github/PULL_REQUEST_TEMPLATE.md`
- **Dependencies** · 003
- **Required tests** · Walk a sample submission.
- **Suggested labels** · `foundation`
- **Size** · S

### 009 — chore(ownership): route code review

- **Status** · Completed in bootstrap
- **Purpose** · Direct review requests to maintainers.
- **Implementation scope and acceptance criteria** · Default ownership matches this repository.
- **Expected files or modules** · `.github/CODEOWNERS`
- **Dependencies** · 003
- **Required tests** · Inspect owner syntax.
- **Suggested labels** · `foundation`
- **Size** · S

### 010 — docs(github): describe hosted configuration

- **Status** · Completed in bootstrap
- **Purpose** · Make repository administration reproducible.
- **Implementation scope and acceptance criteria** · Explain the ruleset, Discussions, labels and private reports.
- **Expected files or modules** · `docs/GITHUB-SETUP.md`
- **Dependencies** · 005, 009
- **Required tests** · Match documented checks to CI.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 011 — docs(roadmap): define milestone gates

- **Status** · Completed in bootstrap
- **Purpose** · Make delivery gates visible.
- **Implementation scope and acceptance criteria** · Every milestone has measurable exit criteria.
- **Expected files or modules** · `docs/ROADMAP.md`
- **Dependencies** · 001
- **Required tests** · Trace phase ranges.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

### 012 — docs(roadmap): publish engineering backlog

- **Status** · Completed in bootstrap
- **Purpose** · Let contributors pick independent work.
- **Implementation scope and acceptance criteria** · Track 103 actionable items and their prerequisites.
- **Expected files or modules** · `docs/PR-ROADMAP.md`
- **Dependencies** · 011
- **Required tests** · Validate numbering and dependency order.
- **Suggested labels** · `foundation`, `documentation`
- **Size** · S

## Phase B — Package foundation and quality tooling (013–023)

### 013 — build(composer): configure the library

- **Status** · Completed in bootstrap
- **Purpose** · Make the checkout a consumable Composer library.
- **Implementation scope and acceptance criteria** · PHP 8.3 floor, support metadata, and PSR-4 pass strict validation.
- **Expected files or modules** · `composer.json`
- **Dependencies** · 002
- **Required tests** · composer validate --strict.
- **Suggested labels** · `tooling`
- **Size** · S

### 014 — feat(core): introduce package exception

- **Status** · Completed in bootstrap
- **Purpose** · Give package callers a common failure base.
- **Implementation scope and acceptance criteria** · A Composer consumer can throw and catch the package exception.
- **Expected files or modules** · `src/Exceptions`
- **Dependencies** · 013
- **Required tests** · Run autoload smoke test.
- **Suggested labels** · `tooling`
- **Size** · S

### 015 — test(phpunit): configure test suites

- **Status** · Completed in bootstrap
- **Purpose** · Provide a repeatable test entry point.
- **Implementation scope and acceptance criteria** · PHPUnit discovers unit and integration suites with Composer bootstrap.
- **Expected files or modules** · `phpunit.xml.dist`, `tests/Unit`
- **Dependencies** · 014
- **Required tests** · composer test.
- **Suggested labels** · `tooling`
- **Size** · S

### 016 — test(fixtures): add synthetic record smoke test

- **Status** · Completed in bootstrap
- **Purpose** · Exercise file-based integration with synthetic data.
- **Implementation scope and acceptance criteria** · Fixture loads offline with an unchanged string original ID.
- **Expected files or modules** · `tests/Fixtures`, `tests/Integration`
- **Dependencies** · 015
- **Required tests** · Run the integration suite.
- **Suggested labels** · `tooling`
- **Size** · S

### 017 — build(analysis): configure PHPStan

- **Status** · Completed in bootstrap
- **Purpose** · Catch typing mistakes and unsupported PHP APIs.
- **Implementation scope and acceptance criteria** · Level 8 checks source and tests against the PHP floor.
- **Expected files or modules** · `phpstan.neon`
- **Dependencies** · 015, 016
- **Required tests** · composer analyse.
- **Suggested labels** · `tooling`
- **Size** · S

### 018 — style(php): configure PSR-12 checks

- **Status** · Completed in bootstrap
- **Purpose** · Apply a common source style.
- **Implementation scope and acceptance criteria** · Check and fix commands target package and test code.
- **Expected files or modules** · `composer.json`, `.editorconfig`
- **Dependencies** · 015
- **Required tests** · composer style.
- **Suggested labels** · `tooling`
- **Size** · S

### 019 — build(deps): lock contributor dependencies

- **Status** · Completed in bootstrap
- **Purpose** · Reproduce the development environment.
- **Implementation scope and acceptance criteria** · Stable tools resolve against PHP 8.3 and install reproducibly.
- **Expected files or modules** · `composer.lock`, `docs/CONTRIBUTOR-GUIDE.md`
- **Dependencies** · 017, 018
- **Required tests** · composer install.
- **Suggested labels** · `tooling`
- **Size** · M

### 020 — build(verification): add aggregate quality gate

- **Status** · Completed in bootstrap
- **Purpose** · Catch regressions in one command.
- **Implementation scope and acceptance criteria** · A failing platform, test, analysis or style check fails verification.
- **Expected files or modules** · `composer.json`
- **Dependencies** · 019
- **Required tests** · composer verify.
- **Suggested labels** · `tooling`
- **Size** · M

### 021 — ci(quality): add supported PHP matrix

- **Status** · Completed in bootstrap
- **Purpose** · Catch version-specific failures.
- **Implementation scope and acceptance criteria** · Each configured PHP version installs dependencies and runs verification.
- **Expected files or modules** · `.github/workflows/ci.yml`
- **Dependencies** · 020
- **Required tests** · Check matrix and first hosted run.
- **Suggested labels** · `tooling`
- **Size** · M

### 022 — ci(dependencies): configure automated updates

- **Status** · Completed in bootstrap
- **Purpose** · Track tool and action updates.
- **Implementation scope and acceptance criteria** · Schedule updates for Composer and GitHub Actions.
- **Expected files or modules** · `.github/dependabot.yml`
- **Dependencies** · 019, 021
- **Required tests** · Parse update YAML.
- **Suggested labels** · `tooling`
- **Size** · M

### 023 — build(distribution): exclude development files

- **Status** · Completed in bootstrap
- **Purpose** · Keep source distributions focused.
- **Implementation scope and acceptance criteria** · Source and license are distributable while vendor and caches are ignored.
- **Expected files or modules** · `.gitattributes`, `.gitignore`
- **Dependencies** · 016
- **Required tests** · Inspect git archive.
- **Suggested labels** · `tooling`
- **Size** · M

## Phase C — Architecture and public specifications (024–035)

### 024 — docs(architecture): define module boundaries

- **Status** · Completed in bootstrap
- **Purpose** · Prevent coupling to provider implementations.
- **Implementation scope and acceptance criteria** · Core does not import provider code or retain whole datasets.
- **Expected files or modules** · `docs/ARCHITECTURE.md`
- **Dependencies** · 001
- **Required tests** · Trace data flow in diagram.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 025 — docs(archive): define directory format

- **Status** · Completed in bootstrap
- **Purpose** · Give other languages a format to implement.
- **Implementation scope and acceptance criteria** · Metadata, encoding, checksums and safe paths are defined.
- **Expected files or modules** · `docs/ARCHIVE-SPEC.md`
- **Dependencies** · 024
- **Required tests** · Walk a draft directory example.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 026 — docs(archive): define coverage and identity

- **Status** · Completed in bootstrap
- **Purpose** · Keep lost and partial data visible.
- **Implementation scope and acceptance criteria** · Partial exports preserve original IDs and unsupported field context.
- **Expected files or modules** · `docs/ARCHIVE-SPEC.md`
- **Dependencies** · 025
- **Required tests** · Review a partial export example.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 027 — docs(connectors): define capability contracts

- **Status** · Completed in bootstrap
- **Purpose** · Allow independent source and destination implementations.
- **Implementation scope and acceptance criteria** · Source-only adapters have no write obligation.
- **Expected files or modules** · `docs/CONNECTOR-SPEC.md`
- **Dependencies** · 024
- **Required tests** · Review three adapter capability combinations.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 028 — docs(mapping): define conversion outcomes

- **Status** · Completed in bootstrap
- **Purpose** · Prevent accidental clearing and lossy transformation.
- **Implementation scope and acceptance criteria** · Omitted values, explicit null and lossy casts have separate behavior.
- **Expected files or modules** · `docs/ARCHITECTURE.md`
- **Dependencies** · 026, 027
- **Required tests** · Walk omitted and cleared fields.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 029 — docs(migration): define safe execution

- **Status** · Completed in bootstrap
- **Purpose** · Make interruption and recovery predictable.
- **Implementation scope and acceptance criteria** · Dry runs avoid writes and ambiguous outcomes have a stop condition.
- **Expected files or modules** · `docs/ARCHITECTURE.md`
- **Dependencies** · 027, 028
- **Required tests** · Walk an interruption scenario.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 030 — docs(verification): define coverage semantics

- **Status** · Completed in bootstrap
- **Purpose** · Define what verification can substantiate.
- **Implementation scope and acceptance criteria** · Byte integrity and target comparability are independently reported.
- **Expected files or modules** · `docs/ARCHITECTURE.md`
- **Dependencies** · 026, 028
- **Required tests** · Walk an intact export with partial coverage.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 031 — docs(archive): specify canonical scalar syntax

- **Status** · Planned
- **Purpose** · Prevent readers from disagreeing on scalar values.
- **Implementation scope and acceptance criteria** · Numeric strings, timestamp precision and unknown keys have explicit rules.
- **Expected files or modules** · `docs/ARCHIVE-SPEC.md`
- **Dependencies** · 025, 026
- **Required tests** · Table accepted and rejected lexical vectors.
- **Suggested labels** · `specification`, `documentation`
- **Size** · S

### 032 — docs(archive): add annotated format examples

- **Status** · Planned
- **Purpose** · Give independent readers concrete reference inputs.
- **Implementation scope and acceptance criteria** · Examples include three metadata documents and empty and partial entities.
- **Expected files or modules** · `docs/ARCHIVE-SPEC.md`, `tests/Fixtures/Archive`
- **Dependencies** · 026, 031
- **Required tests** · Manually validate cross-file references.
- **Suggested labels** · `specification`, `documentation`, `good first issue`
- **Size** · S

### 033 — docs(connectors): define cursor lifetime

- **Status** · Planned
- **Purpose** · Clarify when suspended exports can resume.
- **Implementation scope and acceptance criteria** · Specify cursor ownership, expiration and snapshot resume conditions.
- **Expected files or modules** · `docs/CONNECTOR-SPEC.md`
- **Dependencies** · 027
- **Required tests** · Review an expired snapshot scenario.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 034 — docs(migration): specify durable state transitions

- **Status** · Planned
- **Purpose** · Give store implementations a consistent recovery model.
- **Implementation scope and acceptance criteria** · Each write boundary has a defined recovery transition with revision checks.
- **Expected files or modules** · `docs/ARCHITECTURE.md`
- **Dependencies** · 029
- **Required tests** · Walk crash cases before and after commits.
- **Suggested labels** · `specification`, `documentation`
- **Size** · M

### 035 — docs(api): define compatibility policy

- **Status** · Planned
- **Purpose** · Set expectations for changes to public interfaces.
- **Implementation scope and acceptance criteria** · Document internal APIs and independent archive version changes.
- **Expected files or modules** · `docs/ARCHITECTURE.md`, `CHANGELOG.md`
- **Dependencies** · 024, 025
- **Required tests** · Classify example signature and format changes.
- **Suggested labels** · `specification`, `documentation`, `good first issue`
- **Size** · S

## Phase D — Portable archive implementation (036–048)

### 036 — feat(archive): validate archive paths

- **Status** · Planned
- **Purpose** · Contain reads and writes within the archive root.
- **Implementation scope and acceptance criteria** · Reject traversal, reserved names, symlinks and duplicate paths before file access.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 025
- **Required tests** · Test path and filesystem edge cases.
- **Suggested labels** · `archive`
- **Size** · M

### 037 — feat(archive): decode bounded JSON objects

- **Status** · Planned
- **Purpose** · Reject ambiguous or oversized metadata.
- **Implementation scope and acceptance criteria** · Reject duplicate decoded keys, invalid UTF-8 and excessive depth.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 031
- **Required tests** · Test escaped duplicate keys and exceeded bounds.
- **Suggested labels** · `archive`
- **Size** · L

### 038 — feat(archive): parse manifest descriptors

- **Status** · Planned
- **Purpose** · Gate file access on valid archive metadata.
- **Implementation scope and acceptance criteria** · Validate version, counts, required descriptors and limits before opening streams.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 032, 036, 037
- **Required tests** · Test unsupported versions and duplicate entries.
- **Suggested labels** · `archive`
- **Size** · M

### 039 — feat(archive): decode schema metadata

- **Status** · Planned
- **Purpose** · Tie archived records to explicit schemas.
- **Implementation scope and acceptance criteria** · Validate field kinds and references across declared entities.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 032, 037
- **Required tests** · Test duplicate names and dangling schema references.
- **Suggested labels** · `archive`
- **Size** · M

### 040 — feat(archive): decode coverage metadata

- **Status** · Planned
- **Purpose** · Preserve unsupported data information.
- **Implementation scope and acceptance criteria** · Preserve partial and unsupported entities and validate excluded fields.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 032, 039
- **Required tests** · Test malformed report reasons and exclusions.
- **Suggested labels** · `archive`
- **Size** · M

### 041 — feat(archive): read bounded JSONL records

- **Status** · Planned
- **Purpose** · Keep record reads bounded in memory.
- **Implementation scope and acceptance criteria** · Yield validated envelopes while enforcing line bounds and preserving missing values.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 037, 039
- **Required tests** · Test oversized lines and missing final LF.
- **Suggested labels** · `archive`
- **Size** · M

### 042 — feat(archive): write JSONL incrementally

- **Status** · Planned
- **Purpose** · Keep record writes bounded in memory.
- **Implementation scope and acceptance criteria** · Emit records without buffering the dataset and detect incomplete writes.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 041
- **Required tests** · Test round trips and short writes.
- **Suggested labels** · `archive`
- **Size** · M

### 043 — feat(archive): compute streamed file integrity

- **Status** · Planned
- **Purpose** · Make byte changes detectable.
- **Implementation scope and acceptance criteria** · Incrementally produce exact byte counts and SHA-256 digests.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 038, 042
- **Required tests** · Compare different chunk sizes and truncated files.
- **Suggested labels** · `archive`
- **Size** · M

### 044 — feat(archive): encode portable metadata

- **Status** · Planned
- **Purpose** · Make file metadata interoperable.
- **Implementation scope and acceptance criteria** · Serialize stable metadata readable under the documented limits.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 038, 039, 040, 043
- **Required tests** · Test repeat encoding with fixed clock and UUID.
- **Suggested labels** · `archive`
- **Size** · M

### 045 — feat(archive): publish completed directories

- **Status** · Planned
- **Purpose** · Hide incomplete archive builds.
- **Implementation scope and acceptance criteria** · Stage privately and atomically publish without replacing existing archives.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 036, 044
- **Required tests** · Test collisions, interrupted writes and cleanup.
- **Suggested labels** · `archive`
- **Size** · L

### 046 — feat(archive): open validated archive directories

- **Status** · Planned
- **Purpose** · Expose a safe archive reader entry point.
- **Implementation scope and acceptance criteria** · Reject unlisted or corrupted files before yielding records.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 038, 040, 041, 043
- **Required tests** · Test extra files, changed digests and empty streams.
- **Suggested labels** · `archive`
- **Size** · M

### 047 — test(archive): add format conformance vectors

- **Status** · Planned
- **Purpose** · Give independent readers reusable format vectors.
- **Implementation scope and acceptance criteria** · Supply reusable positive and negative archives with expected verdicts.
- **Expected files or modules** · `tests/Fixtures/Archive`, `tests/Integration`
- **Dependencies** · 045, 046
- **Required tests** · Run vectors against both codecs.
- **Suggested labels** · `archive`
- **Size** · M

### 048 — feat(archive): assemble portable archives from streams

- **Status** · Planned
- **Purpose** · Expose an end-to-end archive writer.
- **Implementation scope and acceptance criteria** · Publish and reopen a synthetic multi-entity export through one entry point.
- **Expected files or modules** · `src/Archive`
- **Dependencies** · 042, 045, 046, 047
- **Required tests** · Test failed iteration and multi-entity round trip.
- **Suggested labels** · `archive`
- **Size** · L

## Phase E — Connector SDK and contracts (049–060)

### 049 — feat(contracts): define connector schema discovery

- **Status** · Implemented on main
- **Purpose** · Give providers a common discovery vocabulary.
- **Implementation scope and acceptance criteria** · Add immutable SchemaDiscovery, ConnectorSchema, EntitySchema, FieldSchema and field kind enum with unique nonempty keys.
- **Expected files or modules** · `src/Contracts`, `tests/Unit/Contracts`
- **Dependencies** · 014, 020, 027
- **Required tests** · Test source-only discovery, duplicate keys, and independent nullable and required flags.
- **Suggested labels** · `connectors`
- **Size** · M

### 050 — feat(contracts): define paged record reads

- **Status** · Partially implemented
- **Purpose** · Fetch records without accumulating a dataset.
- **Implementation scope and acceptance criteria** · Expose RecordReader with bounded pages, opaque cursors and string IDs.
- **Expected files or modules** · `src/Contracts`
- **Dependencies** · 033, 049
- **Required tests** · Test terminating and empty advancing pages.
- **Suggested labels** · `connectors`
- **Size** · M

### 051 — feat(contracts): define independent batch writes

- **Status** · Planned
- **Purpose** · Write records without requiring reads.
- **Implementation scope and acceptance criteria** · Represent success, rejection and uncertainty per stable operation key.
- **Expected files or modules** · `src/Contracts`
- **Dependencies** · 049
- **Required tests** · Test duplicate keys and success without destination ID.
- **Suggested labels** · `connectors`
- **Size** · M

### 052 — feat(contracts): classify adapter failures

- **Status** · Planned
- **Purpose** · Give callers enough context to decide whether to retry.
- **Implementation scope and acceptance criteria** · Expose safe classifications and optional Retry-After without transport details.
- **Expected files or modules** · `src/Contracts`, `src/Exceptions`
- **Dependencies** · 050, 051
- **Required tests** · Test code and message redaction.
- **Suggested labels** · `connectors`
- **Size** · M

### 053 — feat(connectors): declare capabilities and limits

- **Status** · Planned
- **Purpose** · Prevent calling unsupported provider behavior.
- **Implementation scope and acceptance criteria** · Expose read limits, write guarantees and cursor resumability separately.
- **Expected files or modules** · `src/Contracts`
- **Dependencies** · 050, 051, 052
- **Required tests** · Test source, destination and bidirectional declarations.
- **Suggested labels** · `connectors`
- **Size** · M

### 054 — feat(connectors): define reconciliation lookup

- **Status** · Planned
- **Purpose** · Recover safely from ambiguous writes.
- **Implementation scope and acceptance criteria** · Use stable keys to distinguish found, absent and inconclusive writes.
- **Expected files or modules** · `src/Contracts`
- **Dependencies** · 051, 053
- **Required tests** · Test allowed lookup outcomes.
- **Suggested labels** · `connectors`
- **Size** · M

### 055 — feat(core): iterate connector pages safely

- **Status** · Planned
- **Purpose** · Avoid pagination loops.
- **Implementation scope and acceptance criteria** · Enforce page bounds and detect cursor cycles using injected storage.
- **Expected files or modules** · `src/Core`
- **Dependencies** · 050, 052, 053
- **Required tests** · Test early termination, cycles and empty pages.
- **Suggested labels** · `connectors`
- **Size** · L

### 056 — feat(core): encode scoped record identities

- **Status** · Planned
- **Purpose** · Preserve original IDs across provider scopes.
- **Implementation scope and acceptance criteria** · Source, entity and original ID tuples round-trip without collision.
- **Expected files or modules** · `src/Contracts`
- **Dependencies** · 050
- **Required tests** · Test delimiters and leading zeros.
- **Suggested labels** · `connectors`
- **Size** · M

### 057 — test(connectors): add reference adapters

- **Status** · Planned
- **Purpose** · Give adapter authors repeatable examples.
- **Implementation scope and acceptance criteria** · Provide configurable credential-free readers and writers.
- **Expected files or modules** · `tests/Fixtures`, `tests/Integration`
- **Dependencies** · 051, 053, 054, 056
- **Required tests** · Exercise empty pages and uncertain outcomes.
- **Suggested labels** · `connectors`
- **Size** · M

### 058 — test(connectors): build reusable contract assertions

- **Status** · Planned
- **Purpose** · Catch provider contract violations consistently.
- **Implementation scope and acceptance criteria** · Reusable suites cover discovery, pagination and keyed writes independently.
- **Expected files or modules** · `tests/Integration/Contracts`
- **Dependencies** · 052, 053, 057
- **Required tests** · Run good and intentionally malformed fake adapters.
- **Suggested labels** · `connectors`
- **Size** · M

### 059 — feat(core): export connector pages to archives

- **Status** · Planned
- **Purpose** · Deliver the first complete credential-free export.
- **Implementation scope and acceptance criteria** · A source-only connector yields a valid archive and explicit coverage.
- **Expected files or modules** · `src/Core`
- **Dependencies** · 048, 055, 056, 058
- **Required tests** · Test multiple entities and partial source failure.
- **Suggested labels** · `connectors`
- **Size** · L

### 060 — docs(connectors): publish an offline adapter tutorial

- **Status** · Planned
- **Purpose** · Help contributors build independent adapters.
- **Implementation scope and acceptance criteria** · A newcomer can build and run a source-only example.
- **Expected files or modules** · `docs/CONNECTOR-SPEC.md`, `tests/Integration`
- **Dependencies** · 059
- **Required tests** · Execute tutorial against synthetic fixtures.
- **Suggested labels** · `connectors`, `documentation`
- **Size** · S

## Phase F — Schema discovery and mapping (061–070)

### 061 — feat(schema): model reference targets

- **Status** · Planned
- **Purpose** · Expose dependencies encoded in schemas.
- **Implementation scope and acceptance criteria** · Validate native and declared external entity references.
- **Expected files or modules** · `src/Contracts`
- **Dependencies** · 049
- **Required tests** · Test broken and self-referential schemas.
- **Suggested labels** · `mapping`
- **Size** · M

### 062 — feat(schema): fingerprint discovered schemas

- **Status** · Planned
- **Purpose** · Detect drift between planning and execution.
- **Implementation scope and acceptance criteria** · Canonical digest ignores list ordering while detecting semantic changes.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 061
- **Required tests** · Test reorder and field flag drift.
- **Suggested labels** · `mapping`
- **Size** · M

### 063 — feat(mapping): define explicit mapping rules

- **Status** · Planned
- **Purpose** · Represent contributor-selected field behavior.
- **Implementation scope and acceptance criteria** · Immutable rules distinguish default, ignore, and explicit null.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 028, 061
- **Required tests** · Test conflicting rules and null defaults.
- **Suggested labels** · `mapping`
- **Size** · M

### 064 — feat(mapping): compile rules against schemas

- **Status** · Planned
- **Purpose** · Surface invalid mappings early.
- **Implementation scope and acceptance criteria** · Report unknown fields and unsatisfied required targets with schema fingerprints.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 062, 063
- **Required tests** · Test rejected fields and schema drift.
- **Suggested labels** · `mapping`
- **Size** · M

### 065 — feat(mapping): convert scalar fields exactly

- **Status** · Planned
- **Purpose** · Preserve numeric values and precision.
- **Implementation scope and acceptance criteria** · Decimal and integer conversions preserve precision and require consent for loss.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 064
- **Required tests** · Test large values and rejected casts.
- **Suggested labels** · `mapping`
- **Size** · M

### 066 — feat(mapping): normalize timestamp precision

- **Status** · Planned
- **Purpose** · Make equivalent times compare consistently.
- **Implementation scope and acceptance criteria** · Use declared timezone and precision normalization for valid RFC 3339 inputs.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 065
- **Required tests** · Test offsets, fractional seconds and invalid dates.
- **Suggested labels** · `mapping`
- **Size** · M

### 067 — feat(mapping): transform records with field outcomes

- **Status** · Planned
- **Purpose** · Preserve field presence during conversion.
- **Implementation scope and acceptance criteria** · Report omission, explicit clear and conversion failures without leaking payloads.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 065, 066
- **Required tests** · Test missing, null and defaulted fields.
- **Suggested labels** · `mapping`
- **Size** · M

### 068 — feat(mapping): translate relationship dependencies

- **Status** · Planned
- **Purpose** · Retain link targets for the migration planner.
- **Implementation scope and acceptance criteria** · Preserve reference multiplicity and map targets to scoped source identities.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 056, 061, 067
- **Required tests** · Test multi-target, null and unsupported references.
- **Suggested labels** · `mapping`
- **Size** · M

### 069 — feat(mapping): suggest unambiguous matches

- **Status** · Planned
- **Purpose** · Reduce manual selection for simple mappings.
- **Implementation scope and acceptance criteria** · Offer compatible key matches and report ambiguity without applying results.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 064
- **Required tests** · Test competing and incompatible candidates.
- **Suggested labels** · `mapping`
- **Size** · M

### 070 — feat(mapping): aggregate mapping coverage

- **Status** · Planned
- **Purpose** · Show what transformation will omit.
- **Implementation scope and acceptance criteria** · Count exclusions and failures with fixed-size accumulators per field.
- **Expected files or modules** · `src/Mapping`
- **Dependencies** · 067, 068
- **Required tests** · Test mixed and empty input.
- **Suggested labels** · `mapping`
- **Size** · M

## Phase G — Migration execution and reliability (071–082)

### 071 — feat(migration): build immutable plan identities

- **Status** · Planned
- **Purpose** · Protect execution from stale plans.
- **Implementation scope and acceptance criteria** · Bind plans to archive, schemas, mapping and destination scope.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 034, 046, 062, 070
- **Required tests** · Test stable hashes and changed inputs.
- **Suggested labels** · `migration`
- **Size** · M

### 072 — feat(migration): order relationship dependencies

- **Status** · Planned
- **Purpose** · Write prerequisites before dependent records.
- **Implementation scope and acceptance criteria** · Use an injected graph store to schedule prerequisites and report cycles.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 068, 071
- **Required tests** · Test chains, missing parents and cyclic graphs.
- **Suggested labels** · `migration`
- **Size** · L

### 073 — feat(migration): preview plans without writes

- **Status** · Planned
- **Purpose** · Show the effect of migration before committing data.
- **Implementation scope and acceptance criteria** · Report blocked operations and mapping gaps without constructing a writer.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 053, 070, 072
- **Required tests** · Test with a discovery-only destination.
- **Suggested labels** · `migration`
- **Size** · M

### 074 — feat(migration): define durable checkpoints

- **Status** · Planned
- **Purpose** · Resume only from known durable state.
- **Implementation scope and acceptance criteria** · Persist plan identity and revisions with compare-and-swap semantics.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 034, 071
- **Required tests** · Test stale revisions and state round trips.
- **Suggested labels** · `migration`
- **Size** · M

### 075 — feat(migration): persist source-to-target IDs

- **Status** · Planned
- **Purpose** · Resolve relationships using destination identities.
- **Implementation scope and acceptance criteria** · Commit identity changes atomically with checkpoint progress.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 056, 074
- **Required tests** · Test idempotent acknowledgement and conflicting IDs.
- **Suggested labels** · `migration`
- **Size** · M

### 076 — feat(migration): apply bounded retries

- **Status** · Planned
- **Purpose** · Recover from transient failures safely.
- **Implementation scope and acceptance criteria** · Use injected time and jitter, honor bounded Retry-After, and stop on permanent errors.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 052, 074
- **Required tests** · Test delay bounds and exhaustion.
- **Suggested labels** · `migration`
- **Size** · M

### 077 — feat(migration): journal idempotent write intents

- **Status** · Planned
- **Purpose** · Reuse stable keys across interruptions.
- **Implementation scope and acceptance criteria** · Persist operation keys before calling the destination.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 051, 074, 075
- **Required tests** · Inject crashes before and after request dispatch.
- **Suggested labels** · `migration`
- **Size** · M

### 078 — feat(migration): reconcile uncertain writes

- **Status** · Planned
- **Purpose** · Prevent duplicate records after uncertain writes.
- **Implementation scope and acceptance criteria** · Replay only when declared guarantees permit it.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 054, 076, 077
- **Required tests** · Test found, absent, inconclusive and deduplicated writes.
- **Suggested labels** · `migration`
- **Size** · L

### 079 — feat(migration): execute bounded batches

- **Status** · Planned
- **Purpose** · Process workload within adapter limits.
- **Implementation scope and acceptance criteria** · Commit per-operation outcomes and block dependents of rejected operations.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 072, 075, 078
- **Required tests** · Test mixed outcomes and backpressure.
- **Suggested labels** · `migration`
- **Size** · L

### 080 — feat(migration): resume interrupted plans

- **Status** · Planned
- **Purpose** · Continue compatible interrupted plans.
- **Implementation scope and acceptance criteria** · Validate plan identity and ownership before resolving pending writes.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 073, 074, 079
- **Required tests** · Test changed target, competing claims and restart.
- **Suggested labels** · `migration`
- **Size** · M

### 081 — feat(migration): update supported cyclic relationships

- **Status** · Planned
- **Purpose** · Migrate links that need a second pass.
- **Implementation scope and acceptance criteria** · Defer cyclic links when updates are supported and block remaining cycles.
- **Expected files or modules** · `src/Migration`
- **Dependencies** · 072, 079, 080
- **Required tests** · Test self-references and interrupted second pass.
- **Suggested labels** · `migration`
- **Size** · L

### 082 — test(migration): exercise end-to-end recovery

- **Status** · Planned
- **Purpose** · Prove recovery across integrated components.
- **Implementation scope and acceptance criteria** · Synthetic export and resumed import write each expected record once.
- **Expected files or modules** · `tests/Integration/Migration`
- **Dependencies** · 059, 073, 080, 081
- **Required tests** · Inject faults across persistence boundaries.
- **Suggested labels** · `migration`
- **Size** · L

## Phase H — Verification and diagnostics (083–092)

### 083 — feat(verification): report archive byte integrity

- **Status** · Planned
- **Purpose** · Make file corruption diagnosable.
- **Implementation scope and acceptance criteria** · Report unreadable, missing and mismatched entries with safe codes.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 046
- **Required tests** · Test truncation, altered bytes and deleted metadata.
- **Suggested labels** · `verification`
- **Size** · M

### 084 — feat(verification): validate IDs and record counts

- **Status** · Planned
- **Purpose** · Detect incomplete or duplicated exports.
- **Implementation scope and acceptance criteria** · Use an injected index to identify duplicates and count discrepancies.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 056, 083
- **Required tests** · Test cross-page duplicates and distinct entity scopes.
- **Suggested labels** · `verification`
- **Size** · M

### 085 — feat(verification): verify archived relationships

- **Status** · Planned
- **Purpose** · Show which captured links can be resolved.
- **Implementation scope and acceptance criteria** · Distinguish dangling records from targets outside export coverage.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 040, 084
- **Required tests** · Test partial, unsupported and invalid references.
- **Suggested labels** · `verification`
- **Size** · M

### 086 — feat(verification): declare comparable fields

- **Status** · Planned
- **Purpose** · Expose limits of cross-provider comparison.
- **Implementation scope and acceptance criteria** · Derive comparability from mapping and available target reads.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 070, 085
- **Required tests** · Test lossy mapping and unreadable fields.
- **Suggested labels** · `verification`
- **Size** · M

### 087 — feat(verification): compare normalized values

- **Status** · Planned
- **Purpose** · Detect meaningful field changes.
- **Implementation scope and acceptance criteria** · Compare declared numeric and temporal types while preserving null and missing distinctions.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 065, 066, 086
- **Required tests** · Test decimals, time offsets and nulls.
- **Suggested labels** · `verification`
- **Size** · M

### 088 — feat(verification): compare migrated relationships

- **Status** · Planned
- **Purpose** · Check that destination links survived migration.
- **Implementation scope and acceptance criteria** · Translate links using committed identities before comparison.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 075, 085, 087
- **Required tests** · Test unordered links, missing targets and cardinality.
- **Suggested labels** · `verification`
- **Size** · M

### 089 — feat(verification): aggregate result coverage

- **Status** · Planned
- **Purpose** · Communicate verification coverage.
- **Implementation scope and acceptance criteria** · Reconcile verified, mismatched, unsupported, failed and unknown counts.
- **Expected files or modules** · `src/Verification`
- **Dependencies** · 087, 088
- **Required tests** · Test mixed results and empty streams.
- **Suggested labels** · `verification`
- **Size** · M

### 090 — feat(diagnostics): expose redacted execution events

- **Status** · Planned
- **Purpose** · Support observability without sensitive data.
- **Implementation scope and acceptance criteria** · Publish bounded event context without cursors, credentials or payloads.
- **Expected files or modules** · `src/Migration`, `src/Verification`
- **Dependencies** · 080, 089
- **Required tests** · Assert sensitive markers never appear.
- **Suggested labels** · `verification`
- **Size** · M

### 091 — feat(verification): serialize versioned JSON reports

- **Status** · Planned
- **Purpose** · Allow verification consumers outside PHP.
- **Implementation scope and acceptance criteria** · Independent consumers can parse versioned summaries and stable codes.
- **Expected files or modules** · `src/Verification`, `docs`
- **Dependencies** · 089, 090
- **Required tests** · Compare golden reports and parse with a generic decoder.
- **Suggested labels** · `verification`
- **Size** · M

### 092 — test(verification): cover cross-module outcomes

- **Status** · Planned
- **Purpose** · Check migration and verification agree.
- **Implementation scope and acceptance criteria** · Synthetic pipeline distinguishes damaged archives from target mismatches.
- **Expected files or modules** · `tests/Integration/Verification`
- **Dependencies** · 082, 091
- **Required tests** · Test partial coverage, tampering and divergent destination.
- **Suggested labels** · `verification`
- **Size** · M

## Phase I — Zoho and Twenty integrations (093–100)

### 093 — feat(zoho): discover CRM schemas offline

- **Status** · Planned
- **Purpose** · Start offline Zoho adapter development.
- **Implementation scope and acceptance criteria** · Translate provider metadata through injected transport and report unsupported kinds.
- **Expected files or modules** · `src/Connectors/Zoho`, `tests/Fixtures/Zoho`
- **Dependencies** · 058, 061
- **Required tests** · Test malformed and custom metadata.
- **Suggested labels** · `integrations`
- **Size** · L

### 094 — feat(zoho): read paged CRM records

- **Status** · Planned
- **Purpose** · Enable streamed Zoho exports.
- **Implementation scope and acceptance criteria** · Translate pagination and throttling and preserve observed coverage.
- **Expected files or modules** · `src/Connectors/Zoho`
- **Dependencies** · 059, 093
- **Required tests** · Test cursor expiry and rate limits.
- **Suggested labels** · `integrations`
- **Size** · M

### 095 — feat(zoho): write supported CRM records

- **Status** · Planned
- **Purpose** · Support bounded Zoho imports.
- **Implementation scope and acceptance criteria** · Translate individual successes, errors and uncertain outcomes.
- **Expected files or modules** · `src/Connectors/Zoho`
- **Dependencies** · 080, 093
- **Required tests** · Test response loss and partial success.
- **Suggested labels** · `integrations`
- **Size** · L

### 096 — docs(zoho): record pilot capabilities and limitations

- **Status** · Planned
- **Purpose** · Make pilot scope clear to contributors.
- **Implementation scope and acceptance criteria** · Document supported fields, region and scopes with a synthetic pipeline.
- **Expected files or modules** · `docs`, `tests/Integration/Zoho`
- **Dependencies** · 092, 094, 095
- **Required tests** · Run adapter contracts and pilot fixtures.
- **Suggested labels** · `integrations`, `documentation`
- **Size** · M

### 097 — feat(twenty): discover CRM schemas offline

- **Status** · Planned
- **Purpose** · Start offline Twenty adapter development.
- **Implementation scope and acceptance criteria** · Map selected API metadata and document version assumptions.
- **Expected files or modules** · `src/Connectors/Twenty`, `tests/Fixtures/Twenty`
- **Dependencies** · 058, 061
- **Required tests** · Test default, custom and unsupported kinds.
- **Suggested labels** · `integrations`
- **Size** · L

### 098 — feat(twenty): stream CRM records and links

- **Status** · Planned
- **Purpose** · Enable streamed Twenty exports.
- **Implementation scope and acceptance criteria** · Translate cursor-based pages while preserving scoped relationships.
- **Expected files or modules** · `src/Connectors/Twenty`
- **Dependencies** · 059, 097
- **Required tests** · Test throttling, empty pages and malformed data.
- **Suggested labels** · `integrations`
- **Size** · M

### 099 — feat(twenty): write mapped CRM records

- **Status** · Planned
- **Purpose** · Support bounded Twenty imports.
- **Implementation scope and acceptance criteria** · Expose partial outcomes and declare only demonstrated replay guarantees.
- **Expected files or modules** · `src/Connectors/Twenty`
- **Dependencies** · 080, 097
- **Required tests** · Test ambiguous response and failed reconciliation.
- **Suggested labels** · `integrations`
- **Size** · L

### 100 — docs(integrations): describe cross-provider pilot limits

- **Status** · Planned
- **Purpose** · Demonstrate the supported workflow across both providers.
- **Implementation scope and acceptance criteria** · Document a synthetic Zoho-to-Twenty migration and explain omissions.
- **Expected files or modules** · `docs`, `tests/Integration`
- **Dependencies** · 092, 096, 098, 099
- **Required tests** · Run export, migration and verification end to end.
- **Suggested labels** · `integrations`, `documentation`
- **Size** · L

## Phase J — Release readiness and distribution (101–103)

### 101 — build(release): verify source distribution

- **Status** · Planned
- **Purpose** · Test the distribution as a consumer receives it.
- **Implementation scope and acceptance criteria** · A downstream local VCS consumer autoloads package code without dev dependencies.
- **Expected files or modules** · `composer.json`, `.gitattributes`, `tests/Integration`
- **Dependencies** · 023, 035, 100
- **Required tests** · Test production install at PHP floor.
- **Suggested labels** · `release`
- **Size** · M

### 102 — docs(release): prepare pre-alpha release criteria

- **Status** · Planned
- **Purpose** · Make release decisions repeatable.
- **Implementation scope and acceptance criteria** · Define version, compatibility, security contacts, release gates and rollback.
- **Expected files or modules** · `CHANGELOG.md`, `SECURITY.md`, `docs/ROADMAP.md`
- **Dependencies** · 101
- **Required tests** · Walk a dry release review.
- **Suggested labels** · `release`, `documentation`
- **Size** · S

### 103 — build(release): document Packagist distribution

- **Status** · Planned
- **Purpose** · Prepare manual package publication.
- **Implementation scope and acceptance criteria** · Explain ownership checks, annotated tags, submission and update verification.
- **Expected files or modules** · `docs/GITHUB-SETUP.md`, `docs/ROADMAP.md`
- **Dependencies** · 102
- **Required tests** · Rehearse a local tag and check package-name metadata.
- **Suggested labels** · `release`
- **Size** · S
