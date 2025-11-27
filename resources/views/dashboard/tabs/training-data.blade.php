<div class="training-data-container">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Training Data</h2>
            <p class="text-gray-600 mt-1">Rate your translations to improve our AI model</p>
        </div>
        <button onclick="exportTrainingData()" class="btn btn-primary">
            <i class="fas fa-download mr-2"></i>
            Export Data
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-label">Total Translations</div>
            <div class="stat-value" id="stat-total">-</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rated</div>
            <div class="stat-value" id="stat-rated">-</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Approved for Training</div>
            <div class="stat-value" id="stat-approved">-</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Your Contribution</div>
            <div class="stat-value" id="stat-contribution">-</div>
        </div>
    </div>

    <!-- Recent Translations for Rating -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold">Recent Translations - Rate to Improve AI</h3>
        </div>
        <div class="card-body">
            <div id="translations-list" class="space-y-4">
                <!-- Will be populated by JavaScript -->
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                    <p>Loading translations...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-label {
    font-size: 0.875rem;
    color: #6B7280;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
}

.translation-item {
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    background: #F9FAFB;
}

.translation-text {
    background: white;
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    border-left: 3px solid #6366F1;
}

.rating-stars {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
}

.star {
    font-size: 1.5rem;
    color: #D1D5DB;
    cursor: pointer;
    transition: color 0.2s;
}

.star:hover,
.star.active {
    color: #FBBF24;
}

.feedback-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #D1D5DB;
    border-radius: 6px;
    margin-top: 0.5rem;
}

.btn-submit-rating {
    background: #6366F1;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    margin-top: 0.5rem;
}

.btn-submit-rating:hover {
    background: #4F46E5;
}
</style>

<script>
let currentRatings = {};

// Load statistics
async function loadStatistics() {
    try {
        const response = await window.apiClient.getTrainingDataStatistics();
        if (response.success) {
            const stats = response.data;
            document.getElementById('stat-total').textContent = stats.user_stats.total_translations;
            document.getElementById('stat-rated').textContent = stats.user_stats.rated_translations;
            document.getElementById('stat-approved').textContent = stats.user_stats.approved_for_training;
            document.getElementById('stat-contribution').textContent = stats.user_stats.contribution_to_training;
        }
    } catch (error) {
        console.error('Failed to load statistics:', error);
    }
}

// Load recent translations
async function loadRecentTranslations() {
    try {
        const response = await window.apiClient.getRecentTranslationsForRating();
        if (response.success && response.data.length > 0) {
            renderTranslations(response.data);
        } else {
            document.getElementById('translations-list').innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-3xl mb-2 text-green-500"></i>
                    <p>All translations have been rated!</p>
                    <p class="text-sm mt-2">Start translating to contribute to our AI training.</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Failed to load translations:', error);
        document.getElementById('translations-list').innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-circle text-3xl mb-2"></i>
                <p>Failed to load translations</p>
            </div>
        `;
    }
}

// Render translations
function renderTranslations(translations) {
    const html = translations.map(t => `
        <div class="translation-item" data-id="${t.id}">
            <div class="flex justify-between items-start mb-3">
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">${t.source_language}</span> → 
                    <span class="font-semibold">${t.target_language}</span>
                </div>
                <div class="text-xs text-gray-400">${new Date(t.created_at).toLocaleDateString()}</div>
            </div>
            
            <div class="translation-text">
                <div class="text-sm text-gray-600 mb-2">Original:</div>
                <div class="text-gray-900">${escapeHtml(t.source_text)}</div>
            </div>
            
            <div class="translation-text">
                <div class="text-sm text-gray-600 mb-2">Translation:</div>
                <div class="text-gray-900">${escapeHtml(t.translated_text)}</div>
            </div>
            
            <div class="mt-4">
                <div class="text-sm font-semibold mb-2">Rate this translation:</div>
                <div class="rating-stars" data-translation-id="${t.id}">
                    ${[1,2,3,4,5].map(star => `
                        <span class="star" data-rating="${star}" onclick="setRating(${t.id}, ${star})">
                            ★
                        </span>
                    `).join('')}
                </div>
                <textarea 
                    class="feedback-input" 
                    id="feedback-${t.id}" 
                    placeholder="Optional: Add feedback to help improve the translation..."
                    rows="2"
                ></textarea>
                <button 
                    class="btn-submit-rating" 
                    onclick="submitRating(${t.id})"
                    disabled
                >
                    Submit Rating
                </button>
            </div>
        </div>
    `).join('');
    
    document.getElementById('translations-list').innerHTML = html;
}

// Set rating
function setRating(translationId, rating) {
    currentRatings[translationId] = rating;
    
    // Update stars
    const stars = document.querySelectorAll(`[data-translation-id="${translationId}"] .star`);
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
    
    // Enable submit button
    const btn = document.querySelector(`[data-id="${translationId}"] .btn-submit-rating`);
    btn.disabled = false;
}

// Submit rating
async function submitRating(translationId) {
    const rating = currentRatings[translationId];
    if (!rating) {
        alert('Please select a rating');
        return;
    }
    
    const feedback = document.getElementById(`feedback-${translationId}`).value;
    
    try {
        const response = await window.apiClient.rateTranslation(translationId, rating, feedback);
        if (response.success) {
            // Remove rated translation from list
            document.querySelector(`[data-id="${translationId}"]`).remove();
            
            // Reload statistics
            loadStatistics();
            
            // Show success message
            showNotification('Translation rated successfully!', 'success');
            
            // Check if list is empty
            if (document.querySelectorAll('.translation-item').length === 0) {
                loadRecentTranslations();
            }
        }
    } catch (error) {
        console.error('Failed to submit rating:', error);
        showNotification('Failed to submit rating', 'error');
    }
}

// Export training data
async function exportTrainingData() {
    try {
        await window.apiClient.exportTrainingData();
        showNotification('Export started! Download will begin shortly.', 'success');
    } catch (error) {
        console.error('Failed to export:', error);
        showNotification('Failed to export data', 'error');
    }
}

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type = 'info') {
    // Simple notification - can be enhanced
    alert(message);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadStatistics();
    loadRecentTranslations();
});
</script>
