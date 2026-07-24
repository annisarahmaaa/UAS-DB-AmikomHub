const CACHE_NAME = 'amikom-event-hub-v1';
const urlsToCache = [
  '/',
  '/manifest.json',
  '/icons/square-logo-dark.png',
  '/icons/rounded-logo-dark.png',
  // tambahkan rute statis lainnya di sini jika diperlukan
];

// Install Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Activate Service Worker
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Fetch from Cache first for assets, but Network First for HTML and API
self.addEventListener('fetch', event => {
  // Hanya proses metode GET
  if (event.request.method !== 'GET') {
      return;
  }
  
  // BYPASS: Jangan cache rute admin atau otentikasi
  const url = new URL(event.request.url);
  if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/login') || url.pathname.startsWith('/midtrans')) {
      return;
  }

  // Jika ini permintaan halaman HTML (Navigation), gunakan NETWORK FIRST
  if (event.request.mode === 'navigate' || event.request.headers.get('accept').includes('text/html')) {
      event.respondWith(
          fetch(event.request)
              .then(response => {
                  return caches.open(CACHE_NAME).then(cache => {
                      cache.put(event.request, response.clone());
                      return response;
                  });
              })
              .catch(() => {
                  return caches.match(event.request);
              })
      );
      return;
  }
  
  // STRATEGI CACHE FIRST (Untuk file statis: CSS, JS, Image)
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request).then(response => {
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            var responseToCache = response.clone();
            caches.open(CACHE_NAME)
              .then(function(cache) {
                cache.put(event.request, responseToCache);
              });
            return response;
        });
      })
  );
});
