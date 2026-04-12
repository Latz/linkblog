// Load saved settings
async function loadSettings() {
    const settings = await chrome.storage.sync.get(['apiEndpoint', 'apiKey']);

    if (settings.apiEndpoint) {
        document.getElementById('apiEndpoint').value = settings.apiEndpoint;
    }

    if (settings.apiKey) {
        document.getElementById('apiKey').value = settings.apiKey;
    }
}

// Show message
function showMessage(text, type) {
    const messageEl = document.getElementById('message');
    messageEl.textContent = text;
    messageEl.className = `message ${type}`;
    messageEl.style.display = 'block';

    setTimeout(() => {
        messageEl.style.display = 'none';
    }, 5000);
}

// Test API connection
async function testConnection(apiEndpoint, apiKey) {
    try {
        const response = await fetch(`${apiEndpoint}/categories`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-LinkBlog-API-Key': apiKey
            }
        });

        return response.ok;
    } catch (error) {
        console.error('Connection test failed:', error);
        return false;
    }
}

// Handle form submission
async function handleSubmit(e) {
    e.preventDefault();

    const apiEndpoint = document.getElementById('apiEndpoint').value.trim();
    const apiKey = document.getElementById('apiKey').value.trim();

    // Remove trailing slash from endpoint if present
    const cleanEndpoint = apiEndpoint.replace(/\/$/, '');

    // Test connection
    const isConnected = await testConnection(cleanEndpoint, apiKey);

    if (!isConnected) {
        showMessage('Failed to connect to WordPress. Please check your credentials.', 'error');
        return;
    }

    // Save settings
    try {
        await chrome.storage.sync.set({
            apiEndpoint: cleanEndpoint,
            apiKey: apiKey
        });

        showMessage('Settings saved successfully! Connection verified.', 'success');
    } catch (error) {
        console.error('Error saving settings:', error);
        showMessage('Failed to save settings. Please try again.', 'error');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadSettings();
    document.getElementById('settingsForm').addEventListener('submit', handleSubmit);
});
