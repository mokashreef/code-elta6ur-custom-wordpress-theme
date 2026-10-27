# Releases & Packaging Architecture — Code Elta6ur Theme
> 🌐 **Language / اللغة:** [English](releases-and-packaging.md) | [العربية](ar/releases-and-packaging.md)

This document details the release milestones, versioning cadence, and automated packaging architecture designed for the Code Elta6ur WordPress theme.

---

## 1. Release History & Milestones

The platform followed a structured, multi-phase engineering evolution leading up to the current production release (v1.2.5):

```
v1.0.0 (Foundation) ──> v1.1.0 (SEO Suite) ──> v1.2.0 (Gamification) ──> v1.2.5 (DevTools & Sandbox)
```

### Version Changelog Summary

| Version | Release Date | Architectural Focus | Key Engineering Deliverables |
| :---: | :---: | :--- | :--- |
| **v1.2.5** | *October 2026* | **DevTools Hub & Code Sandbox** | • 30 special-purpose developer tools across 7 categories.<br>• 15-language interactive code playground.<br>• Multi-driver execution engine (`CDT_Execution_Driver_Interface`).<br>• Zero Host Code Execution static security validation. |
| **v1.2.0** | *October 2026* | **Gamification & Accounts** | • Native accounts dashboard, login, registration, lost password.<br>• Atomic points ledger with unique idempotency keys (`wp_cdt_points_ledger`).<br>• Active reading tracker with Page Visibility API & scroll depth.<br>• Daily streak engine, course progress tracker, 11 badges. |
| **v1.1.0** | *October 2026* | **SEO Health & Traffic Protection** | • Real-time 404 monitoring and diagnostic log.<br>• Automated 301 redirection engine preserving historical URLs.<br>• Canonical URL enforcement & indexing policy.<br>• Automated Schema.org JSON-LD generation.<br>• Custom WP-CLI diagnostic commands (`wp cdt-seo audit`). |
| **v1.0.0** | *October 2026* | **Core Theme & Design System** | • Zero-framework Vanilla JS (ES6+) architecture.<br>• Modern CSS Grid / Flexbox with CSS Logical Properties (RTL-first).<br>• Centralized WordPress Customizer branding controls.<br>• Native Gutenberg Block Patterns (`inc/block-patterns.php`).<br>• Safe Elementor coexistence and styling isolation wrapper. |

---

## 2. Packaging & Build Pipeline Architecture

To ensure flawless deployment across diverse server topologies without manual friction, the theme utilizes a custom packaging compiler (`build-theme-zip.php`) that outputs two distinct deployment packages:

### Target Package 1: WP-Admin Direct Upload (`code-elta6ur-theme.zip`)
- **Structure:** Root-level asset deployment.
- **Priority Headers Indexing:** The packaging compiler injects priority files (`style.css`, `functions.php`, `index.php`, `screenshot.png`, `README.md`) at the physical beginning of the archive.
- **Purpose:** Enables instant theme validation by the native WordPress Theme Upgrader via `Appearance -> Themes -> Add New -> Upload Theme`, preventing double-directory decompression bugs.

### Target Package 2: Server File Manager / cPanel Archive (`code-elta6ur-theme-cpanel.zip`)
- **Structure:** Directory-wrapped layout (`code-elta6ur-theme/...`).
- **Purpose:** Optimized for system administrators deploying directly via cPanel File Manager or SSH into `wp-content/themes/`. Extracting the archive immediately creates the correct isolated folder structure without scattering files across the parent themes directory.

---

## 3. Automated Package Hygiene & Stripping Rules

The build pipeline enforces strict exclusion rules to guarantee that production ZIP packages contain zero development bloat or sensitive artifacts:

```
[Source Workspace] ──> [Filtering & Stripping Engine] ──> [Production ZIP Packages]
                             │
                             ├── Exclude: .git/ and version control trees
                             ├── Exclude: tests/ and verification suites
                             ├── Exclude: Development markdown docs (*.md)
                             ├── Exclude: Scratch scripts & temporary caches
                             └── Exclude: OS hidden files (.DS_Store, Thumbs.db)
```

- **Compression Ratio:** High-efficiency DEFLATE compression yielding an ultra-compact package (~820 KB) despite bundling complete SVG icon suites, local web fonts, and extensive CSS/JS engines.
- **Integrity Validation:** Every build is automatically tested via `test-zip-integrity.php` prior to production deployment.