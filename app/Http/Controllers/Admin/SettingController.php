<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $sliders  = json_decode(\App\Models\Setting::get('sliders', '[]'), true) ?? [];
        $whatsapp = \App\Models\Setting::get('whatsapp_number', '970594048945');
        return view('admin.settings.index', compact('sliders', 'whatsapp'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required',
            'bg_file.*'       => 'nullable|image|max:3072',
        ]);

        // حفظ رقم الواتساب
        \App\Models\Setting::set('whatsapp_number', preg_replace('/\D/', '', $request->whatsapp_number));

        // حفظ السلايدرات
        $sliders      = [];
        $titles_ar    = $request->input('title_ar', []);
        $titles_en    = $request->input('title_en', []);
        $subtitles_ar = $request->input('subtitle_ar', []);
        $subtitles_en = $request->input('subtitle_en', []);
        $bgs          = $request->input('bg', []);        // URLs
        $existings    = $request->input('bg_existing', []); // الصور الحالية للـ file tab
        $files        = $request->file('bg_file', []);   // الملفات المرفوعة

        foreach ($titles_ar as $i => $title) {
            if (empty($title)) continue;

            // تحديد مصدر الصورة: URL أم ملف مرفوع أم الحالية
            $bg = '';
            if (!empty($files[$i])) {
                // رفع الملف
                $path = $files[$i]->store('slides', 'public');
                $bg   = Storage::url($path);
            } elseif (!empty($bgs[$i])) {
                // رابط URL
                $bg = $bgs[$i];
            } elseif (!empty($existings[$i])) {
                // احتفظ بالصورة الحالية
                $bg = $existings[$i];
            }

            $sliders[] = [
                'title_ar'    => $title,
                'title_en'    => $titles_en[$i] ?? '',
                'subtitle_ar' => $subtitles_ar[$i] ?? '',
                'subtitle_en' => $subtitles_en[$i] ?? '',
                'bg'          => $bg,
            ];
        }

        \App\Models\Setting::set('sliders', json_encode($sliders));

        return back()->with('success', 'تم حفظ الإعدادات بنجاح ✅');
    }
}
