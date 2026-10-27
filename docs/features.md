# Functional Features & Subsystems — Code Elta6ur Theme
> 🌐 **Language / اللغة:** [English](features.md) | [العربية](ar/features.md)


This document provides a comprehensive inventory of the functional capabilities, tools, and subsystems engineered into the custom WordPress theme.

---

## 1. Developer Tools Hub (30 Special-Purpose Web Utilities)

The platform features an integrated suite of 30 utilities built specifically for developers, engineering students, and DevOps practitioners. All tools execute purely on the client side to protect data privacy.

| # | Tool Slug | Category | Mode | Description |
| :-: | :--- | :--- | :---: | :--- |
| 1 | `json-formatter` | JSON & Data | Client | Formats, validates, and minifies complex JSON data with syntax highlighting. |
| 2 | `json-diff` | JSON & Data | Client | Visual side-by-side diffing of two JSON structures identifying mutations. |
| 3 | `json-to-code` | JSON & Data | Client | Converts JSON payloads to TypeScript interfaces, Python dataclasses, and Go structs. |
| 4 | `json-schema-validator` | JSON & Data | Client | Validates JSON against JSON Schema (Draft 7/2020-12) specifications. |
| 5 | `jsonpath-tester` | JSON & Data | Client | Evaluates JSONPath queries interactively against sample JSON payloads. |
| 6 | `yaml-json-converter` | JSON & Data | Client | Bidirectional YAML to JSON conversion with indentation and formatting controls. |
| 7 | `curl-to-code` | Network & API | Client | Converts raw cURL commands to native Python Requests, JavaScript Fetch, PHP, and Go. |
| 8 | `openapi-validator` | Network & API | Client | Validates OpenAPI 3.0/3.1 and Swagger specs for structural compliance. |
| 9 | `openapi-to-typescript` | Network & API | Client | Generates typed TypeScript clients and model interfaces from OpenAPI specifications. |
| 10 | `openapi-diff` | Network & API | Client | Identifies breaking changes and delta modifications between OpenAPI versions. |
| 11 | `jwt-inspector` | Security & Auth | Client | Decodes and inspects JWT header and payload claims; checks expiry timestamps. |
| 12 | `http-headers-analyzer` | Security & Auth | Client | Inspects HTTP response headers, diagnosing caching, CORS, and compression. |
| 13 | `security-headers-checker` | Security & Auth | Client | Evaluates site headers against OWASP security baselines (HSTS, CSP, X-Frame). |
| 14 | `csp-generator` | Security & Auth | Client | Interactive Content Security Policy (CSP) builder with directive validation. |
| 15 | `env-parser` | Security & Auth | Client | Parses, validates, and cleans `.env` files into JSON, shell, or Docker formats. |
| 16 | `regex-tester` | Text & Regex | Client | Real-time regular expression tester with regex breakdown, match groups, and flags. |
| 17 | `regex-builder` | Text & Regex | Client | Visual builder for common regex patterns (emails, URLs, phone numbers, slugs). |
| 18 | `sql-formatter` | SQL & Database | Client | Pretty-prints SQL queries across PostgreSQL, MySQL, SQLite, and T-SQL dialects. |
| 19 | `sql-to-er-diagram` | SQL & Database | Client | Parses SQL DDL statements into visual Mermaid entity diagrams. |
| 20 | `db-schema-generator` | SQL & Database | Client | Generates migration SQL schemas from visual definitions or JSON specifications. |
| 21 | `docker-compose-generator` | DevOps & Git | Client | Interactive UI to construct valid `docker-compose.yml` service definitions. |
| 22 | `dockerfile-generator` | DevOps & Git | Client | Generates optimized, multi-stage Dockerfiles tailored to specific language stacks. |
| 23 | `git-command-generator` | DevOps & Git | Client | Interactive lookup tool generating precise Git commands for complex workflows. |
| 24 | `gitignore-generator` | DevOps & Git | Client | Assembles comprehensive `.gitignore` files combining OS, IDE, and stack templates. |
| 25 | `package-json-generator` | DevOps & Git | Client | Constructs production-ready `package.json` manifests with dependencies and scripts. |
| 26 | `semver-calculator` | DevOps & Git | Client | Evaluates Semantic Versioning ranges and calculates next release bumps. |
| 27 | `htaccess-to-nginx` | DevOps & Git | Client | Translates Apache rewrite rules into Nginx server blocks. |
| 28 | `css-specificity-calculator` | CSS & Frontend | Client | Calculates CSS selector specificity scores with visual breakdown. |
| 29 | `css-clamp-generator` | CSS & Frontend | Client | Computes fluid typography formulas using the CSS `clamp()` function. |
| 30 | `css-unit-converter` | CSS & Frontend | Client | Bidirectional converter for `px`, `rem`, `em`, `vw`, `vh`, and `%` based on root sizes. |

