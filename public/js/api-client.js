/**
 * API Client for CulturalTranslate Platform
 * Handles all API communications with backend
 */

class APIClient {
    constructor(baseURL = '/api/v1') {
        this.baseURL = baseURL;
        this.token = localStorage.getItem('auth_token');
    }

    /**
     * Set authentication token
     */
    setToken(token) {
        this.token = token;
        localStorage.setItem('auth_token', token);
    }

    /**
     * Clear authentication token
     */
    clearToken() {
        this.token = null;
        localStorage.removeItem('auth_token');
    }

    /**
     * Get headers for API requests
     */
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            ...options,
            headers: {
                ...this.getHeaders(),
                ...options.headers,
            },
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Authentication APIs
    async register(name, email, password) {
        return this.request('/register', {
            method: 'POST',
            body: JSON.stringify({ name, email, password }),
        });
    }

    async login(email, password) {
        const response = await this.request('/login', {
            method: 'POST',
            body: JSON.stringify({ email, password }),
        });
        
        if (response.token) {
            this.setToken(response.token);
        }
        
        return response;
    }

    async logout() {
        const response = await this.request('/logout', { method: 'POST' });
        this.clearToken();
        return response;
    }

    async getProfile() {
        return this.request('/me');
    }

    async updateProfile(data) {
        return this.request('/profile', {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    }

    // Translation APIs
    async translate(text, sourceLanguage, targetLanguage, options = {}) {
        return this.request('/translate', {
            method: 'POST',
            body: JSON.stringify({
                text,
                source_language: sourceLanguage,
                target_language: targetLanguage,
                ...options,
            }),
        });
    }

    async batchTranslate(texts, sourceLanguage, targetLanguage, options = {}) {
        return this.request('/translate/batch', {
            method: 'POST',
            body: JSON.stringify({
                texts,
                source_language: sourceLanguage,
                target_language: targetLanguage,
                ...options,
            }),
        });
    }

    async getTranslations(page = 1, perPage = 10) {
        return this.request(`/translations?page=${page}&per_page=${perPage}`);
    }

    async getTranslation(id) {
        return this.request(`/translations/${id}`);
    }

    async deleteTranslation(id) {
        return this.request(`/translations/${id}`, { method: 'DELETE' });
    }

    // Voice Translation APIs
    async voiceTranslate(audioBlob, sourceLanguage, targetLanguage) {
        const formData = new FormData();
        formData.append('audio', audioBlob);
        formData.append('source_language', sourceLanguage);
        formData.append('target_language', targetLanguage);

        return fetch(`${this.baseURL}/voice/translate`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
            },
            body: formData,
        }).then(res => res.json());
    }

    async textToSpeech(text, language, voice = 'alloy') {
        return this.request('/voice/text-to-speech', {
            method: 'POST',
            body: JSON.stringify({ text, language, voice }),
        });
    }

    // Visual Translation APIs
    async translateImage(imageFile, sourceLanguage, targetLanguage) {
        const formData = new FormData();
        formData.append('image', imageFile);
        formData.append('source_language', sourceLanguage);
        formData.append('target_language', targetLanguage);

        return fetch(`${this.baseURL}/visual/image`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
            },
            body: formData,
        }).then(res => res.json());
    }

    // Subscription APIs
    async getPlans() {
        return this.request('/plans');
    }

    async subscribe(planId, paymentMethod) {
        return this.request('/subscribe', {
            method: 'POST',
            body: JSON.stringify({ plan_id: planId, payment_method: paymentMethod }),
        });
    }

    async getSubscription() {
        return this.request('/subscription');
    }

    async getUsage() {
        return this.request('/usage');
    }

    async cancelSubscription() {
        return this.request('/cancel', { method: 'POST' });
    }

    // Analytics APIs
    async getDashboard() {
        return this.request('/analytics/dashboard');
    }

    async getInsights() {
        return this.request('/analytics/insights');
    }

    async getUsageStats() {
        return this.request('/analytics/usage');
    }

    // Collaboration APIs
    async createProject(name, description) {
        return this.request('/projects', {
            method: 'POST',
            body: JSON.stringify({ name, description }),
        });
    }

    async getProjects() {
        return this.request('/projects');
    }

    async inviteToProject(projectId, email, role) {
        return this.request(`/projects/${projectId}/invite`, {
            method: 'POST',
            body: JSON.stringify({ email, role }),
        });
    }
}

// Create global instance
window.apiClient = new APIClient();
