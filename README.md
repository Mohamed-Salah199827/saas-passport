# SaaS Passport

**Your data. Your rules. Your freedom.**

SaaS Passport is a pre-alpha, framework-agnostic PHP toolkit for SaaS data portability. SaaS platforms often expose different export formats, field types, and relationship models. A raw dump can preserve bytes while leaving teams without enough context to move or verify their data.

The planned toolkit will discover schemas, stream records into portable archives, map fields, plan migrations, and report what was verified. Zoho CRM and Twenty CRM are the first planned connector targets. The core will run in PHP without a framework, database, or service dependency. Hosted dashboards, automatic semantic matching, and perfect equivalence between platforms are outside the scope.

## Status and architecture

This unpublished pre-alpha checkout supports schema discovery, paged export to a directory archive, byte integrity checks, explicit scalar field mapping, dry-run counts, and scalar import through a caller-supplied idempotent writer. The integration test exercises this workflow with synthetic records.

Relationship values can be exported, but scalar import rejects mapped records with relationship data. The verifier checks declared files, digests, JSONL envelopes, and record counts. A destination can expose comparable scalar values through `DestinationLookup`. The comparison reports verified, mismatched, and unverified records. Full archive conformance, durable checkpoints, type conversions, and Zoho and Twenty connectors are planned.

Implement the source discovery and pagination methods in `SaaSPassport\Core\Source`. Call `ArchiveExporter::export()` with a new path inside an existing, privately controlled directory. Use `ArchiveImport::preview()` before passing a writer to `execute()`. Use a writer that persists idempotency keys within the destination scope. Keep the archive immutable during verification and import. The scalar importer checks up to 100,000 record IDs and 8 MiB of ID text per mapped entity to detect duplicates before writing. [Architecture](docs/ARCHITECTURE.md) describes their dependencies and public design.

## Scalar migration example

The application supplies a `Source` and an idempotent `RecordWriter`. Resolve `$archive` to a new directory under an existing private workspace.

```php
use SaaSPassport\Archive\ArchiveExporter;
use SaaSPassport\Mapping\FieldMapping;
use SaaSPassport\Migration\ArchiveImport;

(new ArchiveExporter())->export($source, $archive, 'example', 'workspace-a');

$mapping = ['contacts' => new FieldMapping('people', ['name' => 'fullName'])];
$import = new ArchiveImport();
$planned = $import->preview($archive, $mapping);

// After reviewing $planned:
$acknowledged = $import->execute($archive, $mapping, $writer);
```

Replaying the same archive and mapping generates the same operation keys. The destination writer owns durable deduplication. A changed archive or mapped payload can generate different keys.

## Local development

Use PHP 8.3 or later within the PHP 8 series, Composer 2, and Git. Development tools need DOM, libxml, mbstring, SimpleXML, tokenizer, XML, and XMLWriter. Enable the extensions in your CLI PHP configuration.

```sh
git clone https://github.com/Mohamed-Salah199827/saas-passport.git
cd saas-passport
composer install
composer verify
```

Run `composer test`, `composer analyse`, or `composer style` individually. Use `composer style:fix` to apply supported style fixes and review the diff.

See the [milestones](docs/ROADMAP.md), [103-item backlog](docs/PR-ROADMAP.md), and [contributor guide](docs/CONTRIBUTOR-GUIDE.md). Follow the [security policy](SECURITY.md) for private disclosures. SaaS Passport is licensed under [Apache-2.0](LICENSE).
