<?php
/**
 * توليد صفحات اختبار حقيقية لبيئة الفحص المرئي
 * Generate Realistic HTML Test Pages for Visual & Responsive Audits
 */

$themeDir = __DIR__ . '/../code-elta6ur-theme';
$outputDir = __DIR__ . '/html-fixtures';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

function get_common_head($title) {
    return '<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . ' - كود التطور</title>
    <link rel="stylesheet" href="../../code-elta6ur-theme/style.css">
    <link rel="stylesheet" href="../../code-elta6ur-theme/assets/css/main.css">
    <link rel="stylesheet" href="../../code-elta6ur-theme/assets/css/rtl.css">
    <link rel="stylesheet" href="../../code-elta6ur-theme/assets/css/accounts.css">
    <link rel="stylesheet" href="../../code-elta6ur-theme/assets/css/elementor-compat.css">
</head>
<body class="rtl">
<div class="cdt-site-wrapper">
    <header class="cdt-site-header" role="banner">
        <div class="cdt-container">
            <div class="cdt-header-inner">
                <div class="cdt-branding">
                    <a href="#" class="cdt-site-logo">
                        <img src="../../code-elta6ur-theme/screenshot.png" alt="كود التطور" style="max-height:44px; width:auto;">
                        <span class="cdt-logo-text">كود التطور</span>
                    </a>
                </div>
                <nav class="cdt-primary-nav">
                    <ul class="cdt-nav-menu">
                        <li><a href="#">الرئيسية</a></li>
                        <li><a href="#">الكورسات التعليمية</a></li>
                        <li><a href="#">الأدوات التقنية</a></li>
                        <li><a href="#">المدونة</a></li>
                        <li><a href="#">من نحن</a></li>
                    </ul>
                </nav>
                <div class="cdt-header-actions">
                    <a href="#" class="cdt-header-user-widget" title="حسابي">
                        <img src="../../code-elta6ur-theme/screenshot.png" alt="أنا" style="border-radius: 50%; width:26px; height:26px; vertical-align: middle;">
                        <span class="cdt-user-points-badge" style="background: rgba(1, 112, 185, 0.1); color: #0170B9; padding: 2px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 800;">💎 250</span>
                    </a>
                    <button class="cdt-search-toggle" aria-label="بحث">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                    <button class="cdt-mobile-toggle" aria-label="القائمة">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <aside class="cdt-mobile-drawer" id="cdt-mobile-drawer">
        <div>
            <div class="cdt-mobile-header">
                <span class="cdt-logo-text">كود التطور</span>
                <button class="cdt-mobile-close">✕</button>
            </div>
            <nav class="cdt-mobile-navigation">
                <ul class="cdt-mobile-menu">
                    <li><a href="#">الرئيسية</a></li>
                    <li><a href="#">الكورسات التعليمية</a></li>
                    <li><a href="#">الأدوات التقنية</a></li>
                    <li><a href="#">المدونة</a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <main id="main-content" class="cdt-main-content">';
}

function get_common_foot() {
    return '    </main>
    <footer class="cdt-site-footer">
        <div class="cdt-container">
            <div class="cdt-footer-grid">
                <div class="cdt-footer-col">
                    <h4>كود التطور | Code Elta6ur</h4>
                    <p>منصة برمجية تعليمية متخصصة في تأهيل المطورين لسوق العمل العربي والعالمي.</p>
                </div>
                <div class="cdt-footer-col">
                    <h4>روابط سريعة</h4>
                    <ul class="cdt-footer-links">
                        <li><a href="#">الكورسات</a></li>
                        <li><a href="#">المدونة</a></li>
                        <li><a href="#">الأدوات</a></li>
                    </ul>
                </div>
                <div class="cdt-footer-col">
                    <h4>المسارات</h4>
                    <ul class="cdt-footer-links">
                        <li><a href="#">مسار الويب</a></li>
                        <li><a href="#">مسار بايثون</a></li>
                    </ul>
                </div>
                <div class="cdt-footer-col">
                    <h4>تواصل معنا</h4>
                    <div class="cdt-footer-social">
                        <a href="#" class="cdt-social-link">Y</a>
                        <a href="#" class="cdt-social-link">T</a>
                    </div>
                </div>
            </div>
            <div class="cdt-footer-bottom">
                <div class="cdt-footer-bottom-inner">
                    <p>© 2026 كود التطور. برمجة وتصميم محمد أبو خشريف.</p>
                    <ul class="cdt-footer-policies">
                        <li><a href="#">سياسة الخصوصية</a></li>
                        <li><a href="#">الشروط والأحكام</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <button class="cdt-back-to-top">↑</button>
</div>
</body>
</html>';
}

