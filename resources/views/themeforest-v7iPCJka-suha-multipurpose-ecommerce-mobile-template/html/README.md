## Before Getting Started

Progressive Web Apps (PWAs) primarily rely on the **`service-worker.js`** file. This file is responsible for caching data in the browser, enabling smooth offline access to your website.

---

### What Could Go Wrong?

When you make changes to your files or scripts during development, these updates may not reflect immediately due to cached data in the browser. 

---

### How to Avoid This Issue?

To prevent caching conflicts during development:

1. **Disconnect the `service-worker.js` File:**
   - Locate the **`service-worker.js`** file.
   - Comment out all JavaScript code within it or temporarily move the file to another location.

2. Reconnect the file only when your development is complete and the project is ready to be uploaded to the server.

📸 **Reference Images**
- [Image 1](https://prnt.sc/tuwnua)
- [Image 2](https://prnt.sc/tuwo66)

---

### PWA Requirements

- Your website must be served over a secure domain (**HTTPS**).