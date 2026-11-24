# SaaS Passport

**Your data. Your rules. Your freedom.**

SaaS Passport is a pre-alpha, framework-agnostic PHP toolkit for SaaS data portability. SaaS platforms often expose different export formats, field types, and relationship models. A raw dump can preserve bytes while leaving teams without enough context to move or verify their data.

The planned toolkit will discover schemas, stream records into portable archives, map fields, plan migrations, and report what was verified. Zoho CRM and Twenty CRM are the first planned connector targets. The core will run in PHP without a framework, database, or service dependency. Hosted dashboards, automatic semantic matching, and perfect equivalence between platforms are outside the scope.

## Status and architecture

This checkout provides Composer autoloading, a base package exception, synthetic smoke tests, quality tooling, and draft specifications. Export, import, mapping, verification, and provider connectors are planned. The project is unpublished and pre-alpha.

Core orchestration will use capability-specific connector interfaces. Archive codecs, mapping, migration, and verification will be independent modules. [Architecture](docs/ARCHITECTURE.md) describes their dependencies and public design.

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
