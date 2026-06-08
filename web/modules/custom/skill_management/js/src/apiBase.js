// Drupal exposes drupalSettings.path.baseUrl (e.g. "/" or "/skill-management/web/").
// Prefixing it makes the API calls resolve whether the app is served at the
// domain root or in a subdirectory. baseUrl always ends with a slash.
const base =
  (window.drupalSettings &&
    window.drupalSettings.path &&
    window.drupalSettings.path.baseUrl) ||
  '/';

export function apiUrl(path) {
  return base + path.replace(/^\//, '');
}
