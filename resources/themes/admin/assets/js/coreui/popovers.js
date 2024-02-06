/* global coreui */

/**
 * --------------------------------------------------------------------------
 * CoreUI Free Boostrap Admin Template (v4.2.1): popovers.js
 * Licensed under MIT (https://coreui.io/license)
 * --------------------------------------------------------------------------
 */

document.querySelectorAll('[data-bs-toggle="popover"]').forEach(element => {
  // eslint-disable-next-line no-new
  new coreui.Popover(element)
})

