<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title_ar'    => 'نبني لك حضورك الرقمي',
                'title_en'    => 'We Build Your Digital Presence',
                'subtitle_ar' => 'مواقع وتطبيقات احترافية تعكس هوية شركتك وتصل لعملائك',
                'subtitle_en' => 'Professional websites and apps that reflect your brand',
                'bg'          => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1400&q=80',
            ],
            [
                'title_ar'    => 'فريق تقني متكامل',
                'title_en'    => 'A Complete Tech Team',
                'subtitle_ar' => 'مطورون ومصممون محترفون جاهزون لتحويل فكرتك إلى واقع رقمي',
                'subtitle_en' => 'Expert developers and designers ready to turn your idea into reality',
                'bg'          => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1400&q=80',
            ],
            [
                'title_ar'    => 'حلول برمجية مخصصة',
                'title_en'    => 'Custom Software Solutions',
                'subtitle_ar' => 'أنظمة إدارة وتطبيقات مصممة خصيصاً لاحتياجات عملك',
                'subtitle_en' => 'Management systems designed specifically for your business needs',
                'bg'          => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1400&q=80',
            ],
            [
                'title_ar'    => 'دعم تقني مستمر',
                'title_en'    => 'Continuous Technical Support',
                'subtitle_ar' => 'فريقنا معك على مدار الساعة لضمان استمرارية مشروعك',
                'subtitle_en' => 'Our team is available around the clock for your project',
                'bg'          => 'https://images.unsplash.com/photo-1559028012-481c04fa702d?w=1400&q=80',
            ],
        ];

        \App\Models\Setting::set('sliders', json_encode($sliders));
        \App\Models\Setting::set('whatsapp_number', '970594048945');
    }
}