// 1. FRONT PAGE FIXTURE
$frontPageHtml = get_common_head('الرئيسية') . '
    <!-- Hero Section -->
    <section class="cdt-hero">
        <div class="cdt-container">
            <div class="cdt-hero-content">
                <div class="cdt-hero-badge"><span class="cdt-badge cdt-badge-accent">✨ منصة كود التطور التعليمية</span></div>
                <h1 class="cdt-hero-title">تعلم البرمجة من الصفر حتى الاحتراف باللغة العربية</h1>
                <p class="cdt-hero-desc">دروس عملية ومشاريع حقيقية تأخذ بيدك من البداية وحتى بناء بورتفوليو قوي يؤهلك لسوق العمل بثقة تامة.</p>
                <div class="cdt-hero-buttons">
                    <a href="#" class="cdt-btn cdt-btn-primary cdt-btn-lg">ابدأ التعلم الآن مجاناً ←</a>
                    <a href="#" class="cdt-btn cdt-btn-secondary cdt-btn-lg">تصفّح المقالات التقنية</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="cdt-stats-bar">
        <div class="cdt-container">
            <div class="cdt-stats-grid">
                <div class="cdt-stat-item"><div class="cdt-stat-number">+370</div><div class="cdt-stat-label">درس ومقال برمجي مجاني</div></div>
                <div class="cdt-stat-item"><div class="cdt-stat-number">6+</div><div class="cdt-stat-label">مسارات تعليمية متكاملة</div></div>
                <div class="cdt-stat-item"><div class="cdt-stat-number">100%</div><div class="cdt-stat-label">محتوى عربي وعملي</div></div>
                <div class="cdt-stat-item"><div class="cdt-stat-number">0$</div><div class="cdt-stat-label">تعلم مجاني من الصفر</div></div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="cdt-section">
        <div class="cdt-container">
            <div class="cdt-section-header">
                <div class="cdt-section-badge"><span class="cdt-badge">لماذا كود التطور؟</span></div>
                <h2 class="cdt-section-title">منهجية برمجية تركز على فهمك وبناء مستقبلك</h2>
                <p class="cdt-section-desc">نساعدك على كسر حاجز الخوف من الأكواد وبناء مشاريع حقيقية تؤهلك لسوق العمل.</p>
            </div>
            <div class="cdt-grid-4">
                <div class="cdt-feature-card">
                    <div class="cdt-feature-icon">✓</div>
                    <h3 class="cdt-feature-title">تبسيط المفاهيم المعقدة</h3>
                    <p class="cdt-feature-desc">شرح تدريجي وواضح للأسس الهندسية بدون تعقيد أو مصطلحات غامضة.</p>
                </div>
                <div class="cdt-feature-card">
                    <div class="cdt-feature-icon">📁</div>
                    <h3 class="cdt-feature-title">تطبيقات ومشاريع حقيقية</h3>
                    <p class="cdt-feature-desc">تطبيق عملي خطوة بخطوة مع كل مفهوم لبناء بورتفوليو احترافي يُبهر الشركات.</p>
                </div>
                <div class="cdt-feature-card">
                    <div class="cdt-feature-icon">💼</div>
                    <h3 class="cdt-feature-title">التأهيل لسوق العمل</h3>
                    <p class="cdt-feature-desc">نصائح حقيقية في العمل الحر Freelancing وبناء المسار الوظيفي المربح.</p>
                </div>
                <div class="cdt-feature-card">
                    <div class="cdt-feature-icon">🤖</div>
                    <h3 class="cdt-feature-title">مواكبة الذكاء الاصطناعي</h3>
                    <p class="cdt-feature-desc">تعلم استثمار أدوات AI وتسريع إنتاجيتك كمبرمج حديث يواكب متطلبات العصر.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses -->
    <section class="cdt-section cdt-section-alt">
        <div class="cdt-container">
            <div class="cdt-section-header">
                <div class="cdt-section-badge"><span class="cdt-badge cdt-badge-accent">المسارات التعليمية</span></div>
                <h2 class="cdt-section-title">ابدأ التعلم من المسار الذي يناسبك</h2>
            </div>
            <div class="cdt-grid-3">
                <div class="cdt-course-card">
                    <div class="cdt-course-header">
                        <span class="cdt-course-badge">HTML5</span>
                        <h3 class="cdt-course-title"><a href="#">كورس HTML الشامل</a></h3>
                    </div>
                    <div class="cdt-course-body">
                        <p class="cdt-course-desc">تعلم لغة هيكلة الويب القياسية HTML5 مع أحدث المعايير وممارسات إمكانية الوصول.</p>
                        <div class="cdt-course-meta">
                            <span>17 درساً تدريبياً</span>
                            <a href="#" class="cdt-btn cdt-btn-primary cdt-btn-sm">ابدأ الدورة</a>
                        </div>
                    </div>
                </div>
                <div class="cdt-course-card">
                    <div class="cdt-course-header">
                        <span class="cdt-course-badge">CSS3</span>
                        <h3 class="cdt-course-title"><a href="#">كورس CSS المتقدم</a></h3>
                    </div>
                    <div class="cdt-course-body">
                        <p class="cdt-course-desc">أتقن Flexbox و CSS Grid والأنيميشن والتأثيرات والمتغيرات وبناء واجهات متجاوبة.</p>
                        <div class="cdt-course-meta">
                            <span>37 درساً تدريبياً</span>
                            <a href="#" class="cdt-btn cdt-btn-primary cdt-btn-sm">ابدأ الدورة</a>
                        </div>
                    </div>
                </div>
                <div class="cdt-course-card">
                    <div class="cdt-course-header">
                        <span class="cdt-course-badge">JavaScript</span>
                        <h3 class="cdt-course-title"><a href="#">كورس JavaScript الحديثة</a></h3>
                    </div>
                    <div class="cdt-course-body">
                        <p class="cdt-course-desc">افهم لغة البرمجة الأكثر انتشاراً عالمياً من الأساسيات حتى ES6+ والتعامل مع DOM.</p>
                        <div class="cdt-course-meta">
                            <span>32 درساً تدريبياً</span>
                            <a href="#" class="cdt-btn cdt-btn-primary cdt-btn-sm">ابدأ الدورة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Global CTA Banner -->
    <section class="cdt-cta-section">
        <div class="cdt-container">
            <div class="cdt-cta-inner">
                <h2 class="cdt-cta-title">جاهز لبدء رحلتك البرمجية معنا اليوم؟</h2>
                <p class="cdt-cta-desc">انضم إلى مئات الطلاب والمتعلمين واستفد من الدروس والكورسات المجانية بالكامل.</p>
                <a href="#" class="cdt-btn cdt-btn-cta cdt-btn-lg">ابدأ التعلم الآن مجاناً ←</a>
            </div>
        </div>
    </section>
