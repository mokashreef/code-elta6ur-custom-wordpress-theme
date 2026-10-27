# Architectural Decision Records (ADRs) — Code Elta6ur Theme
> 🌐 **Language / اللغة:** [English](engineering-decisions.md) | [العربية](ar/engineering-decisions.md)


This document records the foundational architectural decisions, context, and trade-offs made during the engineering of the custom WordPress theme.

---

## ADR-001: Zero-Framework Vanilla JS vs Client Frameworks (React/Vue/Bootstrap)

### Context
Educational platforms often suffer from slow initial page load times due to excessive JavaScript bundles (jQuery plugins, Bootstrap JS, or heavy React client runtimes), causing poor Core Web Vitals scores and degraded mobile experiences on slower networks.

### Decision
Implement 100% of the frontend visitor experience using pure Vanilla JavaScript (ES6+) and modern browser APIs.
- Use `IntersectionObserver` for lazy loading and scroll animations.
- Use the `Page Visibility API` for tracking active engagement.
- Use native `navigator.clipboard` for code snippet copying.
- Use CSS Grid, Flexbox, and CSS Custom Properties for layout and theming.

### Consequences
- **Positive:** Sub-second First Contentful Paint (FCP), zero external framework dependencies, 95+ PageSpeed scores, minimal maintenance overhead.
- **Negative:** Interactive UI components (modals, mobile drawers, search overlays) had to be built from scratch without off-the-shelf component libraries.

---

## ADR-002: Multi-Driver Sandboxed Execution vs Local Server Evaluation

### Context
The platform requires an interactive code playground supporting 15 programming languages. Executing user-submitted code locally on the WordPress web server via local execution functions or Docker sockets poses severe security risks (container breakouts, denial of service, resource exhaustion, host compromise).

### Decision
Implement a decoupled Multi-Driver Execution Architecture. All execution requests are securely proxied to isolated, external execution sandboxes (such as Piston or Judge0 runtimes). Local dynamic execution functions are strictly prohibited in theme code.

### Consequences
- **Positive:** Zero attack surface on the primary WordPress host. Strict isolation of untrusted student code. Horizontal scalability of the execution layer.
- **Negative:** Requires external runtime connectivity and handling of network latency or fallback modes.

---

## ADR-003: Idempotent Server-Side Points Ledger (Zero-Trust vs Client Reporting)

### Context
Gamified educational platforms are vulnerable to point manipulation if points are granted based on unverified client-side API calls. Malicious users can trigger automated scripts, replay requests, or simulate multiple completion events.

### Decision
Adopt a strict Zero-Trust model where all point calculations and criteria checks occur exclusively on the server.
1. The client sends only proof of activity (e.g., "lesson completed").
2. The server verifies criteria, computes points, and inserts an entry into the points ledger table.
3. Every entry requires a unique idempotency key constructed deterministically based on user ID, event type, reference ID, and date.
4. The database enforces a unique constraint on the idempotency key.

### Consequences
- **Positive:** Total immunity against replay attacks and race conditions. High-integrity data suitable for competitive leaderboards and verifiable certifications.
- **Negative:** Requires rigorous server-side event modeling and database indexing for idempotency checks.

---

## ADR-004: Native Gutenberg Block Patterns vs Visual Page Builders

### Context
The existing platform used Elementor, which introduced deep DOM nesting (15-20 container levels), heavy CSS/JS payloads, and severe vendor lock-in. Future maintenance was bottlenecked by builder updates.

### Decision
Transition content authoring entirely to native Gutenberg Block Patterns. Pre-register production-ready pattern assemblies (Heroes, Feature Grids, Courses, CTAs) styled exclusively with theme design tokens.

### Consequences
- **Positive:** Clean, semantic HTML5 output. Extreme performance gains. Zero ongoing builder license costs. Compatibility with future WordPress core features.
- **Negative:** Content creators must adapt to the Gutenberg block editor workflow rather than a drag-and-drop builder canvas.

---

## ADR-005: Hybrid Coexistence Layer for Legacy Elementor Content

### Context
The live platform contained over 370 published articles and 6 historical course series built with Elementor. Re-authoring all legacy posts prior to theme launch was operationally impossible.

### Decision
Engineer a specialized coexistence layer. The theme detects Elementor post metadata and wraps legacy content in isolated container classes, preserving previous layouts without allowing legacy styles to pollute new Gutenberg pages.

### Consequences
- **Positive:** Zero operational downtime or broken pages during migration. Allows incremental, unhurried migration of legacy content.
- **Negative:** Legacy styling assets must still be enqueued conditionally on legacy pages until full re-authoring is completed.