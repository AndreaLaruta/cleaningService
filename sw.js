const CACHE_NAME = 'v1_pwa_app_cache';
const urlsToCache = [
  'assets/images/logo/logo.png',
  'assets/images/.*',
];

// Evento de instalación del Service Worker
self.addEventListener("install", (e) => {
  // No almacenamos nada en el caché durante la instalación
  e.waitUntil(self.skipWaiting());
});

// Evento de activación del Service Worker
self.addEventListener('activate', e => {
  const cacheWhitelist = [CACHE_NAME];
  e.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            // Eliminar todos los cachés que no estén en la lista blanca
            if (cacheWhitelist.indexOf(cacheName) === -1) {
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => self.clients.claim()) // Activar el service worker de inmediato
  );
});

// Evento de interceptación de solicitudes de red
self.addEventListener('fetch', e => {
  e.respondWith(
    // No hacemos nada con el caché, simplemente buscamos en la red
    fetch(e.request)
      .then(response => {
        if (!response || response.status !== 200 || response.type !== 'basic') {
          return response; // Si la respuesta no es válida, retornamos tal cual
        }

        // Retornar la respuesta directamente sin cachearla
        return response;
      })
      .catch(err => {
        console.log('Falló algo al solicitar recursos:', err);
        self.clients.matchAll().then(clients => {
          clients.forEach(client => {
            client.postMessage('Falló algo al solicitar recursos');
          });
        });
      })
  );
});

// Evento para escuchar la actualización de contenido y forzar la recarga
self.addEventListener('sync', (event) => {
  if (event.tag === 'update') {
    event.waitUntil(
      caches.open(CACHE_NAME)
        .then(cache => {
          // Aquí no estamos almacenando nada en la caché
        })
    );
  }
});

// En el cliente (página web) escuchar la actualización de caché
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.addEventListener('message', event => {
    if (event.data.action === 'reload') {
      window.location.reload();
    }
  });
}
