# Quick Start Guide

## 🚀 Getting Started in 3 Steps

### Step 1: Setup Database
```bash
# Make setup script executable
chmod +x setup.sh

# Run setup script
./setup.sh
```

### Step 2: Start Backend Server
```bash
cd backend
php -S localhost:8080
```

### Step 3: Start Frontend Server
Open a new terminal:
```bash
cd photograph
npm install  # First time only
npm run dev
```

### Step 4: Open Application
Open your browser to: **http://localhost:5173**

## 🔐 Login Credentials
- **Username:** admin
- **Password:** admin123

## 📚 Vue 3 Concepts in This Project

### 1. **Composition API** (`<script setup>`)
All components use the modern Composition API with `<script setup>` syntax.

**Example:** `src/views/LoginView.vue`
```vue
<script setup>
import { ref, computed } from 'vue'

const username = ref('')
const isValid = computed(() => username.value.length > 0)
</script>
```

### 2. **Pinia Store** (State Management)
Centralized state management for auth and users.

**Files:**
- `src/stores/auth.js` - Authentication state
- `src/stores/users.js` - Users data management

### 3. **Vue Router** (Navigation)
SPA routing with navigation guards.

**File:** `src/router/index.js`

### 4. **Composables** (Reusable Logic)
Custom composition functions for shared logic.

**Files:**
- `src/composables/useNotification.js` - Toast notifications
- `src/composables/useFormValidation.js` - Form validation

### 5. **Reactive State**
- `ref()` - For primitive values
- `reactive()` - For objects
- `computed()` - For derived state
- `watch()` - For side effects

### 6. **Lifecycle Hooks**
- `onMounted()` - After component mounts
- `onUnmounted()` - Before component unmounts

### 7. **Props & Emits**
Component communication:
```vue
<script setup>
const props = defineProps(['user'])
const emit = defineEmits(['logout'])
</script>
```

### 8. **Slots**
Content distribution in `MainLayout.vue`:
```vue
<template>
  <div class="layout">
    <slot></slot>  <!-- Child content goes here -->
  </div>
</template>
```

### 9. **Directives**
- Built-in: `v-model`, `v-if`, `v-for`, `v-show`
- Custom: `v-tooltip`, `v-click-outside`

### 10. **Async/Await**
Asynchronous operations in stores and components.

## 📁 Key Files to Explore

### Components
- `src/components/MainLayout.vue` - Reusable layout with sidebar
- `src/views/LoginView.vue` - Login form with validation
- `src/views/UsersView.vue` - Complete CRUD operations
- `src/views/DashboardView.vue` - Dashboard with charts

### Stores (Pinia)
- `src/stores/auth.js` - Authentication logic
- `src/stores/users.js` - User management logic

### Composables
- `src/composables/useNotification.js` - Notification helper
- `src/composables/useFormValidation.js` - Form validation

### Router
- `src/router/index.js` - Route definitions and guards

## 🎨 UI Components (PrimeVue)

This project uses PrimeVue components:
- **DataTable** - Advanced tables with sorting/filtering
- **Dialog** - Modal dialogs
- **Toast** - Notifications
- **Button** - Styled buttons
- **InputText** - Text inputs
- **Password** - Password inputs with strength meter
- **Dropdown** - Select dropdowns
- **Card** - Content containers
- **Chart** - Data visualization

## 🔧 Customization

### Change API URL
Edit `photograph/.env`:
```
VITE_API_URL=http://your-api-url
```

### Change Database Credentials
Edit `backend/config/database.php`:
```php
private $host = "localhost";
private $db_name = "software";
private $username = "root";
private $password = "your_password";
```

## 🐛 Troubleshooting

### Port Already in Use
If port 8080 or 5173 is in use:
```bash
# Backend - use different port
php -S localhost:8081

# Update frontend/.env
VITE_API_URL=http://localhost:8081
```

### Database Connection Error
1. Check MySQL is running
2. Verify credentials in `backend/config/database.php`
3. Ensure database exists: `CREATE DATABASE software;`

### CORS Errors
CORS is configured in `backend/config/cors.php`. If issues persist, check browser console for specific errors.

## 📖 Learn More

- [Vue 3 Documentation](https://vuejs.org/)
- [Pinia Documentation](https://pinia.vuejs.org/)
- [Vue Router Documentation](https://router.vuejs.org/)
- [PrimeVue Documentation](https://primevue.org/)

---

**Happy Coding! 🎉**
