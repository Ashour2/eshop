<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, Category};

class ProductSeeder extends Seeder {
    public function run(): void {
        $frontend  = Category::where('slug', 'frontend')->first()->id;
        $backend   = Category::where('slug', 'backend')->first()->id;
        $webDesign = Category::where('slug', 'web-design')->first()->id;
        $mobile    = Category::where('slug', 'mobile-apps')->first()->id;
        $uiux      = Category::where('slug', 'ui-ux')->first()->id;
        $seo       = Category::where('slug', 'seo')->first()->id;
        $hosting   = Category::where('slug', 'hosting')->first()->id;

        $services = [
            // Frontend
            [
                'name_ar'        => 'تطوير واجهة React.js',
                'name_en'        => 'React.js Interface Development',
                'description_ar' => 'بناء واجهات تفاعلية وعصرية باستخدام React.js مع تصميم متجاوب ومتوافق مع جميع المتصفحات والأجهزة.',
                'description_en' => 'Build interactive and modern interfaces using React.js with responsive design compatible with all browsers and devices.',
                'price' => 800, 'category_id' => $frontend,
            ],
            [
                'name_ar'        => 'تطوير موقع Vue.js',
                'name_en'        => 'Vue.js Website Development',
                'description_ar' => 'تطوير تطبيقات ويب سريعة وخفيفة باستخدام Vue.js مع إدارة حالة متقدمة وتجربة مستخدم سلسة.',
                'description_en' => 'Develop fast and lightweight web applications using Vue.js with advanced state management and a smooth user experience.',
                'price' => 750, 'category_id' => $frontend,
            ],
            [
                'name_ar'        => 'تحويل تصميم إلى HTML/CSS',
                'name_en'        => 'Design to HTML/CSS Conversion',
                'description_ar' => 'تحويل تصاميم Figma أو PSD إلى صفحات ويب متجاوبة بكود نظيف ومعايير SEO.',
                'description_en' => 'Convert Figma or PSD designs into responsive web pages with clean code and SEO standards.',
                'price' => 300, 'category_id' => $frontend,
            ],

            // Backend
            [
                'name_ar'        => 'تطوير API بـ Laravel',
                'name_en'        => 'API Development with Laravel',
                'description_ar' => 'بناء RESTful API قوي وآمن باستخدام Laravel مع توثيق كامل وقاعدة بيانات محسّنة.',
                'description_en' => 'Build a powerful and secure RESTful API using Laravel with full documentation and an optimized database.',
                'price' => 1200, 'category_id' => $backend,
            ],
            [
                'name_ar'        => 'نظام إدارة محتوى مخصص',
                'name_en'        => 'Custom Content Management System',
                'description_ar' => 'تطوير CMS متكامل مصمم خصيصاً لاحتياجاتك مع لوحة تحكم سهلة الاستخدام.',
                'description_en' => 'Develop a complete CMS designed specifically for your needs with an easy-to-use control panel.',
                'price' => 1500, 'category_id' => $backend,
            ],
            [
                'name_ar'        => 'ربط بوابات الدفع الإلكتروني',
                'name_en'        => 'Payment Gateway Integration',
                'description_ar' => 'دمج بوابات الدفع مثل Stripe و PayPal مع نظامك بشكل آمن ومتوافق مع المعايير الدولية.',
                'description_en' => 'Integrate payment gateways like Stripe and PayPal with your system securely and in compliance with international standards.',
                'price' => 500, 'category_id' => $backend,
            ],

            // Web Design
            [
                'name_ar'        => 'تصميم موقع شركة احترافي',
                'name_en'        => 'Professional Company Website Design',
                'description_ar' => 'تصميم وتطوير موقع كامل لشركتك يعكس هويتك البصرية مع صفحات متعددة وتصميم عصري.',
                'description_en' => 'Design and develop a full website for your company reflecting your visual identity with multiple pages and a modern design.',
                'price' => 2000, 'category_id' => $webDesign,
            ],
            [
                'name_ar'        => 'تصميم متجر إلكتروني',
                'name_en'        => 'E-Commerce Store Design',
                'description_ar' => 'إنشاء متجر إلكتروني متكامل مع نظام إدارة المنتجات والطلبات والدفع الإلكتروني.',
                'description_en' => 'Create a full e-commerce store with product management, orders, and online payment processing.',
                'price' => 2500, 'category_id' => $webDesign,
            ],
            [
                'name_ar'        => 'تصميم صفحة هبوط Landing Page',
                'name_en'        => 'Landing Page Design',
                'description_ar' => 'تصميم صفحة هبوط جذابة ومحسّنة للتحويل مع أزرار CTA فعّالة وتصميم يجذب العملاء.',
                'description_en' => 'Design an attractive landing page optimized for conversion with effective CTA buttons and customer-engaging design.',
                'price' => 400, 'category_id' => $webDesign,
            ],

            // Mobile Apps
            [
                'name_ar'        => 'تطبيق Flutter متعدد المنصات',
                'name_en'        => 'Cross-Platform Flutter App',
                'description_ar' => 'تطوير تطبيق موبايل يعمل على Android و iOS بكود واحد باستخدام Flutter مع أداء عالي.',
                'description_en' => 'Develop a mobile app that runs on both Android and iOS from a single codebase using Flutter with high performance.',
                'price' => 3000, 'category_id' => $mobile,
            ],
            [
                'name_ar'        => 'تطبيق Android أصلي',
                'name_en'        => 'Native Android App',
                'description_ar' => 'بناء تطبيق Android بلغة Kotlin مع واجهة Material Design وأداء ممتاز.',
                'description_en' => 'Build a native Android app in Kotlin with a Material Design interface and excellent performance.',
                'price' => 2000, 'category_id' => $mobile,
            ],

            // UI/UX
            [
                'name_ar'        => 'تصميم واجهة مستخدم UI',
                'name_en'        => 'UI Interface Design',
                'description_ar' => 'تصميم واجهات مستخدم احترافية على Figma مع نظام تصميم متكامل وعناصر قابلة لإعادة الاستخدام.',
                'description_en' => 'Design professional user interfaces on Figma with an integrated design system and reusable components.',
                'price' => 600, 'category_id' => $uiux,
            ],
            [
                'name_ar'        => 'دراسة تجربة المستخدم UX',
                'name_en'        => 'UX Research & Analysis',
                'description_ar' => 'تحليل وتحسين تجربة المستخدم لموقعك أو تطبيقك مع تقرير مفصّل وتوصيات عملية.',
                'description_en' => 'Analyze and improve the user experience of your website or app with a detailed report and practical recommendations.',
                'price' => 800, 'category_id' => $uiux,
            ],

            // SEO
            [
                'name_ar'        => 'تحسين محركات البحث SEO',
                'name_en'        => 'SEO Optimization',
                'description_ar' => 'تحسين ظهور موقعك في نتائج البحث مع تحليل الكلمات المفتاحية وتحسين المحتوى والسرعة.',
                'description_en' => 'Improve your website visibility in search results with keyword analysis, content optimization, and speed improvements.',
                'price' => 500, 'category_id' => $seo,
            ],
            [
                'name_ar'        => 'حملة تسويق رقمي متكاملة',
                'name_en'        => 'Integrated Digital Marketing Campaign',
                'description_ar' => 'إدارة حملات Google Ads و Social Media مع تقارير أداء دورية واستراتيجية محتوى.',
                'description_en' => 'Manage Google Ads and Social Media campaigns with periodic performance reports and a content strategy.',
                'price' => 1000, 'category_id' => $seo,
            ],

            // Hosting
            [
                'name_ar'        => 'استضافة وإعداد سيرفر VPS',
                'name_en'        => 'VPS Server Hosting & Setup',
                'description_ar' => 'إعداد وتهيئة سيرفر VPS مع تثبيت SSL وتأمين الخادم وتحسين الأداء.',
                'description_en' => 'Setup and configure a VPS server with SSL installation, server hardening, and performance optimization.',
                'price' => 200, 'category_id' => $hosting,
            ],
            [
                'name_ar'        => 'صيانة ودعم تقني شهري',
                'name_en'        => 'Monthly Maintenance & Technical Support',
                'description_ar' => 'دعم تقني شهري يشمل تحديثات الأمان والنسخ الاحتياطي ومراقبة الأداء وإصلاح الأعطال.',
                'description_en' => 'Monthly technical support including security updates, backups, performance monitoring, and bug fixes.',
                'price' => 300, 'category_id' => $hosting,
            ],
        ];

        foreach ($services as $s) {
            Product::create(array_merge($s, [
                'name'        => $s['name_ar'],
                'description' => $s['description_ar'],
            ]));
        }
    }
}
