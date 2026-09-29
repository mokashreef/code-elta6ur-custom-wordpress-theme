# البنية الهندسية الشاملة للقالب (Theme Architecture)
**اسم القالب:** `code-elta6ur-theme`  
**النوع:** Modern Classic / Hybrid WordPress Theme  
**المعايير المعتمدة:** WordPress Coding Standards (WPCS), PHP 8.0+ Compatibility, Semantic HTML5, WCAG 2.1 AA Accessibility.

---

## 1. الفلسفة المعمارية للقالب (Architectural Philosophy)

تم تصميم قالب كود التطور ليكون **مستقلاً، فائق الخفة، وسهل الصيانة**، مع تجنب الاعتماد على أطر العمل العملاقة غير المبررة (مثل Bootstrap أو Tailwind أو React في واجهة المستخدم).

1. **الاستقلالية وعدم التبعية (Zero Heavy Frameworks):**  
   لا توجد تبعيات على jQuery أو React في واجهة الزائر. الجافاسكريبت نقي (Vanilla JS ES6+)، والأنماط معتمدة على CSS Grid و Flexbox و CSS Custom Properties.
2. **المركزية في الإدارة (Centralized Settings):**  
   جميع المتغيرات المشتركة (الشعار، روابط السوشيال، البريد، الهاتف، الواتساب، التيليجرام، معرف قناة يوتيوب، نصوص الفوتر) مدارة مركزياً عبر WordPress Customizer أو صفحة إعدادات كود التطور، وممنوع كتابة أي رابط بشكل Hardcoded.
3. **تكامل Gutenberg عبر Block Patterns:**  
   بدلاً من إنشاء كتل مخصصة معقدة تتطلب Node.js و React لبنائها، نستخدم **Gutenberg Block Patterns أصلية** خفيفة ومبنية بـ HTML/CSS دلالي، مما يضمن أن القالب يعمل مباشرة عند رفعه كملف ZIP إلى أي استضافة ووردبريس.
4. **التوافقية والحماية (Safe Coexistence):**  
   تصميم غلاف (Wrapper) متطور يتعامل بسلاسة مع صفحات Elementor السابقة دون كسرها، بينما يخدم الصفحات الجديدة بأكواد نظيفة وأصلية 100%.

---

## 2. شجرة وهيكل ملفات المشروع (Directory Structure)

```
code-elta6ur-theme/
├── style.css                     # الترويسة الرسمية للقالب ومتغيرات Design Tokens
├── functions.php                 # نقطة البداية وتحميل ملفات التهيئة (Bootstrapper)
├── index.php                     # القالب الاحتياطي العام
├── front-page.php                # القالب المخصص للصفحة الرئيسية لكود التطور
├── home.php                      # أرشيف المدونة والمقالات (Blog Index)
├── page.php                      # القالب الموحد لصفحات الموقع العامة
├── single.php                    # قالب المقال الفردي الشامل (Single Article)
├── archive.php                   # أرشيف التصنيفات والوسوم والتواريخ
├── taxonomy-cat_series.php       # أرشيف وقالب السلاسل التعليمية (Custom Post Type)
├── search.php                    # صفحة نتائج البحث الموحدة
├── 404.php                       # صفحة الخطأ 404 المتوافقة مع هوية المنصة
├── header.php                    # رأس الموقع وشريط التنقل العلوي
├── footer.php                    # تذييل الموقع الموحد وبيانات الحقوق
├── screenshot.png                # لقطة المعاينة الرسمية في لوحة تحكم ووردبريس
├── README.md                     # دليل التثبيت والاستخدام الكامل
│
├── assets/                       # الموارد الثابتة للقالب
│   ├── css/
│   │   ├── main.css              # الأنماط الأساسية للموقع ونظام التصميم
│   │   ├── rtl.css               # الأنماط التكميلية للاتجاه العربي
│   │   ├── editor-style.css      # أنماط مطابقة لمحرر Gutenberg في لوحة التحكم
│   │   └── elementor-compat.css  # طبقة التوافق وعزل كلاسات Elementor
│   ├── js/
│   │   ├── navigation.js         # قائمة الجوال الخفيفة والبحث التفاعلي
│   │   ├── video-modal.js        # المشغل المنبثق لفيديوهات يوتيوب
│   │   └── code-copy.js          # زر نسخ الأكواد البرمجية بنقرة واحدة
│   └── images/
│       └── logo.png              # الشعار الافتراضي لمنصة كود التطور
│
├── inc/                          # الشيفرات والوظائف الهندسية المنظمة
│   ├── theme-setup.php           # تهيئة ميزات القالب (theme supports, menus, sizes)
│   ├── enqueue.php               # تسجيل وتحميل ملفات CSS و JS بكفاءة
│   ├── customizer.php            # إعدادات كود التطور المركزية في Customizer
│   ├── helpers.php               # دوال مساعدة (وقت القراءة، الخلاصات، الخيارات)
│   ├── breadcrumbs.php           # نظام مسار التتبع الأصلي المهيأ للـ SEO
│   ├── block-patterns.php        # تسجيل أنماط كود التطور الجاهزة في جوتنبرج
│   ├── seo-schema.php            # إضافة بيانات Schema المنظمة للمقالات والموقع
│   ├── compatibility.php         # طبقة دعم Elementor و Forminator و Ultimate Member
│   └── redirects.php             # المعالجة التلقائية وتوجيه الروابط 301
│
└── template-parts/               # الأجزاء البرمجية المعيارية القابلة لإعادة الاستخدام
    ├── header/
    │   ├── site-branding.php     # الشعار واسم المنصة
    │   ├── primary-nav.php       # القائمة الرئيسية
    │   └── mobile-nav.php        # قائمة الهاتف الذكي
    ├── footer/
    │   ├── footer-widgets.php    # أعمدة الفوتر
    │   └── footer-bottom.php     # شريط الحقوق وشبكات التواصل
    ├── hero/
    │   ├── hero-home.php         # بانر الهيرو في الصفحة الرئيسية
    │   └── hero-page.php         # رأس الصفحات الداخلية النظيف
    ├── cards/
    │   ├── card-post.php         # بطاقة المقال الإخباري/التعليمي
    │   ├── card-course.php       # بطاقة الكورس التعليمي
    │   ├── card-video.php        # بطاقة فيديو يوتيوب
    │   └── card-feature.php      # بطاقة أهداف ومميزات المنصة
    └── content/
        ├── content-single.php    # جسم المقال الفردي (محتوى، ميتا، كاتب، مشاركة)
        ├── content-page.php      # جسم الصفحة العامة
        └── content-none.php      # رسالة في حال عدم وجود نتائج
```

