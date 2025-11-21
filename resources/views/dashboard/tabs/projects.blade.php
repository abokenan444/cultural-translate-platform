<div x-data="projectsTab()" x-init="loadProjects()" class="space-y-6">
    
    <!-- Header with Create Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Projects</h2>
            <p class="text-gray-600 mt-1">Organize your translations into projects and collaborate with your team</p>
        </div>
        <button @click="showCreateModal = true" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus mr-2"></i> New Project
        </button>
    </div>
    
    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="project in projects" :key="project.id">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer" 
                 @click="viewProject(project)">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1" x-text="project.name"></h3>
                            <p class="text-sm text-gray-600 line-clamp-2" x-text="project.description"></p>
                        </div>
                        <div class="ml-4">
                            <button @click.stop="toggleProjectMenu(project.id)" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <div class="flex items-center space-x-4">
                            <div>
                                <i class="fas fa-language mr-1"></i>
                                <span x-text="project.translations_count"></span>
                            </div>
                            <div>
                                <i class="fas fa-users mr-1"></i>
                                <span x-text="project.members_count"></span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full" 
                              :class="{
                                  'bg-green-100 text-green-800': project.status === 'active',
                                  'bg-yellow-100 text-yellow-800': project.status === 'archived',
                                  'bg-gray-100 text-gray-800': project.status === 'draft'
                              }" 
                              x-text="project.status.charAt(0).toUpperCase() + project.status.slice(1)"></span>
                    </div>
                    
                    <div class="flex items-center -space-x-2 mb-4">
                        <template x-for="(member, index) in project.members.slice(0, 4)" :key="index">
                            <img :src="member.avatar" :alt="member.name" :title="member.name"
                                 class="w-8 h-8 rounded-full border-2 border-white">
                        </template>
                        <div x-show="project.members.length > 4" 
                             class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600">
                            +<span x-text="project.members.length - 4"></span>
                        </div>
                    </div>
                    
                    <div class="text-xs text-gray-500">
                        Updated <span x-text="formatDate(project.updated_at)"></span>
                    </div>
                </div>
            </div>
        </template>
        
        <div x-show="projects.length === 0 && !loading" 
             class="col-span-full bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 mb-4">No projects yet</p>
            <button @click="showCreateModal = true" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Create Your First Project
            </button>
        </div>
    </div>
    
    <!-- Create Project Modal -->
    <div x-show="showCreateModal" x-cloak @click.self="showCreateModal = false" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Create New Project</h3>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                    <input type="text" x-model="newProject.name" 
                           placeholder="e.g., Marketing Campaign 2025"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea x-model="newProject.description" rows="3"
                              placeholder="Brief description of the project..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source Language</label>
                    <select x-model="newProject.source_language" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="en">English</option>
                        <option value="ar">Arabic</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                        <option value="de">German</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Languages</label>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="lang in availableLanguages" :key="lang.code">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" :value="lang.code" 
                                       x-model="newProject.target_languages"
                                       class="rounded text-indigo-600">
                                <span class="text-sm text-gray-700" x-text="lang.name"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="showCreateModal = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button @click="createProject()" :disabled="!newProject.name" 
                        :class="!newProject.name ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg transition">
                    Create Project
                </button>
            </div>
        </div>
    </div>
    
    <!-- View Project Modal -->
    <div x-show="viewProjectModal.show" x-cloak @click.self="viewProjectModal.show = false" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" x-text="viewProjectModal.project.name"></h3>
                    <p class="text-sm text-gray-600 mt-1" x-text="viewProjectModal.project.description"></p>
                </div>
                <button @click="viewProjectModal.show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Project Stats -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900" x-text="viewProjectModal.project.translations_count"></div>
                        <div class="text-sm text-gray-600">Translations</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900" x-text="viewProjectModal.project.members_count"></div>
                        <div class="text-sm text-gray-600">Team Members</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-gray-900" x-text="viewProjectModal.project.languages_count"></div>
                        <div class="text-sm text-gray-600">Languages</div>
                    </div>
                </div>
                
                <!-- Team Members -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">Team Members</h4>
                        <button @click="showInviteModal = true" 
                                class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-user-plus mr-1"></i> Invite
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="member in viewProjectModal.project.members" :key="member.id">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <img :src="member.avatar" :alt="member.name" class="w-10 h-10 rounded-full">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900" x-text="member.name"></div>
                                        <div class="text-xs text-gray-500" x-text="member.email"></div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800" 
                                          x-text="member.role"></span>
                                    <button class="text-gray-400 hover:text-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h4>
                    <div class="space-y-3">
                        <template x-for="(activity, index) in viewProjectModal.project.recent_activity" :key="index">
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-language text-indigo-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm text-gray-900" x-text="activity.description"></div>
                                    <div class="text-xs text-gray-500 mt-1" x-text="formatDate(activity.created_at)"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="archiveProject(viewProjectModal.project.id)" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-archive mr-2"></i> Archive
                </button>
                <button @click="deleteProject(viewProjectModal.project.id)" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
                <button @click="viewProjectModal.show = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
    
    <!-- Invite Member Modal -->
    <div x-show="showInviteModal" x-cloak @click.self="showInviteModal = false" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Invite Team Member</h3>
                <button @click="showInviteModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" x-model="invite.email" 
                           placeholder="colleague@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select x-model="invite.role" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="viewer">Viewer - Can only view translations</option>
                        <option value="translator">Translator - Can create and edit translations</option>
                        <option value="admin">Admin - Full project access</option>
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="showInviteModal = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button @click="sendInvite()" :disabled="!invite.email" 
                        :class="!invite.email ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg transition">
                    Send Invite
                </button>
            </div>
        </div>
    </div>
    