---

## 2. Interactive Multi-Language Code Playground (15 Languages)

The theme includes an interactive programming environment integrated into lessons and tutorials:

| Language | Target Version | Execution Mode | Security Model |
| :--- | :--- | :---: | :--- |
| **Python** | Python 3.11+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **JavaScript** | Node.js LTS | Remote Sandbox | Isolated container runtime, zero host execution |
| **TypeScript** | TypeScript 5+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **C** | GCC 12+ / C17 | Remote Sandbox | Isolated container runtime, zero host execution |
| **C++** | G++ 12+ / C++20 | Remote Sandbox | Isolated container runtime, zero host execution |
| **C#** | .NET 8.0+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **Java** | OpenJDK 17+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **Go** | Go 1.21+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **Rust** | Rust 1.75+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **PHP** | PHP 8.2+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **Ruby** | Ruby 3.2+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **Kotlin** | Kotlin 1.9+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **Swift** | Swift 5.9+ | Remote Sandbox | Isolated container runtime, zero host execution |
| **SQL** | SQLite 3 / MySQL | Remote Sandbox | Isolated container runtime, zero host execution |
| **Bash** | GNU Bash 5+ | Remote Sandbox | Isolated container runtime, zero host execution |

---

## 3. Accounts, Gamification & Learning Progress Subsystem

### A. Authentication & User Management
- Native frontend account management eliminating third-party membership plugins.
- Customized dedicated views for Login, Registration, Lost Password, and Member Dashboard.
- Seamless backward compatibility with legacy user accounts stored in standard WordPress user tables.

### B. Gamification Architecture & Tables
- **Points Ledger Table:** Atomic transactional table capturing every point event. Protected by a unique idempotency constraint ensuring duplicate events cannot credit points more than once.
- **Learning Progress Table:** Tracks lesson completion per course, storing percent completion and completion timestamps.
- **User Badges Table:** Tracks earned badges. A unique index on user and badge keys prevents duplicate awards.
- **User Sessions Table:** Captures active daily engagement for streak evaluation.

### C. Active Engagement Verification
- **Anti-Fraud Tracking:** Leverages the browser's Page Visibility API and scroll depth measurements. Point rewards for reading require minimum active viewport time, preventing tab-background farming.
- **Streak Evaluation:** Evaluates daily continuous study, granting automated bonus points at 3-day, 7-day, and 30-day milestones.
- **Achievement Badges (11 Badges):** First Step, Consistent Learner, Code Explorer, Reading Champion, Course Master, Night Owl, Weekend Warrior, and milestone badges.

---

## 4. SEO Health & URL Integrity Subsystem

- **Real-Time 404 Interception:** Intercepts 404 responses, recording requested URLs, referrers, and user agents to identify broken inbound links.
- **301 Redirection Manager:** Maintains deterministic 301 mappings to preserve link equity from historical URLs.
- **Canonical URL Enforcement:** Canonical headers and meta tags generated automatically across all taxonomies and custom routes.
- **Indexation Policy:** Enforces robots directives on low-value pages, internal search queries, and paginated archives to prevent crawl budget waste.
- **Automated Schema.org JSON-LD:** Structured data injected for educational articles, tutorials, and breadcrumbs.