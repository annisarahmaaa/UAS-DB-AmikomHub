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

// Fetch from Cache first, then Network
self.addEventListener('fetch', event => {
  // Jangan cache request POST, PUT, DELETE, dll. Hanya GET.
  if (event.request.method !== 'GET') {
      return;
  }
  
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit - return response
        if (response) {
          return response;
        }

        return fetch(event.request).then(
          function(response) {
            // Check if we received a valid response
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Mencegah cache untuk request tertentu seperti API midtrans
            if (event.request.url.includes('midtrans')) {
                return response;
            }

            var responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then(function(cache) {
                cache.put(event.request, responseToCache);
              });

            return response;
          }
        ).catch(() => {
            // Optional: return halaman fallback offline di sini jika diperlukan
            // if (event.request.mode === 'navigate') {
            //     return caches.match('/offline.html');
            // }
        });
      })
  );
});
