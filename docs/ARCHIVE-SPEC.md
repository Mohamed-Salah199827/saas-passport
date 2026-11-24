# Portable archive format

Draft format 1.0. The archive root is a directory. Container formats and encryption are future extensions. All encodings and path rules apply independently of implementation language.

## Layout

```text
manifest.json
schema.json
coverage.json
records/<stream-key>.jsonl
```

Use UTF-8 without BOM throughout. JSON objects have unique property names. JSONL contains one compact JSON object per line terminated by LF, including the final record. An empty stream is a zero-byte file. Readers reject malformed UTF-8, invalid JSON and duplicate object keys. Implementations expose record, manifest, entry-count, and aggregate-byte limits.

`manifest.json` contains `formatVersion` (exactly `"1.0"`), `archiveId` (UUID), `createdAt` (UTC RFC 3339 string), `source` (`provider` and archive-local opaque `key` strings), and `files`. Each file descriptor contains `path`, `bytes` (nonnegative safe JSON integer), and `sha256` (64 lowercase hex characters over exact stored bytes). Record file descriptors also contain `entity` and `records` (nonnegative safe integer). Require one entry per path, one stream per entity, and descriptors for schema and coverage. The manifest excludes itself. Reject unsupported versions before reading data files.

`schema.json` contains `entities`, an array of objects with `key` and `fields`. Each field has a unique `key` within its entity, a `type`, and boolean `nullable` and `required` properties. Reference fields declare `targetEntity`. Entity keys are distinct, nonempty strings. Field types are string, boolean, integer, decimal, datetime, reference, and opaque.

Records use `{"id":"0001","fields":{"name":"Example Contact"},"relationships":{}}`. The stream descriptor defines the record entity. Within that entity, `id` is a unique nonempty original ID, kept as a string even when it contains only digits. `fields` stores scalar field values or null. Encode integer and decimal values as base-10 strings without exponent or leading plus. Encode datetimes as RFC 3339 strings. Opaque fields use strings. An omitted field signals missing data and an explicit null signals a captured null. `relationships` maps reference field keys to arrays of `{"entity":"companies","id":"009"}` targets or explicit null. Empty arrays represent known empty relationships. References outside captured coverage are reported as unresolved.

`coverage.json` contains `consistency` (`snapshot` or `best-effort`) and `entities`, an array with one entry per requested entity. Each has `key`, `status` (`complete`, `partial`, `unsupported`, or `failed`), and a `reasons` array of stable machine-readable codes. Record exclusions or unsupported fields with an optional `excludedFields` array of field keys. Record omissions explicitly. Attachments, binary blobs, automation rules, and provider permissions are outside format 1.0.

## Publication and trust

Allow only the three fixed metadata names and record paths matching the full pattern `records/[a-z0-9][a-z0-9_-]{0,63}\.jsonl`. Check path uniqueness before opening files. Reject symbolic links, junctions, other redirections, and non-regular entries. Work from a privately controlled staging directory and never process a directory concurrently writable by an untrusted party. Reject unlisted entries. Publish a new archive by writing into a sibling staging directory and renaming on the same filesystem after successful validation. Readers treat missing manifests as incomplete.

Verify sizes, checksums, record counts, and schema references before trusting the archive for migration. SHA-256 detects byte changes relative to a trusted manifest. Authenticity requires a trusted distribution channel or a future signature mechanism. Bound traversal and parsing independently of lengths claimed by the manifest. An archive may contain personal data. Export only explicitly selected fields, exclude credentials and provider request metadata, and protect archives through the caller's storage policy.
