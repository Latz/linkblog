import { vi, afterEach } from 'vitest';

global.chrome = {
    storage: {
        sync: {
            get: vi.fn().mockResolvedValue({}),
            set: vi.fn().mockResolvedValue(undefined),
        },
        local: {
            get: vi.fn().mockResolvedValue({}),
            set: vi.fn().mockResolvedValue(undefined),
        },
        onChanged: { addListener: vi.fn() },
    },
    runtime: {
        onStartup:   { addListener: vi.fn() },
        onInstalled: { addListener: vi.fn() },
        openOptionsPage: vi.fn(),
    },
    contextMenus: {
        create:    vi.fn(),
        onClicked: { addListener: vi.fn() },
    },
    tabs: {
        create: vi.fn(),
        query:  vi.fn().mockResolvedValue([]),
    },
    scripting: {
        executeScript: vi.fn().mockResolvedValue([{ result: '' }]),
    },
    notifications: {
        create: vi.fn(),
    },
    cookies: {
        getAll: vi.fn().mockResolvedValue([]),
    },
};

global.Tagify = vi.fn(() => ({ value: [] }));

afterEach(() => {
    vi.clearAllMocks();
    // Restore default resolved values after clearing
    chrome.storage.sync.get.mockResolvedValue({});
    chrome.storage.sync.set.mockResolvedValue(undefined);
    chrome.storage.local.get.mockResolvedValue({});
    chrome.storage.local.set.mockResolvedValue(undefined);
    chrome.tabs.query.mockResolvedValue([]);
    chrome.scripting.executeScript.mockResolvedValue([{ result: '' }]);
    chrome.cookies.getAll.mockResolvedValue([]);
});
