# سجل الروابط وتوجيه المسارات (URL Inventory & Mapping)
**الهدف:** الحفاظ الكامل على بنية الروابط الحالية (URLs & Permalinks)، منع حدوث أي أخطاء 404، معالجة الروابط المعطوبة المكتشفة، وضمان حماية أرشفة الموقع في محركات البحث (SEO).

---

## 1. سياسة الروابط والقواعد الأساسية (Permalink Rules)
1. **هيكل الروابط الدائمة (Permalinks Structure):**  
   يعتمد الموقع على بنية الاسم اللطيف للمنشور: `/%postname%/`.  
   **قاعدة صارمة:** يُمنع منعاً باتاً تعديل هذا الهيكل أو تغيير أي Slug لأي مقال أو صفحة أثناء أو بعد تثبيت القالب.
2. **الروابط العربية المشفرة (Encoded Arabic Slugs):**  
   WordPress يتعامل داخلياً مع الـ Slugs العربية بصيغة URL Encoded (مثل `%d8%a7%d9%84%d8%b1%d8%a6%d9%8a%d8%b3%d9%8a%d8%a9`). القالب الجديد مصمم للتعامل بكفاءة مع المسارات العربية دون أي تشويه أو أخطاء في الـ Canonical URLs.

---

## 2. جرد الروابط لجميع الصفحات العشرين (Pages URL Inventory)

| المعرف (ID) | العنوان الحالي | الرابط الفعلي (Actual URL) | الحالة الحالية (HTTP Status) | الإجراء المتبع في القالب الجديد (Action) |
| :---: | :--- | :--- | :---: | :--- |
| **2612** | الرئيسية | `https://code-elta6ur.com/` | `200 OK` | يُعرض عبر `front-page.php` المخصص بهوية كود التطور. |
| **233** | من نحن | `https://code-elta6ur.com/من-نحن/` | `200 OK` | الحفاظ على الرابط كما هو؛ يُعرض عبر `page.php` أو قالب مخصص. |
| **43** | اتصل بنا | `https://code-elta6ur.com/اتصل-بنا/` | `200 OK` | الحفاظ على الرابط؛ يدعم نموذج Forminator ومكونات التواصل المركزية. |
| **40** | المدونة | `https://code-elta6ur.com/المدونة/` | `200 OK` | الحفاظ على الرابط؛ يُعرض عبر صفحة الأرشيف/المدونة الموحدة. |
| **832** | الكورسات التعليمية | `https://code-elta6ur.com/الكورسات-التعليمية/` | `200 OK` | الحفاظ التام على الرابط؛ هو الفهرس الأساسي لكافة الدورات. |
| **822** | كورس HTML | `https://code-elta6ur.com/كورس-html/` | `200 OK` | الحفاظ على الرابط والدروس التابعة له. |
| **827** | كورس CSS | `https://code-elta6ur.com/كورس-css/` | `200 OK` | الحفاظ على الرابط والدروس التابعة له. |
| **1197** | كورس Bootstrap | `https://code-elta6ur.com/كورس-bootstrap/` | `200 OK` | الحفاظ على الرابط والدروس التابعة له. |
| **1273** | كورس JavaScript | `https://code-elta6ur.com/كورس-javascript/` | `200 OK` | الحفاظ على الرابط والدروس التابعة له. |
| **1372** | كورس React | `https://code-elta6ur.com/كورس-react/` | `200 OK` | الحفاظ على الرابط والدروس التابعة له. |
| **2733** | كورس C++ | `https://code-elta6ur.com/كورس-c/` | `200 OK` | الحفاظ على الرابط والدروس التابعة له. |
| **2059** | سياسة الخصوصية | `https://code-elta6ur.com/سياسة-الخصوصية/` | `200 OK` | الحفاظ على الرابط، وتصحيح رابط الفوتر للإشارة إليه مباشرة. |
| **2361** | شروط الاستخدام | `https://code-elta6ur.com/شروط-الاستخدام/` | `200 OK` | الحفاظ على الرابط، وتصحيح رابط الفوتر للإشارة إليه مباشرة. |
| **1723** | تسجيل الدخول | `https://code-elta6ur.com/login/` | `200 OK` | صفحة Ultimate Member؛ القالب ينسق إطاراتها دون كسر نماذجها. |
| **1725** | تسجيل | `https://code-elta6ur.com/registration/` | `200 OK` | صفحة Ultimate Member؛ متوافقة تماماً. |
| **1724** | فقدت كلمة المرور | `https://code-elta6ur.com/lost-password/` | `200 OK` | صفحة استعادة الحساب؛ متوافقة تماماً. |
| **1722** | حسابي | `https://code-elta6ur.com/my-account/` | `200 OK` | لوحة تحكم العضو؛ الحفاظ الكامل. |
| **2779** | مستخدم | `https://code-elta6ur.com/user/` | `200 OK` | الملف الشخصي للمستخدم؛ متوافق. |
| **2780** | الأعضاء | `https://code-elta6ur.com/members/` | `200 OK` | دليل الأعضاء؛ الحفاظ الكامل. |
| **2781** | تسجيل الخروج | `https://code-elta6ur.com/logout/` | `200 OK` | رابط تسجيل الخروج الآمن؛ الحفاظ الكامل. |

