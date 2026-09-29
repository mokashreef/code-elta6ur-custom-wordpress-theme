# نظام التصميم الموحد لكود التطور (Design System)
**الإصدار:** 1.0.0 — مخصص حصرياً لمنصة كود التطور  
**فلسفة التصميم:** Modern SaaS-inspired, Arabic-first, Clean, High-performance, Accessible.

---

## 1. فلسفة الهوية ومبادئ التصميم (Design Principles)

1. **الخصوصية والعراقة البرمجية (Engineering & Technical Identity):**  
   تصميم كود التطور ليس قالباً تجارياً جاهزاً، وليس مدونة ووردبريس عامة، وليس تصميماً عشوائياً مبنياً بـ Elementor. يعكس التصميم انطباع منصة تقنية احترافية موثوقة تلهم المبرمجين والطلاب العرب.
2. **العربية أولاً (Arabic-First Typography):**  
   النص العربي هو المحور الأساسي للواجهة. نستخدم خطوطاً أصيلة (`Cairo` للواجهة والمحتوى العربي، مع `Outfit` للأرقام والنصوص الإنجليزية، و`JetBrains Mono / Fira Code` للأكواد البرمجية)، مع التزام تام بالمسافات وارتفاع الأسطر المريح للنص العربي (`line-height: 1.7 - 1.85`).
3. **حظر اللون البنفسجي (Strict No-Purple Rule):**  
   وفقاً لتعليمات الهوية الصارمة، **يُمنع منعاً باتاً استخدام اللون البنفسجي ومشتقاته كجزء من الهوية الأساسية**. الألوان محصورة في لوحة الأزرق التقني والسماوي المتدرج والداكن المتزن.
4. **المنطقية في الاتجاهين (CSS Logical Properties):**  
   بناء كامل للأنماط باستخدام الخصائص المنطقية الحديثة (`margin-inline`, `padding-inline`, `inset-inline`, `border-start-start-radius`) لضمان دعم متكامل وسلس للغتين العربية (RTL) والإنجليزية (LTR) دون أي حلول ترقيعية أو تحويلات عشوائية.

---

## 2. لوحة الألوان المعتمدة (Color Palette)

تمت مطابقة الألوان بدقة بين ما ورد في الهوية الرسمية، والشيفرة المكتشفة في الموقع الحالي (`:root` على الرئيسية)، وبورتفوليو المؤسس:

