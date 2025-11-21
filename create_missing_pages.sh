#!/bin/bash

# Resources التي تحتاج Pages
resources=(
    "Feature" "Integration" "Plan" "User" "Company" "ApiProvider" 
    "Translation" "ApiKey" "Subscription" "Payment" "Invoice" 
    "UsageLog" "ActivityLog" "Notification" "SupportTicket" 
    "BlogPost" "Testimonial" "Faq" "FooterLink" "SocialLink" 
    "PlatformSetting" "EmailTemplate" "PlanFeature" "CompanyService" 
    "ApiRequestLog" "TranslationMemory" "Glossary" "FileUpload" 
    "Webhook" "AuditLog" "Coupon"
)

for resource in "${resources[@]}"
do
    RESOURCE_DIR="app/Filament/Resources/${resource}Resource"
    mkdir -p "${RESOURCE_DIR}/Pages"
    
    # List Page
    cat > "${RESOURCE_DIR}/Pages/List${resource}s.php" << EOF
<?php

namespace App\Filament\Resources\\${resource}Resource\Pages;

use App\Filament\Resources\\${resource}Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class List${resource}s extends ListRecords
{
    protected static string \$resource = ${resource}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
EOF

    # Create Page
    cat > "${RESOURCE_DIR}/Pages/Create${resource}.php" << EOF
<?php

namespace App\Filament\Resources\\${resource}Resource\Pages;

use App\Filament\Resources\\${resource}Resource;
use Filament\Resources\Pages\CreateRecord;

class Create${resource} extends CreateRecord
{
    protected static string \$resource = ${resource}Resource::class;
}
EOF

    # Edit Page
    cat > "${RESOURCE_DIR}/Pages/Edit${resource}.php" << EOF
<?php

namespace App\Filament\Resources\\${resource}Resource\Pages;

use App\Filament\Resources\\${resource}Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit${resource} extends EditRecord
{
    protected static string \$resource = ${resource}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
EOF

    echo "✓ Created pages for ${resource}Resource"
done

echo ""
echo "All Pages created successfully!"
