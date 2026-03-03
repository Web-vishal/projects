# GoDaddy Deployment Guide

This repo now includes ready-to-upload bundles for shared hosting:

- `/Users/vishalgarg/Documents/php/deploy/godaddy/software.zip`
- `/Users/vishalgarg/Documents/php/deploy/godaddy/kunalSoftware.zip`

## 1. Pick the project

- `software.zip` for the original app
- `kunalSoftware.zip` for the Kunal app

## 2. Upload to GoDaddy

1. Open GoDaddy cPanel.
2. Open **File Manager**.
3. Go to your domain root folder (`public_html`).
4. Upload the selected zip.
5. Extract it.

After extraction, copy the contents of:

- `.../software/public_html/*` (or `.../kunalSoftware/public_html/*`)

into your actual hosting `public_html/`.

Your `public_html` should contain:

- React build files (`index.html`, `assets/`, `.htaccess`)
- `api/`
- `config/`
- `.env.example`

## 3. Configure backend DB credentials

1. In `public_html`, duplicate `.env.example` as `.env`.
2. Update values in `.env`:

```env
DB_HOST=localhost
DB_NAME=your_db_name
DB_USER=your_db_user
DB_PASS=your_db_password
ALLOWED_ORIGIN=https://yourdomain.com
```

## 4. Create/import database

1. Open **phpMyAdmin** from cPanel.
2. Create database (if not existing).
3. Import:
   - `.../software/database.sql` or
   - `.../kunalSoftware/database.sql`

## 5. Verify

- Frontend: `https://yourdomain.com`
- API check: `https://yourdomain.com/api/auth/login.php` (GET should return 400 JSON message)

## Rebuild bundle after future changes

Run:

```bash
/Users/vishalgarg/Documents/php/scripts/prepare-godaddy-deploy.sh
```
