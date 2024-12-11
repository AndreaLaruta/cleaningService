const CACHE_NAME = 'app';

// URLs que deben ser cacheadas
const urlsToCache = [
  'assets/images/logo/logo.png',
  'assets/images/.*',  // Regex para imágenes
];

// Evento de instalación del Service Worker
self.addEventListener('install', async (e) => {
  try {
    const cache = await caches.open(CACHE_NAME);
    await cache.addAll(urlsToCache);
    self.skipWaiting();
  } catch (err) {
    console.error('Error al instalar el service worker:', err);
  }
});

// Evento de activación del Service Worker
self.addEventListener('activate', async (e) => {
  const cacheWhitelist = [CACHE_NAME];
  try {
    const cacheNames = await caches.keys();
    await Promise.all(
      cacheNames.map(cacheName => {
        if (!cacheWhitelist.includes(cacheName)) {
          return caches.delete(cacheName);
        }
      })
    );
    self.clients.claim();
  } catch (err) {
    console.error('Error al activar el service worker:', err);
  }
});

// Evento de interceptación de solicitudes de red
self.addEventListener('fetch', (e) => {
  e.respondWith(
    (async () => {
      try {
        const cachedResponse = await caches.match(e.request);
        if (cachedResponse) {
          return cachedResponse;
        }
        const networkResponse = await fetch(e.request);
        if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
          return networkResponse;
        }
        const responseClone = networkResponse.clone();
        const cache = await caches.open(CACHE_NAME);
        cache.put(e.request, responseClone);
        return networkResponse;
      } catch (err) {
        console.error('Error al buscar recurso:', err);
        // Notificación a los clientes si hay un fallo
        self.clients.matchAll().then(clients => {
          clients.forEach(client => {
            client.postMessage('¡Hubo un error al solicitar recursos!');
          });
        });
        throw err; // Re-lanzar el error para que pueda ser manejado adecuadamente
      }
    })()
  );
});

// Evento de mensaje para manejar errores específicos o acciones desde la interfaz
self.addEventListener('message', (event) => {
  if (event.data === 'Falló algo al solicitar recursos') {
    // Mostrar notificación o ventana de error al usuario
    // Por ejemplo, usar Push API o mostrar un mensaje en el navegador
    self.registration.showNotification('Error', {
      body: 'Hubo un problema al solicitar recursos de la red.',
    });
  }
});
