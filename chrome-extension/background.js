const MENU_ID = 'linkblog-admin';

chrome.runtime.onInstalled.addListener(() => {
    chrome.contextMenus.create({
        id: MENU_ID,
        title: 'Open LinkBlog Admin',
        contexts: ['action']
    });
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