' . get_common_foot();

file_put_contents($outputDir . '/front-page.html', $frontPageHtml);

// 2. ACCOUNT PAGE FIXTURE
$accountHtml = get_common_head('حسابي') . '
<div class="cdt-account-container">
    <div class="cdt-container">
        <!-- Account Hero -->
        <div class="cdt-account-hero">
            <div class="cdt-account-profile-main">
                <div class="cdt-account-avatar-wrap">
                    <img src="../../code-elta6ur-theme/screenshot.png" alt="محمد" class="cdt-account-avatar">
                </div>
                <div>
                    <h1 class="cdt-account-name">محمد أبو خشريف</h1>
                    <div class="cdt-account-meta">
                        <span>@mkhashreef</span>
                        <span>•</span>
                        <span>عضو منذ: 11 أكتوبر 2026</span>
                        <span>•</span>
                        <span style="background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 9999px; font-weight: 700;">🌱 مبتدئ</span>
                    </div>
                </div>
            </div>
            <div class="cdt-account-hero-stats">
                <div class="cdt-hero-stat-box">
                    <div class="cdt-hero-stat-val">350</div>
                    <div class="cdt-hero-stat-lbl">💎 رصيد النقاط</div>
                </div>
                <div class="cdt-hero-stat-box">
                    <div class="cdt-hero-stat-val">🔥 5</div>
                    <div class="cdt-hero-stat-lbl">أيام Streak</div>
                </div>
                <div class="cdt-hero-stat-box">
                    <div class="cdt-hero-stat-val">🎓 8</div>
                    <div class="cdt-hero-stat-lbl">دروس مكتملة</div>
                </div>
            </div>
        </div>

        <!-- Dashboard Layout -->
        <div class="cdt-dashboard-layout">
            <aside>
                <nav class="cdt-account-tabs-nav">
                    <button class="cdt-tab-link is-active">📊 نظرة عامة</button>
                    <button class="cdt-tab-link">💎 النقاط والمكافآت</button>
                    <button class="cdt-tab-link">🎓 تقدمي التعليمي</button>
                    <button class="cdt-tab-link">🏆 الإنجازات والشارات</button>
                    <button class="cdt-tab-link">📜 سجل النشاط</button>
                    <button class="cdt-tab-link">⚙️ إعدادات الحساب</button>
                </nav>
            </aside>
            <div class="cdt-tab-content">
                <div class="cdt-stats-grid-4">
                    <div class="cdt-stat-card"><div class="cdt-stat-icon">💎</div><div><div class="cdt-stat-number">350</div><div class="cdt-stat-label">إجمالي النقاط المكتسبة</div></div></div>
                    <div class="cdt-stat-card"><div class="cdt-stat-icon">🔥</div><div><div class="cdt-stat-number">5</div><div class="cdt-stat-label">سلسلة الـ Streak الحالية</div></div></div>
                    <div class="cdt-stat-card"><div class="cdt-stat-icon">📖</div><div><div class="cdt-stat-number">12</div><div class="cdt-stat-label">مقالات تمت قراءتها</div></div></div>
                    <div class="cdt-stat-card"><div class="cdt-stat-icon">🎓</div><div><div class="cdt-stat-number">8</div><div class="cdt-stat-label">دروس مكتملة</div></div></div>
                </div>

                <div class="cdt-level-card">
                    <h3>المستوى الحالي: مبتدئ</h3>
                    <div class="cdt-progress-track"><div class="cdt-progress-fill" style="width: 35%;"></div></div>
                    <p>تبقى 150 نقطة للوصول إلى المستوى 2: مستكشف 🧭</p>
                </div>

                <div class="cdt-badges-grid">
                    <div class="cdt-badge-item"><div class="cdt-badge-icon-lg">🚀</div><div class="cdt-badge-title">البداية الأولى</div><p class="cdt-badge-desc">سجلت أول دخول للمنصة بنجاح.</p></div>
                    <div class="cdt-badge-item"><div class="cdt-badge-icon-lg">📖</div><div class="cdt-badge-title">القارئ النهم</div><p class="cdt-badge-desc">قرأت 5 مقالات تعليمية كاملة.</p></div>
                    <div class="cdt-badge-item"><div class="cdt-badge-icon-lg">🔥</div><div class="cdt-badge-title">أسبوع الالتزام</div><p class="cdt-badge-desc">حققت 7 أيام Streak متواصلة.</p></div>
                    <div class="cdt-badge-item is-locked"><div class="cdt-badge-icon-lg">🎓</div><div class="cdt-badge-title">خريج كورس</div><p class="cdt-badge-desc">أكملت جميع دروس مسار تعليمي كامل.</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
