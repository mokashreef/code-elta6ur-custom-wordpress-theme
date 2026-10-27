# Security & Privacy Architecture — Code Elta6ur Theme

This document details the security principles, data protection safeguards, and privacy controls implemented throughout the custom WordPress theme.

---

## 1. Security Architecture Principles

1. **Principle of Least Privilege:** Theme components operate strictly within necessary WordPress capabilities. Administrative settings require appropriate management privileges.
2. **Zero Host Execution:** The WordPress web server must never evaluate or execute user-submitted source code.
3. **Zero-Trust Client Data:** Data received from browser clients (points claims, progress reports, profile updates) is never trusted unconditionally; all inputs are validated, recomputed, and authorized server-side.
4. **Defense in Depth:** Multiple independent verification layers (contextual escaping, input sanitization, security nonce checking, and unique database constraints).

---

## 2. Threat Modeling & Mitigation

| Threat Vector | Mitigation Strategy |
| :--- | :--- |
| **SQL Injection** | Exclusively use parameterized database queries and core WordPress data-access abstractions. |
| **Cross-Site Scripting (XSS)** | Context-sensitive output escaping on 100% of rendered values (HTML escaping, attribute escaping, URL escaping, and allowed tag filtering). |
| **Cross-Site Request Forgery (CSRF)** | Every state-changing AJAX endpoint validates cryptographic security nonces. |
| **Direct Script Execution** | Every template and module file enforces direct script execution prevention guards at the file header. |
| **Remote Code Execution (RCE)** | Complete prohibition of dynamic code evaluation functions. Interactive code runtimes execute in external, isolated sandbox containers. |
| **Race Conditions / Double-Spend** | Unique database constraint on idempotency keys in the points ledger table prevents concurrent duplication of awards. |
| **Data Scraping / Enumeration** | User listing and author archive enumeration disabled for unauthenticated visitors. |

---

## 3. Privacy & Client-Side Execution

- **Developer Tools Privacy:** The 30 developer utilities (such as JWT decoding, JSON formatting, SQL formatting, and regex testing) operate 100% client-side in browser memory. User data, payloads, and secrets entered into these tools are never transmitted to or logged on any server.
- **Minimal PII Collection:** The platform collects only essential user information (username, email, password hash) required for authentication. No third-party tracking scripts or advertising SDKs are loaded.

---

## 4. Policy on Source Code & Asset Exclusion

To protect the intellectual property, commercial confidentiality, and security posture of Code Elta6ur:
- The proprietary theme source code, implementation files, internal server configurations, API keys, and database dumps are **strictly excluded** from this documentation repository.
- No confidential credentials, database passwords, or private endpoints are included in documentation files or commit logs.