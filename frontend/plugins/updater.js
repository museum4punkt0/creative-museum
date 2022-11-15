export default async () => {
  if (process.client) {
    const workbox = await window.$workbox;

    if (workbox) {
      workbox.addEventListener('installed', (event) => {
        const installEvent = new Event('sw-installed')
        window.dispatchEvent(installEvent)

        if (event.isUpdate) {
          const updateEvent = new Event('sw-updated')
          window.dispatchEvent(updateEvent)
        }
      });
    }
  }
}