---

## 3. جرد روابط التصنيفات (Categories URL Inventory)

| المعرف | اسم التصنيف | الرابط الحالي (Canonical URL) | الحالة |
| :---: | :--- | :--- | :---: |
| 71 | Bootstrap | `https://code-elta6ur.com/category/البرمجة/bootstrap/` | `200 OK` |
| 80 | C++ | `https://code-elta6ur.com/category/البرمجة/c/` | `200 OK` |
| 62 | css | `https://code-elta6ur.com/category/البرمجة/css/` | `200 OK` |
| 53 | HTML | `https://code-elta6ur.com/category/البرمجة/html/` | `200 OK` |
| 74 | JavaScript | `https://code-elta6ur.com/category/البرمجة/javascript/` | `200 OK` |
| 76 | React | `https://code-elta6ur.com/category/البرمجة/react/` | `200 OK` |
| 6 | البرمجة | `https://code-elta6ur.com/category/البرمجة/` | `200 OK` |
| 10 | التقنية | `https://code-elta6ur.com/category/التقنية/` | `200 OK` |
| 9 | الذكاء الاصطناعي | `https://code-elta6ur.com/category/الذكاء-الاصطناعي/` | `200 OK` |
| 7 | العمل في البرمجة | `https://code-elta6ur.com/category/العمل-في-البرمجة/` | `200 OK` |
| 5 | تطوير المواقع | `https://code-elta6ur.com/category/تطوير-المواقع/` | `200 OK` |
| 8 | تعلم البرمجة | `https://code-elta6ur.com/category/تعلم-البرمجة/` | `200 OK` |
| 119 | كورس typescript | `https://code-elta6ur.com/category/البرمجة/typescript/` | `200 OK` |
| 11 | لغات البرمجة | `https://code-elta6ur.com/category/لغات-البرمجة/` | `200 OK` |

---

## 4. جرد روابط السلاسل التعليمية (Custom Post Type: `cat_series`)

| المعرف | عنوان السلسلة | الرابط الفعلي |
| :---: | :--- | :--- |
| **3543** | دورة typescript من الصفر الى الاحتراف باللغة العربية | `https://code-elta6ur.com/series/دورة-typescript-من-الصفر-الى-الاحتراف-باللغة-ال/` |
| **الأرشيف** | أرشيف السلاسل التعليمية | `https://code-elta6ur.com/series/` |

---

## 5. مصفوفة إعادة التوجيه ومعالجة الروابط المعطوبة (301 Redirect Mapping)

أثناء التدقيق الشامل تم اكتشاف الروابط التالية التي تسبب حالياً أخطاء 404 في الموقع الحي. يتضمن القالب كود إعادة توجيه 301 ذكي لمعالجتها وحماية تجربة الزائر:

| الرابط المعطوب / المطلوب توجيهه (Old / Broken URL) | السبب المكتشف في الموقع الحالي | الرابط الصحيح المنشور (Target Destination) | نوع التوجيه (HTTP Redirect) |
| :--- | :--- | :--- | :---: |
| `https://code-elta6ur.com/كورسات-التعليمية/` | خطأ إملائي في رابط زر الـ Hero بالرئيسية الحالية (غياب "الـ") | `https://code-elta6ur.com/الكورسات-التعليمية/` | **301 Moved Permanently** |
| `https://code-elta6ur.com/privacy-policy/` | الرابط الإنجليزي بالفوتر الحالي يقود لصفحة 404 | `https://code-elta6ur.com/سياسة-الخصوصية/` | **301 Moved Permanently** |
| `https://code-elta6ur.com/terms-of-use/` | الرابط الإنجليزي بالفوتر الحالي يقود لصفحة 404 | `https://code-elta6ur.com/شروط-الاستخدام/` | **301 Moved Permanently** |
| `https://code-elta6ur.com/courses/` | مسار بديل محتمل يطلبه الزوار | `https://code-elta6ur.com/الكورسات-التعليمية/` | **301 Moved Permanently** |
| `https://code-elta6ur.com/about/` | مسار بديل بالإنجليزية | `https://code-elta6ur.com/من-نحن/` | **301 Moved Permanently** |
| `https://code-elta6ur.com/contact/` | مسار بديل بالإنجليزية | `https://code-elta6ur.com/اتصل-بنا/` | **301 Moved Permanently** |
| `https://code-elta6ur.com/blog/` | مسار بديل لصفحة المدونة | `https://code-elta6ur.com/المدونة/` | **301 Moved Permanently** |

---

## 6. خرائط الموقع المعتمدة (Sitemaps Compatibility)
يتم الحفاظ على خرائط الموقع المولدة تلقائياً بواسطة إضافة السيو `ThinkRank SEO`:
- `https://code-elta6ur.com/wp-sitemap.xml`
- `https://code-elta6ur.com/sitemap-posts.xml`
- `https://code-elta6ur.com/sitemap-posts-2.xml`
- `https://code-elta6ur.com/sitemap-categories.xml`
- `https://code-elta6ur.com/local-sitemap.xml`
- القالب لا ينشئ خرائط بديلة أو مكررة، مما يحمي الفهرسة الكاملة في Google Search Console.
