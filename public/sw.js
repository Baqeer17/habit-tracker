const CACHE_NAME = 'mahabba-cache-v1';
const urlsToCache = [
    '/',
    '/manifest.json',
    // Untuk production, ini adalah aset statis.
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                // Ignore fallback caching errors initially
                return cache.addAll(urlsToCache).catch(err => console.log('Partial caching issue', err));
            })
    );
});

self.addEventListener('fetch', event => {
    // Pada PWA manual, network first, cache fallback
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

// Listener notifikasi untuk Web Push (nanti saat Laravel mengirim broadcast via VAPID)
self.addEventListener('push', event => {
    let data;
    try {
        data = event.data.json();
    } catch(e) {
        data = { title: "MahabBa Reminder", body: event.data.text() };
    }

    const options = {
        body: data.body,
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-192x192.png',
        data: { url: data.url || '/dashboard' }
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    } else {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    }
});
