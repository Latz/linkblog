const MENU_ID = 'linkdigest-admin';
const MENU_ID_REFRESH = 'linkdigest-refresh-categories';

export async function refreshCategories() {
    const { apiEndpoint, apiKey } = await chrome.storage.sync.get(['apiEndpoint', 'apiKey']);
    if (!apiEndpoint || !apiKey) return;

    try {
        const response = await fetch(`${apiEndpoint}/categories`, {
            method: 'GET',
            cache: 'no-store',
            headers: {
                'Content-Type': 'application/json',
                'X-LinkDigest-API-Key': apiKey
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

chrome.storage.onChanged.addListener((changes, area) => {
    if (area === 'sync' && (changes.apiEndpoint || changes.apiKey)) {
        refreshCategories();
    }
});

chrome.runtime.onInstalled.addListener(() => {
    chrome.contextMenus.create({
        id: MENU_ID,
        title: 'Open LinkDigest Admin',
        contexts: ['action']
    });
    chrome.contextMenus.create({
        id: MENU_ID_REFRESH,
        title: 'Update categories',
        contexts: ['action']
    });
    refreshCategories();
});

export async function handleContextMenuClick(info) {
    if (info.menuItemId === MENU_ID_REFRESH) {
        refreshCategories();
        return;
    }

    if (info.menuItemId !== MENU_ID) return;

    const { apiEndpoint } = await chrome.storage.sync.get('apiEndpoint');

    if (!apiEndpoint) {
        chrome.runtime.openOptionsPage();
        return;
    }

    const wpBase = apiEndpoint.split('/wp-json/')[0];
    chrome.tabs.create({ url: `${wpBase}/wp-admin/admin.php?page=linkdigest-dashboard` });
}

chrome.contextMenus.onClicked.addListener(handleContextMenuClick);
