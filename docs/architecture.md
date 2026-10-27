# Architecture Overview — Code Elta6ur WordPress Theme
> 🌐 **Language / اللغة:** [English](architecture.md) | [العربية](ar/architecture.md)


This document details the high-level architecture, design patterns, module separation, and component interactions of the custom WordPress theme engineered for **Code Elta6ur**.

---

## 1. Architectural Philosophy

The theme was designed around four non-negotiable architectural pillars:

1. **Zero Heavy Frameworks (High Performance):**
   Rejecting the prevailing trend of bundling jQuery, Bootstrap, or heavy client-side React frameworks for public visitor views. All user interactions are powered by native Vanilla JavaScript (ES6+) and modern Web APIs.
   
2. **Centralized Configuration:**
   Preventing hardcoded strings, URLs, or credentials. All platform-wide assets (branding, social profiles, contact endpoints, global calls-to-action) are centrally configured in the WordPress Customizer and accessed through typed helper functions.

3. **Gutenberg-Native Componentization:**
   Utilizing native Gutenberg Block Patterns rather than proprietary visual builders. This guarantees complete portability, native editing speed, and clean semantic markup.

4. **Multi-Layer Defensive Security:**
   Strict input sanitization, contextual output escaping, nonce verification for all state-changing requests, and absolute isolation of interactive execution runtimes.

---

## 2. System Architecture Diagram

```mermaid
graph TB
    subgraph Client [Browser Client (Arabic RTL / English LTR)]
        UI[Semantic HTML5 + CSS Logical Properties]
        JS[Vanilla JS Modules: Navigation, Copy, Lightbox, Trackers, Tool Engines]
    end

    subgraph ThemeCore [Theme Core Engine]
        Init[Theme Setup & Bootstrapper]
        Enq[Asset Enqueue Manager - Zero Render Blocking]
        Cust[Centralized Customizer Interface]
        Help[Helpers & Breadcrumbs Engine]
    end

    subgraph Subsystems [Modular Subsystems]
        Tools[Developer Tools Hub - 30 Client-Side Utilities]
        Playground[Code Playground - 15 Languages]
        Accounts[Accounts & Membership System]
        Gamify[Gamification & Activity Tracking Engine]
        SEO[SEO Health & 301 Redirection Engine]
        Patterns[Gutenberg Block Pattern Engine]
    end

    subgraph ExternalExecution [Remote Execution Layer]
        Resolver[Driver Resolver]
        DriverPiston[Piston Driver]
        DriverJudge[Judge0 Driver]
        DriverJDoodle[JDoodle Driver]
    end

    subgraph DatabaseLayer [WordPress Storage]
        WPCore[(WordPress Tables: wp_users, wp_posts, wp_options)]
        Ledger[(Points Ledger Table)]
        Progress[(Progress Table)]
        Badges[(Badges Table)]
        Sessions[(Sessions Table)]
    end

    UI <--> JS
    JS <--> ThemeCore
    ThemeCore --> Subsystems
    Playground --> Resolver
    Resolver --> DriverPiston
    Resolver --> DriverJudge
    Resolver --> DriverJDoodle
    Subsystems --> DatabaseLayer
```

---

## 3. Structural Decomposition

### A. Core Presentation & Layout Layer
- **Layout Templates:** Standardized hierarchical WordPress templates for home, single articles, general pages, archives, search results, and 404 pages.
- **Template Parts:** Highly reusable, modular snippets organized by component domain:
  - Header: Site branding, primary navigation, responsive mobile drawers.
  - Footer: Multi-column widget areas, copyright text, social links.
  - Hero: Contextual banners for home and internal landing pages.
  - Cards: Polymorphic cards for articles, courses, videos, and platform features.
  - Content: Clean article bodies with integrated breadcrumbs, reading time indicators, and copyable code blocks.

### B. Subsystem Layer
1. **Developer Tools Hub:**
   - Client-side execution architecture ensuring zero transmission of user inputs (JSON, SQL, environment variables) to external servers.
   - Centralized tool registry defining metadata, input/output schemas, categories, and SEO tags.
2. **Interactive Code Playground:**
   - Multi-driver abstraction decoupling frontend editor instances from backend execution engines.
   - Provider resolution mechanism that dynamically delegates code execution requests to designated sandbox providers while ensuring the host server executes zero untrusted code.
3. **Accounts & Gamification Engine:**
   - Frictionless authentication system extending native WordPress user capabilities with zero schema bloat.
   - Idempotent points ledger preventing double-crediting via database unique constraints.
   - Non-intrusive activity trackers utilizing the browser's Page Visibility API.
4. **SEO Health & Traffic Protection:**
   - Request-level interception logging 404 occurrences in real time.
   - Automated 301 redirect mapping engine matching legacy URLs to consolidated resources.
   - Automated Schema.org JSON-LD generation for articles, breadcrumbs, and organizations.

### C. Safe Coexistence & Compatibility Layer
- A dedicated compatibility layer encapsulates legacy Elementor markup within scoped selectors, allowing historical pages to display correctly without polluting global styles or hindering new Gutenberg-driven templates.

---

## 4. Frontend Architecture & Design Tokens

- **CSS Custom Properties (Design Tokens):** Centralized token definition establishing brand colors, dark-mode surfaces, typography scale, spacing grids, and elevation shadows.
- **RTL-First Logical Properties:** Layouts leverage logical properties (`margin-inline`, `padding-inline`, and `inset-inline`) to ensure flawless rendering across Arabic (RTL) and English (LTR) contexts without CSS overrides.
- **Zero-Dependency JavaScript:** Packaged as discrete ES6 modules with strictly scoped event delegation, ensuring minimal memory footprints and zero library overhead.