/**
 * Auth Token Manager
 * Automatically generates and stores API token for authenticated users
 */

class AuthTokenManager {
    constructor() {
        this.tokenKey = 'auth_token';
    }

    /**
     * Check if user has valid token
     */
    hasToken() {
        return !!localStorage.getItem(this.tokenKey);
    }

    /**
     * Get stored token
     */
    getToken() {
        return localStorage.getItem(this.tokenKey);
    }

    /**
     * Store token
     */
    setToken(token) {
        localStorage.setItem(this.tokenKey, token);
    }

    /**
     * Clear token
     */
    clearToken() {
        localStorage.removeItem(this.tokenKey);
    }

    /**
     * Generate new API token from server
     */
    async generateToken() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/api-token/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include'
            });

            const data = await response.json();

            if (data.success && data.data.access_token) {
                this.setToken(data.data.access_token);
                console.log('✅ API Token generated and stored successfully');
                return data.data.access_token;
            } else {
                console.error('❌ Failed to generate API token:', data.message);
                return null;
            }
        } catch (error) {
            console.error('❌ Error generating API token:', error);
            return null;
        }
    }

    /**
     * Ensure user has valid token (generate if needed)
     */
    async ensureToken() {
        if (!this.hasToken()) {
            console.log('📝 No API token found, generating new one...');
            return await this.generateToken();
        }
        return this.getToken();
    }
}

// Create global instance
window.authTokenManager = new AuthTokenManager();

// Auto-generate token on page load for authenticated users
document.addEventListener('DOMContentLoaded', async function() {
    // Check if user is authenticated (has CSRF token)
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        await window.authTokenManager.ensureToken();
        
        // Update apiClient with the token
        if (window.apiClient) {
            const token = window.authTokenManager.getToken();
            if (token) {
                window.apiClient.setToken(token);
                console.log('✅ API Client configured with Bearer token');
            }
        }
    }
});