```
┌────────────────────────────────────────────────────────────────────────┐
│  اللون الأساسي (Primary):      #0170B9  /  #0D6EFD                     │
│  الأزرق الفاتح (Primary Light): #3EA3E8  /  #4A9BE6                     │
│  الأزرق الداكن (Primary Dark):  #004A7D                                 │
│  اللون الثانوي (Secondary):     #3B82F6                                 │
│  اللون المميز (Accent):        #38BDF8                                 │
│  الخلفية الداكنة (Dark Text):   #0F172A  /  #1A1A1A                     │
│  الخلفية الفاتحة (Light/Card):  #F5F7FA  /  #FAFAFA                     │
│  السطح الأبيض (White Surface): #FFFFFF                                 │
│  الحدود والفواصل (Borders):    #E2E8F0  /  rgba(0, 0, 0, 0.08)          │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 3. متغيرات التصميم (Design Tokens)

يتم تعريف هذه المتغيرات مركزياً داخل ملف القالب الأساسي `style.css` وتوريثها لكافة المكونات وقوالب Gutenberg:

```css
:root {
  /* ==========================================================================
     Color Tokens
     ========================================================================== */
  --brand-primary: #0170B9;
  --brand-primary-light: #3EA3E8;
  --brand-primary-dark: #004A7D;
  --brand-secondary: #3B82F6;
  --brand-accent: #38BDF8;
  
  --brand-dark: #0F172A;
  --brand-dark-surface: #1E293B;
  --brand-light: #F5F7FA;
  --brand-white: #FFFFFF;
  
  --brand-text-main: #0F172A;
  --brand-text-muted: #555555;
  --brand-text-subtle: #64748B;
  
  --brand-bg: #FFFFFF;
  --brand-bg-alt: #F8FAFC;
  --brand-surface: #F5F7FA;
  --brand-surface-hover: #EDF2F7;
  
  --brand-border: #E2E8F0;
  --brand-border-subtle: rgba(0, 0, 0, 0.06);
  --brand-border-focus: #0170B9;

  /* Semantic Status Colors */
  --status-success: #10B981;
  --status-warning: #F59E0B;
  --status-error: #EF4444;
  --status-info: #0284C7;

  /* ==========================================================================
     Typography Tokens
     ========================================================================== */
  --font-ar: 'Cairo', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --font-en: 'Outfit', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --font-code: 'JetBrains Mono', 'Fira Code', Menlo, Consolas, Monaco, monospace;
  
  --font-base: var(--font-ar);
  
  --font-size-xs: 0.75rem;    /* 12px */
  --font-size-sm: 0.875rem;   /* 14px */
  --font-size-md: 1rem;       /* 16px - Base Body */
  --font-size-lg: 1.125rem;   /* 18px */
  --font-size-xl: 1.25rem;    /* 20px */
  --font-size-2xl: 1.5rem;    /* 24px */
  --font-size-3xl: 1.875rem;  /* 30px */
  --font-size-4xl: 2.25rem;   /* 36px */
  --font-size-hero: clamp(2rem, 4.5vw, 3.25rem); /* 32px to 52px */

  --font-weight-regular: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-extrabold: 800;

  --line-height-tight: 1.3;
  --line-height-normal: 1.6;
  --line-height-relaxed: 1.85; /* مثالي لقراءة المقالات العربية */

  /* ==========================================================================
     Spacing Tokens
     ========================================================================== */
  --space-1: 0.25rem;   /* 4px */
  --space-2: 0.5rem;    /* 8px */
  --space-3: 0.75rem;   /* 12px */
  --space-4: 1rem;      /* 16px */
  --space-5: 1.25rem;   /* 20px */
  --space-6: 1.5rem;    /* 24px */
  --space-8: 2rem;      /* 32px */
  --space-10: 2.5rem;   /* 40px */
  --space-12: 3rem;     /* 48px */
  --space-16: 4rem;     /* 64px */
  --space-20: 5rem;     /* 80px */
  --space-24: 6rem;     /* 96px */

  /* ==========================================================================
     Border Radius Tokens
     ========================================================================== */
  --radius-xs: 4px;
  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 20px;
  --radius-xl: 28px;
  --radius-full: 9999px;

  /* ==========================================================================
     Box Shadow Tokens
     ========================================================================== */
  --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.04);
  --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 6px 16px -2px rgba(15, 23, 42, 0.08);
  --shadow-lg: 0 12px 32px -4px rgba(15, 23, 42, 0.12);
  --shadow-hover: 0 16px 36px -6px rgba(1, 112, 185, 0.16);
  --shadow-focus: 0 0 0 3px rgba(1, 112, 185, 0.25);

  /* ==========================================================================
     Layout & Container Tokens
     ========================================================================== */
  --container-max: 1240px;
  --container-narrow: 840px;
  --container-padding: var(--space-4);
  --header-height: 80px;

  /* ==========================================================================
     Transition Tokens
     ========================================================================== */
  --transition-fast: 0.15s ease;
  --transition-normal: 0.25s ease;
  --transition-smooth: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

/* التبديل التلقائي للخط بناءً على لغة الصفحة */
[dir="rtl"] {
  --font-base: var(--font-ar);
}

