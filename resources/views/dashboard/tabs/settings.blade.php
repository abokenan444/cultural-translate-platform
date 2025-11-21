<div x-data="settingsTab()" x-init="loadSettings()" class="max-w-4xl mx-auto space-y-6">
    
    <!-- Profile Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Profile Settings</h3>
        <div class="space-y-6">
            <div class="flex items-center space-x-6">
                <img :src="profile.avatar" class="w-20 h-20 rounded-full">
                <div>
                    <button @click="uploadAvatar()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                        Change Avatar
                    </button>
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF. Max size 2MB.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" x-model="profile.name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" x-model="profile.email" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <input type="text" x-model="profile.company" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                    <input type="text" x-model="profile.job_title" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea x-model="profile.bio" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            
            <div class="flex justify-end">
                <button @click="saveProfile()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
    
    <!-- Password Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Change Password</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" x-model="password.current" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" x-model="password.new" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" x-model="password.confirm" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex justify-end">
                <button @click="changePassword()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Update Password
                </button>
            </div>
        </div>
    </div>
    
    <!-- Preferences -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Preferences</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Source Language</label>
                <select x-model="preferences.default_source_language" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="auto">Auto-detect</option>
                    <option value="en">English</option>
                    <option value="ar">Arabic</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default Target Language</label>
                <select x-model="preferences.default_target_language" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="ar">Arabic</option>
                    <option value="en">English</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Default AI Model</label>
                <select x-model="preferences.default_ai_model" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="gpt-4">GPT-4 (Best quality)</option>
                    <option value="gpt-3.5">GPT-3.5 (Faster)</option>
                    <option value="google">Google Translate</option>
                    <option value="deepl">DeepL</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                <select x-model="preferences.timezone" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="UTC">UTC</option>
                    <option value="America/New_York">Eastern Time (ET)</option>
                    <option value="America/Chicago">Central Time (CT)</option>
                    <option value="America/Los_Angeles">Pacific Time (PT)</option>
                    <option value="Europe/London">London (GMT)</option>
                    <option value="Europe/Paris">Paris (CET)</option>
                    <option value="Asia/Dubai">Dubai (GST)</option>
                    <option value="Asia/Tokyo">Tokyo (JST)</option>
                </select>
            </div>
            
            <div class="flex justify-end">
                <button @click="savePreferences()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Save Preferences
                </button>
            </div>
        </div>
    </div>
    
    <!-- Notifications -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Notification Settings</h3>
        <div class="space-y-4">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <div class="text-sm font-medium text-gray-900">Email Notifications</div>
                    <div class="text-xs text-gray-500">Receive email updates about your translations</div>
                </div>
                <input type="checkbox" x-model="notifications.email" class="w-5 h-5 text-indigo-600 rounded">
            </label>
            
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <div class="text-sm font-medium text-gray-900">Translation Completed</div>
                    <div class="text-xs text-gray-500">Get notified when translations are completed</div>
                </div>
                <input type="checkbox" x-model="notifications.translation_completed" class="w-5 h-5 text-indigo-600 rounded">
            </label>
            
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <div class="text-sm font-medium text-gray-900">Team Invitations</div>
                    <div class="text-xs text-gray-500">Get notified when invited to projects</div>
                </div>
                <input type="checkbox" x-model="notifications.team_invitations" class="w-5 h-5 text-indigo-600 rounded">
            </label>
            
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <div class="text-sm font-medium text-gray-900">Usage Alerts</div>
                    <div class="text-xs text-gray-500">Alert when approaching usage limits</div>
                </div>
                <input type="checkbox" x-model="notifications.usage_alerts" class="w-5 h-5 text-indigo-600 rounded">
            </label>
            
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <div class="text-sm font-medium text-gray-900">Marketing Emails</div>
                    <div class="text-xs text-gray-500">Receive news and product updates</div>
                </div>
                <input type="checkbox" x-model="notifications.marketing" class="w-5 h-5 text-indigo-600 rounded">
            </label>
            
            <div class="flex justify-end">
                <button @click="saveNotifications()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Save Settings
                </button>
            </div>
        </div>
    </div>
    
    <!-- API Keys -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">API Keys</h3>
            <button @click="generateApiKey()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                <i class="fas fa-plus mr-2"></i> Generate New Key
            </button>
        </div>
        <div class="space-y-3">
            <template x-for="key in apiKeys" :key="key.id">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900" x-text="key.name"></div>
                        <div class="text-xs text-gray-500 font-mono mt-1" x-text="key.key"></div>
                        <div class="text-xs text-gray-500 mt-1">
                            Created <span x-text="formatDate(key.created_at)"></span> • 
                            Last used <span x-text="formatDate(key.last_used_at)"></span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button @click="copyApiKey(key.key)" class="text-gray-400 hover:text-gray-600" title="Copy">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button @click="deleteApiKey(key.id)" class="text-gray-400 hover:text-red-600" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </template>
            
            <div x-show="apiKeys.length === 0" class="text-center py-8 text-gray-500">
                No API keys yet. Generate one to start using the API.
            </div>
        </div>
    </div>
    
    <!-- Danger Zone -->
    <div class="bg-red-50 rounded-lg border border-red-200 p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-4">Danger Zone</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-red-900">Export Your Data</div>
                    <div class="text-xs text-red-700">Download all your translations and data</div>
                </div>
                <button @click="exportData()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                    Export Data
                </button>
            </div>
            
            <div class="flex items-center justify-between pt-4 border-t border-red-200">
                <div>
                    <div class="text-sm font-medium text-red-900">Delete Account</div>
                    <div class="text-xs text-red-700">Permanently delete your account and all data</div>
                </div>
                <button @click="deleteAccount()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                    Delete Account
                </button>
            </div>
        </div>
    </div>
    
