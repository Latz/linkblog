// Check if settings are configured
async function checkSettings() {
    const settings = await chrome.storage.sync.get(['apiEndpoint', 'apiKey']);

    if (!settings.apiEndpoint || !settings.apiKey) {
        document.getElementById('setupMessage').style.display = 'block';
        document.getElementById('mainForm').style.display = 'none';
        return false;
    }

    document.getElementById('setupMessage').style.display = 'none';
    document.getElementById('mainForm').style.display = 'block';
    return true;
}

// Extract description from page meta tags (runs inside the tab's context)
function extractPageDescription() {
    const candidates = [
        ['property', 'og:description'],
        ['name',     'description'],
        ['name',     'twitter:description'],
        ['name',     'og:description'],
        ['http-equiv', 'description'],
    ];

    for (const [attr, value] of candidates) {
        const nodes = document.querySelectorAll(`[${attr}="${value}" i]`);
        for (const node of nodes) {
            const text = (node.content || '').trim().replace(/(^\n+)|(\n+$)/g, '');
            if (text) return text;
        }
    }
    return '';
}

// Load current page info
async function loadPageInfo() {
    try {
        const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });

        if (tab) {
            document.getElementById('title').value = tab.title || '';
            document.getElementById('url').value = tab.url || '';

            // Fill description from page meta tags
            try {
                const [{ result }] = await chrome.scripting.executeScript({
                    target: { tabId: tab.id },
                    func: extractPageDescription,
                });
                if (result) {
                    document.getElementById('content').value = result;
                }
            } catch (_) {
                // Silently skip on restricted pages (e.g. chrome://)
            }
        }
    } catch (error) {
        console.error('Error loading page info:', error);
    }
}

// Render categories to DOM
function renderCategories(categories) {
    const categoriesList = document.getElementById('categoriesList');

    if (!categories || categories.length === 0) {
        categoriesList.innerHTML = '<div class="loading">No categories available</div>';
        return;
    }

    categoriesList.innerHTML = '';
    const fragment = document.createDocumentFragment();

    categories.forEach(category => {
        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = 'linkblog_category';
        radio.id = `cat-${category.id}`;
        radio.value = category.name;
        radio.className = 'category-checkbox';

        const label = document.createElement('label');
        label.htmlFor = `cat-${category.id}`;
        label.textContent = category.name;
        label.className = 'category-label';

        fragment.appendChild(radio);
        fragment.appendChild(label);
    });

    categoriesList.appendChild(fragment);
}

// Load categories from WordPress (with caching)
async function loadCategories() {
    const categoriesList = document.getElementById('categoriesList');

    try {
        // Check cache first (5 minutes expiration)
        const cached = await chrome.storage.local.get(['categories', 'categoriesTimestamp']);
        const cacheAge = Date.now() - (cached.categoriesTimestamp || 0);

        if (cached.categories && cacheAge < 5 * 60 * 1000) {
            // Use cached data
            renderCategories(cached.categories);
            return;
        }

        // Fetch fresh data
        const settings = await chrome.storage.sync.get(['apiEndpoint', 'apiKey']);

        const response = await fetch(`${settings.apiEndpoint}/categories`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-LinkBlog-API-Key': settings.apiKey
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load categories');
        }

        const categories = await response.json();

        // Cache the results
        await chrome.storage.local.set({
            categories: categories,
            categoriesTimestamp: Date.now()
        });

        renderCategories(categories);
    } catch (error) {
        console.error('Error loading categories:', error);
        categoriesList.innerHTML = '<div class="loading">Failed to load categories</div>';
    }
}

// Show message
function showMessage(text, type) {
    const messageEl = document.getElementById('message');
    messageEl.textContent = text;
    messageEl.className = `message ${type} show`;

    setTimeout(() => {
        messageEl.classList.remove('show');
    }, 5000);
}

// Handle form submission
async function handleSubmit(e) {
    e.preventDefault();

    const saveBtn = document.getElementById('saveBtn');
    const btnText = saveBtn.querySelector('.btn-text');
    const btnLoading = saveBtn.querySelector('.btn-loading');

    saveBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';

    try {
        const settings = await chrome.storage.sync.get(['apiEndpoint', 'apiKey']);

        // Get selected categories
        const selectedRadio = document.querySelector('.category-checkbox:checked');
        const selectedCategories = selectedRadio ? [selectedRadio.value] : [];

        if (selectedCategories.length === 0) {
            showMessage('Please select at least one category before saving.', 'error');
            saveBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
            return;
        }

        // Get tags from Tagify
        const tags = tagify ? tagify.value.map(tag => tag.value).join(', ') : document.getElementById('tags').value;

        const formData = {
            title: document.getElementById('title').value,
            url: document.getElementById('url').value,
            content: document.getElementById('content').value,
            categories: selectedCategories,
            tags: tags
        };

        const response = await fetch(`${settings.apiEndpoint}/add-link`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-LinkBlog-API-Key': settings.apiKey
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to save link');
        }

        showMessage(result.message || 'Link saved successfully!', 'success');

        // Clear form except title and URL (in case user wants to add another)
        document.getElementById('content').value = '';
        if (tagify) {
            tagify.removeAllTags();
        } else {
            document.getElementById('tags').value = '';
        }
        const checked = document.querySelector('.category-checkbox:checked');
        if (checked) checked.checked = false;

    } catch (error) {
        console.error('Error saving link:', error);
        showMessage(error.message || 'Failed to save link. Please check your settings.', 'error');
    } finally {
        saveBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
    }
}

// Open settings page
function openSettings() {
    chrome.runtime.openOptionsPage();
}

// Tagify instance
let tagify;

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    // Add event listeners immediately
    document.getElementById('linkForm')?.addEventListener('submit', handleSubmit);
    document.getElementById('settingsBtn')?.addEventListener('click', openSettings);
    document.getElementById('openSettings')?.addEventListener('click', openSettings);

    // Check settings
    const hasSettings = await checkSettings();

    if (hasSettings) {
        // Hide settings button when connected
        const settingsBtn = document.getElementById('settingsBtn');
        if (settingsBtn) {
            settingsBtn.style.display = 'none';
        }

        // Initialize Tagify on tags input
        const tagsInput = document.getElementById('tags');
        if (tagsInput) {
            tagify = new Tagify(tagsInput, {
                delimiters: ',',
                trim: true,
                duplicates: false,
                addTagOnBlur: true,
                placeholder: 'Add tags...',
                dropdown: {
                    enabled: 0
                }
            });
        }

        // Load page info and categories in parallel (non-blocking)
        Promise.all([
            loadPageInfo(),
            loadCategories()
        ]).catch(err => console.error('Error during initialization:', err));
    }
});
