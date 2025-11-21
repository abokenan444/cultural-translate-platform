<x-filament::page>
    <div class="space-y-4">
        <h2 class="text-lg font-semibold">
            AI Server Agent Console
        </h2>

        <p class="text-sm text-gray-600">
            هذه الصفحة مخصصة للتحكم في خادم Laravel عبر الـ AI Server Agent
            (تشغيل أوامر آمنة مثل optimize, deploy, migrate، وغيرها).
        </p>

        <p class="text-sm text-gray-600">
            النسخة الحالية هي واجهة أولية. الأزرار والنماذج التي تستدعي
            المسارات:
            <code>/api/health</code>,
            <code>/run-command</code>,
            <code>/deploy</code>,
            <code>/optimize</code>
            سنضيفها في خطوة لاحقة بعد التأكد من عمل الصفحات والروت.
        </p>
    </div>
</x-filament::page>