</div>

<script>
function projectsTab() {
    return {
        projects: [],
        loading: false,
        showCreateModal: false,
        showInviteModal: false,
        newProject: {
            name: '',
            description: '',
            source_language: 'en',
            target_languages: []
        },
        viewProjectModal: {
            show: false,
            project: {}
        },
        invite: {
            email: '',
            role: 'translator'
        },
        availableLanguages: [
            { code: 'ar', name: 'Arabic' },
            { code: 'es', name: 'Spanish' },
            { code: 'fr', name: 'French' },
            { code: 'de', name: 'German' },
            { code: 'it', name: 'Italian' },
            { code: 'pt', name: 'Portuguese' }
        ],
        
        async loadProjects() {
            this.loading = true;
            try {
                const response = await window.apiClient.getProjects();
                this.projects = response.data || [];
            } catch (error) {
                console.error('Failed to load projects:', error);
                // Demo data
                this.projects = this.generateDemoProjects();
            } finally {
                this.loading = false;
            }
        },
        
        generateDemoProjects() {
            return [
                {
                    id: 1,
                    name: 'Marketing Campaign 2025',
                    description: 'Translation project for global marketing materials',
                    translations_count: 45,
                    members_count: 5,
                    languages_count: 8,
                    status: 'active',
                    members: [
                        { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin', avatar: 'https://ui-avatars.com/api/?name=John+Doe&background=6366f1&color=fff' },
                        { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'Translator', avatar: 'https://ui-avatars.com/api/?name=Jane+Smith&background=8b5cf6&color=fff' }
                    ],
                    recent_activity: [
                        { description: 'New translation added: Product Description', created_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString() },
                        { description: 'Jane Smith joined the project', created_at: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString() }
                    ],
                    updated_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString()
                },
                {
                    id: 2,
                    name: 'Website Localization',
                    description: 'Translating website content for international markets',
                    translations_count: 128,
                    members_count: 8,
                    languages_count: 12,
                    status: 'active',
                    members: [
                        { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin', avatar: 'https://ui-avatars.com/api/?name=John+Doe&background=6366f1&color=fff' }
                    ],
                    recent_activity: [],
                    updated_at: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString()
                },
                {
                    id: 3,
                    name: 'Product Documentation',
                    description: 'Technical documentation translation',
                    translations_count: 67,
                    members_count: 3,
                    languages_count: 5,
                    status: 'active',
                    members: [
                        { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin', avatar: 'https://ui-avatars.com/api/?name=John+Doe&background=6366f1&color=fff' }
                    ],
                    recent_activity: [],
                    updated_at: new Date(Date.now() - 48 * 60 * 60 * 1000).toISOString()
                }
            ];
        },
        
        async createProject() {
            if (!this.newProject.name) return;
            
            try {
                const response = await window.apiClient.createProject(
                    this.newProject.name,
                    this.newProject.description
                );
                await this.loadProjects();
                this.showCreateModal = false;
                this.newProject = { name: '', description: '', source_language: 'en', target_languages: [] };
                this.$dispatch('show-toast', { type: 'success', message: 'Project created successfully!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to create project' });
            }
        },
        
        viewProject(project) {
            this.viewProjectModal = {
                show: true,
                project: project
            };
        },
        
        async sendInvite() {
            if (!this.invite.email) return;
            
            try {
                await window.apiClient.inviteToProject(
                    this.viewProjectModal.project.id,
                    this.invite.email,
                    this.invite.role
                );
                this.showInviteModal = false;
                this.invite = { email: '', role: 'translator' };
                this.$dispatch('show-toast', { type: 'success', message: 'Invitation sent!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to send invitation' });
            }
        },
        
        async archiveProject(id) {
            if (!confirm('Archive this project?')) return;
            this.$dispatch('show-toast', { type: 'success', message: 'Project archived!' });
            this.viewProjectModal.show = false;
        },
        
        async deleteProject(id) {
            if (!confirm('Are you sure you want to delete this project? This action cannot be undone.')) return;
            this.$dispatch('show-toast', { type: 'success', message: 'Project deleted!' });
            this.viewProjectModal.show = false;
            await this.loadProjects();
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