[dir="ltr"] {
  --font-base: var(--font-en);
}
```

---

## 4. معايير ونقاط الاستجابة (Responsive Breakpoints)

يتم فحص وتوافق كل عنصر مع الأجهزة المختلفة عبر المعايير التالية:

| اسم النقطة | العرض (Width) | الأجهزة المستهدفة |
| :--- | :--- | :--- |
| **Mobile Small** | `320px - 374px` | الهواتف القديمة والضيقة |
| **Mobile Standard** | `375px - 414px` | هواتف iPhone و Samsung الحديثة |
| **Mobile Large** | `415px - 767px` | الهواتف ذات الشاشات الكبيرة |
| **Tablet Portrait** | `768px - 1023px` | أجهزة iPad والتابلت العمودي |
| **Laptop / Desktop** | `1024px - 1279px` | الحواسيب المحمولة القياسية |
| **Desktop Large** | `1280px - 1439px` | شاشات الحواسيب المكتبية |
| **Ultra Wide** | `1440px - 1920px+` | الشاشات العريضة وعالية الدقة |

---

## 5. مواصفات المكونات الأساسية (Components Specification)

### 1. الأزرار (Buttons):
- **الزر الأساسي (`.cdt-btn-primary`):**  
  - الخلفية: `var(--brand-primary)`
  - لون النص: `#FFFFFF`
  - التدوير: `var(--radius-sm)` (8px) أو `var(--radius-full)` للأزرار التفاعلية.
  - الحواشي: `12px 24px`
  - التأثير عند التحويم (Hover): رفع خفيف `translateY(-2px)` وظل `var(--shadow-hover)`.
- **الزر الثانوي (`.cdt-btn-secondary`):**  
  - الخلفية: `var(--brand-surface)`
  - لون النص: `var(--brand-primary)`
  - الحواشي: `12px 24px`
- **الزر المفرغ (`.cdt-btn-outline`):**  
  - الخلفية: شفافة
  - الحدود: `1.5px solid var(--brand-primary)`
  - لون النص: `var(--brand-primary)`

### 2. البطاقات (Cards):
- **بطاقة الدورة التعليمية (`.cdt-card-course`):**  
  - خلفية بيضاء `var(--brand-white)` مع حدود ناعمة `1px solid var(--brand-border)`، زوايا مستديرة `var(--radius-md)`.
  - بادينج داخلي `var(--space-6)`، شارة تصنيف واضحة، وزر CTA مباشر لكل كورس.
- **بطاقة المقال (`.cdt-card-post`):**  
  - صورة بارزة بنسبة عرض لارتفاع 16:9 مع `border-radius: var(--radius-sm)`.
  - تفاصيل الميتا: الكاتب، التاريخ، وقت القراءة المقدر، رابط العنوان، مقتطف نصي نظيف.
- **بطاقة الفيديو (`.cdt-card-video`):**  
  - صورة الغلاف من يوتيوب مع زر تشغيل تفاعلي يفتح نافذة Modal منبثقة دون مغادرة الصفحة.

### 3. شريط التنقل العلوي (Header & Navigation):
- ارتفاع ثابت `80px`، شريط أبيض ناصع مع حدود سفلية فائقة النعومة.
- شعار كود التطور الرسمي في البداية مع رابط الرئيسية.
- قائمة روابط مأخوذة أصلية من نظام قوائم ووردبريس (`wp_nav_menu`).
- زر بحث تفاعلي، وزر اتصل بنا / ابدأ التعلم.
- زر قائمة الموبايل (Hamburger) مدمج وخفيف الوزن بلا أي مكتبات خارجية ضخمة.

### 4. التذييل الموحد (Footer):
- هيكل من 3 إلى 4 أعمدة منظمة:
  1. شعار كود التطور ووصف المنصة المعتمد وشبكات التواصل المباشرة (Telegram, YouTube, Instagram, Facebook, GitHub).
  2. الروابط الهامة (الرئيسية، الدورات، المقالات، تواصل معنا).
  3. السياسات القانونية (سياسة الخصوصية، شروط الاستخدام) بروابطها المعتمدة الصحيحة.
  4. حقوق النشر الرسمية: `جميع الحقوق محفوظة © كود التطور 2026`.

### 5. عرض الأكواد البرمجية (Code Snippets):
- بما أن الموقع منصة تعليمية برمجية، فإن كتل الأكواد البرمجية تحظى بأعلى درجات العناية:
  - خط أحادي المسافة عالي الدقة: `'JetBrains Mono', 'Fira Code', monospace`.
  - اتجاه الكود دائماً `ltr` من اليسار لليمين حتى داخل الصفحات العربية.
  - زر نسخ الكود بنقرة واحدة (Copy to Clipboard).
  - خلفية داكنة فخمة مع تباين عالي للنصوص.
