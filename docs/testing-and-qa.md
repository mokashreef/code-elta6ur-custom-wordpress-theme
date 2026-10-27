# Testing Methodology & Quality Assurance — Code Elta6ur Theme
> 🌐 **Language / اللغة:** [English](testing-and-qa.md) | [العربية](ar/testing-and-qa.md)


This document describes the automated verification pipeline, test suites, and quality assurance procedures engineered to validate the custom WordPress theme.

---

## 1. Testing Strategy Overview

The testing strategy spans multiple automated verification layers executed independently of the production WordPress environment:

```
┌────────────────────────────────────────────────────────┐
│               Automated Testing Pyramid                │
├────────────────────────────────────────────────────────┤
│  Layer 4: Visual & Responsive Headless Validation     │
│  Layer 3: Host Security Scans (Zero Prohibited Calls)  │
│  Layer 2: Registry, Driver & Schema Integration Tests  │
│  Layer 1: Standalone PHP CLI Syntax & Linting Suite    │
└────────────────────────────────────────────────────────┘
```

---

## 2. Automated Test Runner

The test suite runs headlessly via the PHP CLI using a dedicated test runner. It mocks necessary WordPress core primitives to execute deterministically in CI/CD pipelines without database dependencies.

### Core Verification Modules

1. **Developer Tools Registry Verification (30 Tools):**
   - Asserts that all 30 tool slugs are defined.
   - Verifies all 7 tool categories (`json-data`, `api-network`, `security-auth`, `regex-text`, `sql-database`, `devops-git`, `css-frontend`).
   - Validates that each tool definition includes mandatory metadata: identifier, localized names, descriptions, execution mode, input/output schemas, dependencies, privacy notices, acceptance criteria, and SEO definitions.

2. **Editor Languages Registry Verification (15 Languages):**
   - Asserts that all 15 language slugs exist.
   - Validates presence of runtime specifications, sample codes, file extensions, and SEO metadata.

3. **Provider Resolver & Execution Driver Architecture:**
   - Instantiates the Provider Resolver and confirms it returns a valid execution driver adhering to the driver interface specification.
   - Verifies the multi-driver registry (supporting Piston, Judge0, JDoodle, and Null fallback).

4. **Static Security Audit (Zero Host Execution):**
   - Recursively iterates through every PHP file in the theme.
   - Executes regular expression analysis checking for prohibited execution functions (such as dynamic evaluation or direct shell invocation functions).
   - **Result:** Asserts 0 occurrences across all files.

5. **URL Structure & Route Integrity:**
   - Validates the deterministic generation of 30 dedicated tool permalinks (`/tools/{slug}/`).
   - Validates the deterministic generation of 15 dedicated editor permalinks (`/editor/{lang}/`).
   - Verifies existence and preservation of the URL inventory mapping.

6. **Accounts, Gamification & Schema Structure:**
   - Verifies existence of custom account templates (Login, Registration, Lost Password, Account Dashboard).
   - Verifies frontend asset files.
   - Verifies gamification engines (database schema definitions, points engine, streak engine, reading engine, course progress, time tracker).

---

## 3. Automated Syntax & Linting Suite

- Recursively scans every PHP file in the codebase.
- Executes syntax verification checking compatibility with PHP 8.0, 8.1, 8.2, and 8.3.
- Enforces strict adherence to WordPress Coding Standards (WPCS) rules.

---

## 4. Visual & Responsive Suite

- Headless script inspecting DOM structure, viewport responsiveness, and RTL directionality across standard breakpoints:
  - Mobile: `375px × 667px`
  - Tablet: `768px × 1024px`
  - Desktop: `1440px × 900px`
- Validates that horizontal scrollbars do not occur (`overflow-x: hidden` enforcement).
- Validates contrast ratios on primary text and button tokens against WCAG 2.1 AA specifications.

---

## 5. Production Package Integrity Validation

- Verifies that the automated build script produces an installable ZIP package.
- Inspects package contents to ensure development files (version control metadata, tests, and documentation markdown files) are excluded from distribution builds.