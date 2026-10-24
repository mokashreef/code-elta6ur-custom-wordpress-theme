# قائمة التحقق الفنية للاستضافة والجدار الناري (Hosting & Server Checklist)

هذا الدليل الفني مخصص لمهندس الخادم ومدير موقع **كود التطور (code-elta6ur.com)** لعلاج أخطاء **5xx (Server Errors)** و **403 (Forbidden)** المبلغ عنها في Google Search Console.

---

## 1. فحص سجلات أخطاء الخادم (5xx Error Logs)
عند ظهور خطأ 500 أو 502 أو 503 في Search Console، فإن السبب الجذري يكون مدوناً في سجلات الاستضافة (Apache / Nginx / PHP-FPM):
1. **في لوحة استضافة Hostinger (hPanel):**
   - انتقل إلى **Websites** -> **Manage** -> **Logs & Statistics** -> **Error Logs**.
   - ابحث عن السطور المصحوبة بـ `PHP Fatal error` أو `Out of memory` أو `upstream timed out`.
2. **فحص ملف سجل ووردبريس (WP_DEBUG_LOG):**
   - تأكد من تفعيل السجل في `wp-config.php`:
     ```php
     define( 'WP_DEBUG', true );
     define( 'WP_DEBUG_LOG', true );
     define( 'WP_DEBUG_DISPLAY', false );
     ```
   - افحص الملف في مسار: `/wp-content/debug.log` (يمكنك معاينة آخر 50 سطراً مباشرة من لوحة **صحة السيو** داخل لوحة تحكم القالب).

---

## 2. إعدادات Cloudflare وجدار الحماية (WAF & Bot Management)
أحد أهم أسباب حجب Googlebot بـ 403 هو ميزات حماية البوتات الصارمة:
1. **Bot Fight Mode في Cloudflare:**
   - توجّه إلى: **Security** -> **Bots**.
   - تأكد من أن Googlebot لا يتم اعتراضه بتحديات Managed Challenge أو JS Challenge.
   - البوتات المعتمدة (Verified Bots) تكون مستثناة افتراضياً في Cloudflare ما لم تكن هناك قاعدة مخصصة (Custom WAF Rule) تحجبها بناءً على الـ ASN أو مسارات معينة.
2. **إنشاء قاعدة استثناء صريحة لـ Googlebot:**
   - في Cloudflare WAF: أنشئ قاعدة تخطي (Bypass):
     - الشرط: `cf.client.bot` is `true` OR `http.user_agent contains "Googlebot"`
     - الإجراء: **Bypass / Allow**.

---

## 3. التحقق العكسي من هوية Googlebot (Reverse DNS Verification)
تأكد من أن جدار حماية السيرفر (مثل ModSecurity أو Wordfence) لا يحجب عناوين IP الخاصة بجوجل:
- جوجل ينشر نطاقات IP الخاصة به ولا يعتمد فقط على اسم المتصفح (User-Agent).
- للتحقق من أي IP يزعم أنه Googlebot:
  ```bash
  host 66.249.66.1
  # الناتج يجب أن ينتهي بـ: .googlebot.com أو .google.com
  host crawl-66-249-66-1.googlebot.com
  # يجب أن يعيد نفس عنوان الـ IP
  ```

---

## 4. موارد الخادم ومكتبات PHP (Server Resources)
لضمان عدم انهيار PHP تحت ضغط فهرسة مئات الصفحات المتزامنة:
1. **ذاكرة PHP الموصى بها:**
   - في `wp-config.php`:
     ```php
     define( 'WP_MEMORY_LIMIT', '512M' );
     define( 'WP_MAX_MEMORY_LIMIT', '1024M' );
     ```
2. **إعدادات `php.ini` المثالية:**
   - `max_execution_time = 300`
   - `max_input_vars = 5000`
   - `post_max_size = 64M`
   - `upload_max_filesize = 64M`
3. **تفعيل OPcache:**
   - تأكد من تفعيل `Zend OPcache` لتسريع تنفيذ قوالب PHP بنسبة تتجاوز 70% وتقليل إجهاد المعالج.

---

## 5. التأكد من الوصول العام للصفحات (No Auth / No Cookies Barrier)
- تأكد من أن الروابط العامة (المقالات، الكورسات، الأدوات، المحرر) لا تشترط وجود Cookie أو جلسة مستخدم مسبقة.
- تأكد أن ملف `.htaccess` أو إعدادات Nginx لا تحتوي قواعد إعادة توجيه تجبر الزوار من وكلاء معينين على تسجيل الدخول.