---

## 3. بادئة التسمية وفضاء الأسماء (Namespacing & Prefixing)

لتجنب أي تضارب محتمل مع نواة ووردبريس أو الإضافات الأخرى:
- **بادئة الدوال والمتغيرات:** `cdt_` (اختصار **C**ode **D**evelopment / **C**ode **T**a6ur).
- **كلاسات CSS للمكونات الخاصة:** البادئة `.cdt-` (مثل `.cdt-hero`, `.cdt-btn`, `.cdt-card`).
- **المقابض المسجلة (Handles):** `cdt-main-style`, `cdt-navigation-script`, `cdt-video-script`.
- **معرف خيارات القالب (Theme Mods):** `cdt_options` ومفاتيح مثل `cdt_logo`, `cdt_whatsapp`, `cdt_youtube`.

---

## 4. نظام الإعدادات المركزية (Centralized Site Settings Architecture)

يتم بناء لوحة تحكم مركزية في **WordPress Customizer** (`inc/customizer.php`) تحت قسم رئيسي بارز:  
**🎨 إعدادات منصة كود التطور (Code Elta6ur Settings)**

وتتفرع إلى الأقسام التالية:
1. **بيانات التواصل المباشر (Contact Details):**
   - البريد الإلكتروني الرسمي للمنصة (`cdt_email`)
   - رقم الهاتف والواتساب المباشر (`cdt_whatsapp`)
   - رابط حساب تيليجرام (`cdt_telegram`)
2. **شبكات التواصل الاجتماعي (Social Media Channels):**
   - رابط قناة يوتيوب والمعرف البرمجي (`cdt_youtube_url`, `cdt_youtube_channel_id`)
   - رابط حساب إنستغرام (`cdt_instagram`)
   - رابط صفحة فيسبوك (`cdt_facebook`)
   - رابط حساب GitHub للمؤسس (`cdt_github`)
   - رابط حساب LinkedIn (`cdt_linkedin`)
3. **ألوان الهوية (Brand Colors):**
   - اللون الأساسي (Primary Color) مع القيمة الافتراضية `#0170B9`
   - اللون الثانوي (Secondary Color) مع القيمة الافتراضية `#3B82F6`
   - اللون المميز (Accent Color) مع القيمة الافتراضية `#38BDF8`
4. **تذييل الموقع وحقوق النشر (Footer & Copyright):**
   - نبذة المنصة في الفوتر (`cdt_footer_about`)
   - نص حقوق النشر السنوي (`cdt_copyright_text`)
5. **دعوة اتخاذ الإجراء الافتراضية (Global CTA):**
   - عنوان وزر ورابط الـ CTA الشامل للمنصة.

---

## 5. معايير الأمان البرمجي (Security Architecture)

1. **الهروب الآمن عند الإخراج (Output Escaping):**  
   - النصوص: `esc_html()`, `esc_html_e()`
   - الخصائص: `esc_attr()`, `esc_attr_e()`
   - الروابط: `esc_url()`
   - الكود والـ HTML المسموح به: `wp_kses_post()`
2. **التعقيم الصارم للمدخلات (Input Sanitization):**  
   - الحقول النصية: `sanitize_text_field()`
   - البريد: `sanitize_email()`
   - الروابط: `esc_url_raw()`
   - الأرقام: `absint()`
3. **التحقق من الصلاحيات وأرقام الحماية (Capabilities & Nonces):**  
   - أي عمليات AJAX أو نماذج تخضع لفحص `check_ajax_referer()` أو `wp_verify_nonce()` وصلاحيات `current_user_can()`.
4. **منع الوصول المباشر للملفات:**  
   - كل ملف PHP في القالب يبدأ بسطر الحماية القياسي:
   ```php
   if ( ! defined( 'ABSPATH' ) ) {
       exit; // Exit if accessed directly.
   }
   ```

---

## 6. استراتيجية الأداء والسرعة (Performance Architecture)

- **تقليل طلبات HTTP:** ملف CSS أساسي واحد مضغوط بدون تشتيت.
- **التحميل المسبق للخطوط (Preconnect & Font-Display Swap):**  
  ربط سريع بـ `fonts.googleapis.com` مع استخدام `font-display: swap` لمنع حجب الرندر (Render Blocking).
- **الصور سريعة الاستجابة (Responsive Images):**  
  استخدام كامل لـ `wp_get_attachment_image()` وخصائص `srcset` و `sizes` والتحميل الكسول الأصلي `loading="lazy"`.
- **نقاء DOM وحذف العناصر الزائدة:**  
  التحول من بنية الـ Containers المتداخلة بعمق في Elementor إلى عناصر HTML5 دلالية خفيفة (`<header>`, `<nav>`, `<main>`, `<article>`, `<footer>`).
