Un cuadro de mando para usar en Moodle

- Debería funcionar en Oracle por el momento
- 

## Temporary Bootstrap 5 inclusion (Option A)

This repository has been temporarily configured to load Bootstrap 5 from the jsdelivr CDN and a small DOM shim that maps legacy `data-*` attributes to Bootstrap 5's `data-bs-*` equivalents. This was done to support a fast, reversible migration so that components emitting Bootstrap 5 attributes (like the updated navbar) start working without a full AdminLTE/theme upgrade.

Notes and next steps:
- This is a quick compatibility shim. AdminLTE v3 included in `thirdpartylibs` targets Bootstrap 4 and may have CSS/JS conflicts with Bootstrap 5. Test thoroughly across pages (home, courses, users, geo).
- Long-term, consider upgrading AdminLTE to a Bootstrap 5 compatible version or migrating to a BS5-aligned theme.
- If you prefer local hosting of Bootstrap 5 assets instead of CDN, copy files to `assets/` and update the includes in `pages/*.php`.

