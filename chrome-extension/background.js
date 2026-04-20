const MENU_ID = 'linkblog-admin';

async function refreshCategories() {
    const { apiEndpoint, apiKey } = await chrome.storage.sync.get(['apiEndpoint', 'apiKey']);
    if (!apiEndpoint || !apiKey) return;

    try {
        const response = await fetch(`${apiEndpoint}/categories`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-LinkBlog-API-Key': apiKey
            }
        });
        if (!response.ok) return;
        const categories = await response.json();
        await chrome.storage.local.set({ categories, categoriesTimestamp: Date.now() });
    } catch {
        // Fail silently — popup will fetch on next open if cache is missing
    }
}

chrome.runtime.onStartup.addListener(() => refreshCategories());

chrome.runtime.onInstalled.addListener(() => {
    chrome.contextMenus.create({
        id: MENU_ID,
        title: 'Open LinkBlog Admin',
        contexts: ['action']
    });
    refreshCategories();
});

chrome.contextMenus.onClicked.addListener(async (info) => {
    if (info.menuItemId !== MENU_ID) return;

    const { apiEndpoint } = await chrome.storage.sync.get('apiEndpoint');

    if (!apiEndpoint) {
        chrome.runtime.openOptionsPage();
        return;
    }

    const wpBase = apiEndpoint.split('/wp-json/')[0];
    chrome.tabs.create({ url: `${wpBase}/wp-admin/admin.php?page=linkblog-dashboard` });
});
