<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'الباقة المجانية',
                'slug' => 'free',
                'description' => 'مثالية للأفراد والاستخدام الشخصي',
                'price' => 0,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'tokens_limit' => 1000,
                'features' => [
                    'ترجمة نصوص قصيرة',
                    'دعم 13 لغة',
                    'واجهة سهلة الاستخدام',
                ],
                'max_projects' => 1,
                'max_team_members' => 1,
                'api_access' => false,
                'priority_support' => false,
                'custom_integrations' => false,
                'is_popular' => false,
                'is_custom' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'الباقة الاحترافية',
                'slug' => 'professional',
                'description' => 'للمحترفين والشركات الصغيرة',
                'price' => 29.99,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'tokens_limit' => 50000,
                'features' => [
                    'ترجمة غير محدودة',
                    'دعم 13 لغة',
                    'الوصول إلى API',
                    'تقارير مفصلة',
                    'دعم فني سريع',
                ],
                'max_projects' => 5,
                'max_team_members' => 3,
                'api_access' => true,
                'priority_support' => false,
                'custom_integrations' => false,
                'is_popular' => true,
                'is_custom' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'باقة الأعمال',
                'slug' => 'business',
                'description' => 'للشركات المتوسطة والكبيرة',
                'price' => 99.99,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'tokens_limit' => 200000,
                'features' => [
                    'ترجمة غير محدودة',
                    'دعم جميع اللغات',
                    'API متقدم',
                    'تكاملات مخصصة',
                    'دعم فني مميز 24/7',
                    'مدير حساب مخصص',
                    'تدريب الفريق',
                ],
                'max_projects' => 20,
                'max_team_members' => 10,
                'api_access' => true,
                'priority_support' => true,
                'custom_integrations' => true,
                'is_popular' => false,
                'is_custom' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'باقة مخصصة',
                'slug' => 'custom',
                'description' => 'حلول مخصصة للمؤسسات الكبرى',
                'price' => 0,
                'currency' => 'USD',
                'billing_period' => 'monthly',
                'tokens_limit' => 999999999,
                'features' => [
                    'حلول مخصصة حسب احتياجاتك',
                    'توكنات غير محدودة',
                    'دعم فني مخصص',
                    'تكاملات متقدمة',
                    'SLA مضمون',
                    'تدريب شامل',
                ],
                'max_projects' => 999,
                'max_team_members' => 999,
                'api_access' => true,
                'priority_support' => true,
                'custom_integrations' => true,
                'is_popular' => false,
                'is_custom' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('Subscription plans seeded successfully!');
    }
}
