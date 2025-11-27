<div x-data="subscriptionTab()" x-init="loadSubscription()" class="max-w-4xl mx-auto space-y-6">
    
    <!-- Current Plan -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm opacity-90 mb-2">Current Plan</div>
                <h2 class="text-3xl font-bold" x-text="subscription.plan_name"></h2>
                <p class="mt-2 opacity-90" x-text="'$' + subscription.price + ' / ' + subscription.billing_cycle"></p>
            </div>
            <div class="text-right">
                <div class="text-sm opacity-90 mb-2">Renews on</div>
                <div class="text-xl font-semibold" x-text="formatDate(subscription.renews_at)"></div>
            </div>
        </div>
    </div>
    
    <!-- Usage Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-600 mb-2">Characters Used</div>
            <div class="text-2xl font-bold text-gray-900 mb-2" x-text="formatNumber(usage.characters_used)"></div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-indigo-600" :style="`width: ${(usage.characters_used / usage.characters_limit * 100)}%`"></div>
            </div>
            <div class="text-xs text-gray-500">of <span x-text="formatNumber(usage.characters_limit)"></span> limit</div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-600 mb-2">API Calls</div>
            <div class="text-2xl font-bold text-gray-900 mb-2" x-text="formatNumber(usage.api_calls)"></div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full bg-purple-600" :style="`width: ${(usage.api_calls / usage.api_limit * 100)}%`"></div>
            </div>
            <div class="text-xs text-gray-500">of <span x-text="formatNumber(usage.api_limit)"></span> limit</div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="text-sm font-medium text-gray-600 mb-2">Team Members</div>
            <div class="text-2xl font-bold text-gray-900 mb-2" x-text="usage.team_members"></div>
            <div class="text-xs text-gray-500">of <span x-text="usage.team_limit"></span> seats</div>
        </div>
    </div>
    
    <!-- Available Plans -->
    <div>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Available Plans</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <template x-for="plan in plans" :key="plan.id">
                <div class="bg-white rounded-lg shadow-sm border-2 transition hover:shadow-md" 
                     :class="plan.id === subscription.plan_id ? 'border-indigo-600' : 'border-gray-200'">
                    <div class="p-6">
                        <div x-show="plan.id === subscription.plan_id" class="inline-block px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full mb-4">
                            Current Plan
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2" x-text="plan.name"></h4>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-gray-900" x-text="'$' + plan.price"></span>
                            <span class="text-gray-600">/ month</span>
                        </div>
                        <ul class="space-y-3 mb-6">
                            <template x-for="feature in plan.features" :key="feature">
                                <li class="flex items-start space-x-2 text-sm text-gray-700">
                                    <i class="fas fa-check text-green-600 mt-0.5"></i>
                                    <span x-text="feature"></span>
                                </li>
                            </template>
                        </ul>
                        <button @click="changePlan(plan.id)" 
                                :disabled="plan.id === subscription.plan_id"
                                :class="plan.id === subscription.plan_id ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
                                class="w-full px-4 py-2 rounded-lg font-semibold transition">
                            <span x-text="plan.id === subscription.plan_id ? 'Current Plan' : (plan.price > subscription.price ? 'Upgrade' : 'Downgrade')"></span>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Billing History -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Billing History</h3>
            <button @click="downloadInvoices()" class="text-sm text-indigo-600 hover:text-indigo-700">
                <i class="fas fa-download mr-1"></i> Download All
            </button>
        </div>
        <div class="divide-y divide-gray-200">
            <template x-for="invoice in invoices" :key="invoice.id">
                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900" x-text="invoice.description"></div>
                        <div class="text-sm text-gray-500 mt-1" x-text="formatDate(invoice.date)"></div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900" x-text="'$' + invoice.amount"></div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full" 
                                  :class="{
                                      'bg-green-100 text-green-800': invoice.status === 'paid',
                                      'bg-yellow-100 text-yellow-800': invoice.status === 'pending',
                                      'bg-red-100 text-red-800': invoice.status === 'failed'
                                  }" 
                                  x-text="invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)"></span>
                        </div>
                        <button @click="downloadInvoice(invoice.id)" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Payment Method -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                <div>
                    <div class="text-sm font-medium text-gray-900">•••• •••• •••• <span x-text="paymentMethod.last4"></span></div>
                    <div class="text-xs text-gray-500">Expires <span x-text="paymentMethod.exp_month + '/' + paymentMethod.exp_year"></span></div>
                </div>
            </div>
            <button @click="updatePaymentMethod()" class="text-sm text-indigo-600 hover:text-indigo-700">
                Update
            </button>
        </div>
    </div>
    
    <!-- Cancel Subscription -->
    <div class="bg-red-50 rounded-lg border border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-2">Cancel Subscription</h3>
        <p class="text-sm text-red-700 mb-4">Once you cancel, you'll lose access to all premium features at the end of your billing period.</p>
        <button @click="cancelSubscription()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            Cancel Subscription
        </button>
    </div>
    
