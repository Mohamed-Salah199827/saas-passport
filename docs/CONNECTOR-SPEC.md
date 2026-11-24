# Connector specification

Status: draft for the first Core implementation. Schema discovery and paged reading are implemented. The current scalar import uses a single-record idempotent writer. The batch interface below describes the planned contract. No provider connector is implemented.

## Capabilities and signatures

A source implements `SchemaDiscovery` and `RecordReader`. A destination implements `SchemaDiscovery` and `RecordWriter`. A bidirectional adapter implements both read and write interfaces. Optional capabilities are expressed as separate interfaces when a consumer needs them. A read-only adapter has no write obligation.

```php
interface SchemaDiscovery
{
    public function discoverSchema(): ConnectorSchema;
}

interface RecordReader
{
    public function readPage(ReadRequest $request): RecordPage;
}

interface RecordWriter
{
    public function writeBatch(WriteBatch $batch): WriteBatchResult;
}
```

All types live under `SaaSPassport\Contracts` initially. The implemented discovery values also support reference targets within the same connector schema. Use immutable `ConnectorSchema`, `EntitySchema`, and `FieldSchema` values with list-shaped collections and case-sensitive stable string keys. Entity and field keys must be nonempty and unique in their respective scopes. Fields expose `key`, `type`, `nullable`, and `required` independently. The initial field kinds are string, boolean, integer, decimal, datetime, reference, and opaque. Add relationship targets and schema fingerprints in their scheduled changes.

## Reading

A read request supplies an entity key, positive page limit, and optional opaque cursor. Null cursor starts a stream. A page supplies a bounded list of records and a nullable next cursor. Null next cursor terminates. Empty pages may advance. The orchestration rejects repeated cursors. Persist a cursor only when the adapter explicitly declares it resumable for the same scope. Keep cursors out of diagnostics. Capture snapshot or best-effort consistency and note provider limitations. Original IDs are nonempty strings and are scoped to the connector source and entity.

## Writing and failures

A batch contains bounded operations keyed by stable operation IDs. Each has a target entity, mapped fields, and an idempotency key. Return one outcome per operation with a destination ID for successful writes, a safe failure code for known failures, or an uncertain outcome when acceptance cannot be established. Describe supported operations, batch limits, and reconciliation guarantees before execution. Never infer successful writes from a transport status alone.

Classify failures as transient, rate limited, authorization, invalid request, conflict, or unknown outcome. Expose a safe code and optional Retry-After duration on classified errors. Treat unknown outcomes as non-replayable unless deduplication or reconciliation is available. Redact tokens, cursors, credentials, and payloads in user-facing errors. Inject clients and credential access at the adapter boundary. Exercise contracts with fake transports and synthetic data.
