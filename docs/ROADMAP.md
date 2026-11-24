# Milestones

The [PR roadmap](PR-ROADMAP.md) tracks deliverables and dependencies. Milestones are acceptance gates without release dates.

| Milestone | Items | Exit criteria |
|---|---|---|
| Foundation | 001–035 | Local verification runs, governance is documented, and draft public boundaries are reviewable. |
| Portable export | 036–060 | A synthetic source produces a validated archive through the provider-independent SDK. |
| Mapping and resumable import | 061–082 | A dry run reports incompatibilities and synthetic migration resumes safely after interruption. |
| Verification | 083–092 | Integrity, comparisons, and coverage generate actionable reports. |
| Provider pilots | 093–100 | Zoho and Twenty adapters pass fixture-based contract tests and have documented limitations. |
| First pre-alpha release | 101–103 | Release candidate checks, packaging, and maintainer-operated distribution are ready. |

First implementation item: **049 — feat(contracts): define connector schema discovery**. It depends on the draft connector contract and local quality tooling. The archive work can proceed independently after its format dependencies are met.