</div>

<script>
function subscriptionTab() {
    return {
        subscription: {
            plan_id: 2,
            plan_name: 'Professional',
            price: 49,
            billing_cycle: 'month',
            renews_at: new Date(Date.now() + 15 * 24 * 60 * 60 * 1000).toISOString()
        },
        usage: {
            characters_used: 45200,
            characters_limit: 100000,
            api_calls: 1234,
            api_limit: 10000,
            team_members: 5,
            team_limit: 10
        },
        plans: [
            {
                id: 1,
                name: 'Starter',
                price: 19,
                features: [
                    '50,000 characters/month',
                    '5,000 API calls',
                    '3 team members',
                    'Basic support',
                    'All languages'
                ]
            },
            {
                id: 2,
                name: 'Professional',
                price: 49,
                features: [
                    '100,000 characters/month',
                    '10,000 API calls',
                    '10 team members',
                    'Priority support',
                    'All languages',
                    'Custom glossary'
                ]
            },
            {
                id: 3,
                name: 'Enterprise',
                price: 199,
                features: [
                    'Unlimited characters',
                    'Unlimited API calls',
                    'Unlimited team members',
                    '24/7 dedicated support',
                    'All languages',
                    'Custom AI models',
                    'SLA guarantee'
                ]
            }
        ],
        invoices: [
            { id: 1, description: 'Professional Plan - January 2025', amount: 49, status: 'paid', date: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString() },
            { id: 2, description: 'Professional Plan - December 2024', amount: 49, status: 'paid', date: new Date(Date.now() - 60 * 24 * 60 * 60 * 1000).toISOString() },
            { id: 3, description: 'Professional Plan - November 2024', amount: 49, status: 'paid', date: new Date(Date.now() - 90 * 24 * 60 * 60 * 1000).toISOString() }
        ],
        paymentMethod: {
            last4: '4242',
            exp_month: '12',
            exp_year: '2025'
        },
        
        async loadSubscription() {
            try {
                const response = await window.apiClient.getSubscription();
                this.subscription = response.data;
                const usageResponse = await window.apiClient.getUsage();
                this.usage = usageResponse.data;
                
                // Load available plans from API
                const plansResponse = await window.apiClient.getPlans();
                if (plansResponse.success && plansResponse.data) {
                    this.plans = plansResponse.data.map(plan => ({
                        id: plan.id,
                        name: plan.name,
                        price: plan.price,
                        features: [
                            `${plan.tokens_limit || 'Unlimited'} tokens/month`,
                            `${plan.max_team_members || 'Unlimited'} team members`,
                            plan.features || 'All features included'
                        ]
                    }));
                }
            } catch (error) {
                console.error('Failed to load subscription:', error);
            }
        },
        
        async changePlan(planId) {
            if (!confirm('Change your subscription plan?')) return;
            
            try {
                await window.apiClient.subscribe(planId, 'card');
                await this.loadSubscription();
                this.$dispatch('show-toast', { type: 'success', message: 'Plan changed successfully!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to change plan' });
            }
        },
        
        async cancelSubscription() {
            if (!confirm('Are you sure you want to cancel your subscription?')) return;
            
            try {
                await window.apiClient.cancelSubscription();
                this.$dispatch('show-toast', { type: 'success', message: 'Subscription cancelled' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to cancel subscription' });
            }
        },
        
        updatePaymentMethod() {
            this.$dispatch('show-toast', { type: 'info', message: 'Redirecting to payment portal...' });
        },
        
        downloadInvoice(id) {
            this.$dispatch('show-toast', { type: 'info', message: 'Downloading invoice...' });
        },
        
        downloadInvoices() {
            this.$dispatch('show-toast', { type: 'info', message: 'Downloading all invoices...' });
        },
        
        formatNumber(num) {
            return num.toLocaleString();
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>