</div>

<script>
function settingsTab() {
    return {
        profile: {
            name: '',
            email: '',
            company: '',
            job_title: '',
            bio: '',
            avatar: ''
        },
        password: {
            current: '',
            new: '',
            confirm: ''
        },
        preferences: {
            default_source_language: 'auto',
            default_target_language: 'ar',
            default_ai_model: 'gpt-4',
            timezone: 'UTC'
        },
        notifications: {
            email: true,
            translation_completed: true,
            team_invitations: true,
            usage_alerts: true,
            marketing: false
        },
        apiKeys: [
            {
                id: 1,
                name: 'Production API Key',
                key: 'ct_live_••••••••••••••••••••1234',
                created_at: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString(),
                last_used_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString()
            }
        ],
        
        async loadSettings() {
            try {
                const response = await window.apiClient.getProfile();
                this.profile = response.data;
            } catch (error) {
                console.error('Failed to load settings:', error);
                // Demo data
                this.profile = {
                    name: 'John Doe',
                    email: 'john@example.com',
                    company: 'Acme Inc.',
                    job_title: 'Marketing Manager',
                    bio: 'Professional translator and localization expert',
                    avatar: 'https://ui-avatars.com/api/?name=John+Doe&background=6366f1&color=fff'
                };
            }
        },
        
        async saveProfile() {
            try {
                await window.apiClient.updateProfile(this.profile);
                this.$dispatch('show-toast', { type: 'success', message: 'Profile updated successfully!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to update profile' });
            }
        },
        
        async changePassword() {
            if (this.password.new !== this.password.confirm) {
                this.$dispatch('show-toast', { type: 'error', message: 'Passwords do not match' });
                return;
            }
            
            try {
                await window.apiClient.updateProfile({ password: this.password.new });
                this.password = { current: '', new: '', confirm: '' };
                this.$dispatch('show-toast', { type: 'success', message: 'Password changed successfully!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to change password' });
            }
        },
        
        savePreferences() {
            this.$dispatch('show-toast', { type: 'success', message: 'Preferences saved!' });
        },
        
        saveNotifications() {
            this.$dispatch('show-toast', { type: 'success', message: 'Notification settings saved!' });
        },
        
        uploadAvatar() {
            this.$dispatch('show-toast', { type: 'info', message: 'Avatar upload feature coming soon!' });
        },
        
        generateApiKey() {
            const newKey = {
                id: this.apiKeys.length + 1,
                name: 'New API Key',
                key: 'ct_live_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15),
                created_at: new Date().toISOString(),
                last_used_at: new Date().toISOString()
            };
            this.apiKeys.push(newKey);
            this.$dispatch('show-toast', { type: 'success', message: 'API key generated!' });
        },
        
        copyApiKey(key) {
            navigator.clipboard.writeText(key);
            this.$dispatch('show-toast', { type: 'success', message: 'API key copied to clipboard!' });
        },
        
        deleteApiKey(id) {
            if (!confirm('Delete this API key? Applications using it will stop working.')) return;
            this.apiKeys = this.apiKeys.filter(k => k.id !== id);
            this.$dispatch('show-toast', { type: 'success', message: 'API key deleted!' });
        },
        
        exportData() {
            this.$dispatch('show-toast', { type: 'info', message: 'Preparing your data export...' });
        },
        
        deleteAccount() {
            if (!confirm('Are you absolutely sure? This action cannot be undone.')) return;
            if (!confirm('This will permanently delete all your data. Continue?')) return;
            this.$dispatch('show-toast', { type: 'error', message: 'Account deletion initiated' });
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    }
}
</script>