' . get_common_foot();

file_put_contents($outputDir . '/account.html', $accountHtml);

// 3. COURSE SINGLE FIXTURE
$courseHtml = get_common_head('كورس HTML') . '
<div class="cdt-container" style="padding-block: 2rem;">
    <div class="cdt-card" style="padding: 2rem; margin-block-end: 2rem;">
        <span class="cdt-badge cdt-badge-accent">HTML5</span>
        <h1 style="font-size: 2rem; font-weight: 800; margin-block: 0.5rem 1rem;">كورس تعلم HTML5 بالعربي للمبتدئين</h1>
        <p style="color: #475569; line-height: 1.8;">تعلم لغة هيكلة الويب القياسية مع أحدث المعايير وممارسات إمكانية الوصول وبناء العناصر الدلالية خطوة بخطوة.</p>
    </div>

    <div class="cdt-lessons-list" style="display: grid; gap: 0.75rem;">
        <a href="#" class="cdt-lesson-item cdt-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; text-decoration: none;">
            <div style="display: flex; align-items: center; gap: 1rem; min-width: 0;">
                <span style="width: 38px; height: 38px; border-radius: 10px; background: rgba(1, 112, 185, 0.1); color: #0170B9; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">01</span>
                <div style="min-width: 0;">
                    <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">مقدمة في لغة HTML وهيكل الصفحة الأساسي</h3>
                    <div style="font-size: 0.8rem; color: #64748B; margin-block-start: 4px;">⏱️ 5 دقائق قراءة</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; color: #0170B9; font-weight: 700;">
                <span>ابدأ الشرح ←</span>
            </div>
        </a>
        <a href="#" class="cdt-lesson-item cdt-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; text-decoration: none;">
            <div style="display: flex; align-items: center; gap: 1rem; min-width: 0;">
                <span style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">✓</span>
                <div style="min-width: 0;">
                    <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">العناصر الدلالية Semantic HTML5 وأهميتها في SEO</h3>
                    <div style="font-size: 0.8rem; color: #64748B; margin-block-start: 4px;">⏱️ 8 دقائق قراءة</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; color: #10B981; font-weight: 700;">
                <span style="background: rgba(16, 185, 129, 0.1); padding: 4px 12px; border-radius: 9999px;">مكتمل</span>
            </div>
        </a>
    </div>
</div>
' . get_common_foot();

file_put_contents($outputDir . '/course.html', $courseHtml);

// 4. LOGIN PAGE FIXTURE
$loginHtml = get_common_head('تسجيل الدخول') . '
<div class="cdt-auth-wrapper">
    <div class="cdt-auth-card">
        <div class="cdt-auth-header">
            <div class="cdt-auth-icon-badge">🔒</div>
            <h1 class="cdt-auth-title">تسجيل الدخول إلى كود التطور</h1>
            <p class="cdt-auth-desc">أهلاً بك مجدداً! سجّل دخولك لمتابعة تقدمك التعليمي وجمع النقاط.</p>
        </div>
        <form class="cdt-auth-form">
            <div class="cdt-form-group">
                <label class="cdt-form-label">اسم المستخدم أو البريد الإلكتروني</label>
                <div class="cdt-input-wrapper">
                    <input type="text" class="cdt-form-control" placeholder="example@email.com">
                </div>
            </div>
            <div class="cdt-form-group">
                <label class="cdt-form-label">كلمة المرور</label>
                <div class="cdt-input-wrapper">
                    <input type="password" class="cdt-form-control" placeholder="••••••••">
                </div>
            </div>
            <div class="cdt-form-actions-inline">
                <label class="cdt-remember-me"><input type="checkbox"> تذكرني دائماً</label>
                <a href="#" class="cdt-link-muted">نسيت كلمة المرور؟</a>
            </div>
            <button type="submit" class="cdt-btn-primary">تسجيل الدخول الآن ←</button>
        </form>
    </div>
</div>
' . get_common_foot();

file_put_contents($outputDir . '/login.html', $loginHtml);

echo "Successfully generated test fixtures in tests/html-fixtures/\n";
