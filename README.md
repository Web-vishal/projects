# User Management System - Vue.js 3 + PHP

A comprehensive full-stack user management system built with **Vue.js 3** (frontend) and **PHP** (backend) demonstrating all major Vue 3 concepts and features.

## 🚀 Features

### Frontend (Vue.js 3)
- ✅ **Authentication System** - Login/Logout with session management
- ✅ **User CRUD Operations** - Create, Read, Update, Delete users
- ✅ **Change Password** - Secure password change functionality
- ✅ **User Profile** - View and edit profile information
- ✅ **Dashboard** - Statistics and data visualization
- ✅ **Responsive Design** - Works on all devices

### Vue 3 Concepts Demonstrated

#### Core Concepts
- **Composition API** - Using `setup()` function and composition functions
- **Reactive State** - `ref()` and `reactive()` for reactive data
- **Computed Properties** - `computed()` for derived state
- **Watchers** - `watch()` and `watchEffect()` for side effects
- **Lifecycle Hooks** - `onMounted()`, `onUnmounted()`, etc.

#### Advanced Features
- **Pinia Store** - State management (replaces Vuex)
- **Vue Router** - SPA navigation with route guards
- **Composables** - Reusable composition functions
- **Custom Directives** - `v-tooltip` directive
- **Slots** - Content distribution in components
- **Props & Emits** - Component communication
- **Teleport** - Rendering content outside component hierarchy
- **Suspense** - Async component loading
- **Dynamic Components** - Component switching
- **Form Validation** - Custom validation composable

#### UI Framework
- **PrimeVue** - Rich UI component library
- **PrimeIcons** - Icon library
- **Toast Notifications** - User feedback
- **Confirmation Dialogs** - User confirmations
- **Data Tables** - Advanced data display with pagination
- **Charts** - Data visualization

### Backend (PHP)
- ✅ **RESTful API** - Clean API architecture
- ✅ **PDO Database** - Secure database operations
- ✅ **Password Handling** - Plain text passwords
- ✅ **CORS Support** - Cross-origin resource sharing
- ✅ **Input Validation** - Server-side validation
- ✅ **Error Handling** - Proper error responses

## 📁 Project Structure

```
php/
├── photograph/               # Angular application
│   ├── src/
│   │   ├── components/      # Reusable components
│   │   │   ├── MainLayout.vue
│   │   │   └── Dashboard.vue
│   │   ├── views/           # Page components
│   │   │   ├── LoginView.vue
│   │   │   ├── DashboardView.vue
│   │   │   ├── UsersView.vue
│   │   │   ├── ChangePasswordView.vue
│   │   │   ├── ProfileView.vue
│   │   │   └── NotFoundView.vue
│   │   ├── stores/          # Pinia stores
│   │   │   ├── auth.js
│   │   │   └── users.js
│   │   ├── composables/     # Reusable composition functions
│   │   │   ├── useNotification.js
│   │   │   └── useFormValidation.js
│   │   ├── router/          # Vue Router configuration
│   │   │   └── index.js
│   │   ├── App.vue          # Root component
│   │   └── main.js          # Application entry point
│   └── package.json
│
└── backend/                 # PHP API
    ├── api/
    │   ├── auth/           # Authentication endpoints
    │   │   ├── login.php
    │   │   └── change-password.php
    │   └── users/          # User CRUD endpoints
    │       ├── read.php
    │       ├── create.php
    │       ├── update.php
    │       └── delete.php
    ├── config/
    │   ├── database.php    # Database connection
    │   └── cors.php        # CORS configuration
    └── database.sql        # Database schema
```

## 🛠️ Setup Instructions

### Prerequisites
- Node.js (v16 or higher)
- PHP (v7.4 or higher)
- MySQL/MariaDB
- npm or yarn

### Database Setup

1. Create MySQL database:
```sql
CREATE DATABASE software;
```

2. Import the database schema:
```bash
mysql -u root -p software < backend/database.sql
```

3. Update database credentials in `backend/config/database.php`:
```php
private $host = "localhost";
private $db_name = "software";
private $username = "root";
private $password = "your_password";
```

### Backend Setup

1. Navigate to backend directory:
```bash
cd backend
```

2. Start PHP development server:
```bash
php -S localhost:8080
```

The API will be available at `http://localhost:8080`

### Photograph Setup

1. Navigate to photograph directory:
```bash
cd photograph
```

2. Install dependencies:
```bash
npm install
```

3. Update API URL in `.env` file (already configured):
```
VITE_API_URL=http://localhost:8080
```

4. Start development server:
```bash
npm run dev
```

The application will be available at `http://localhost:5173`

## 🔐 Demo Credentials

**Username:** admin  
**Password:** admin123

## 📚 Vue 3 Concepts Explained

### 1. Composition API
```javascript
// Using setup() function
<script setup>
import { ref, computed } from 'vue'

// Reactive state
const count = ref(0)

// Computed property
const doubleCount = computed(() => count.value * 2)

// Method
const increment = () => {
  count.value++
}
</script>
```

### 2. Pinia Store (State Management)
```javascript
// stores/auth.js
export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  
  const isAuthenticated = computed(() => !!user.value)
  
  async function login(username, password) {
    // Login logic
  }
  
  return { user, isAuthenticated, login }
})
```

### 3. Composables (Reusable Logic)
```javascript
// composables/useNotification.js
export function useNotification() {
  const showSuccess = (message) => {
    // Show success notification
  }
  
  return { showSuccess }
}
```

### 4. Vue Router
```javascript
// router/index.js
const routes = [
  { path: '/', component: LoginView },
  { path: '/dashboard', component: DashboardView, meta: { requiresAuth: true } }
]

// Navigation guard
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else {
    next()
  }
})
```

## 🎨 UI Components Used

- **DataTable** - Advanced data tables with sorting, filtering, pagination
- **Dialog** - Modal dialogs for forms
- **Toast** - Notification messages
- **ConfirmDialog** - Confirmation prompts
- **Card** - Content containers
- **Button** - Interactive buttons
- **InputText** - Text inputs
- **Password** - Password inputs with strength meter
- **Dropdown** - Select dropdowns
- **Tag** - Status badges
- **Chart** - Data visualization
- **Avatar** - User avatars
- **Menu** - Dropdown menus
- **Toolbar** - Action toolbars

## 🔒 Security Features

- Plain text password storage
- SQL injection prevention with PDO prepared statements
- XSS protection
- CORS configuration
- Input validation (client and server-side)
- Authentication guards

## 📱 Responsive Design

The application is fully responsive and works on:
- Desktop (1920px+)
- Laptop (1024px - 1919px)
- Tablet (768px - 1023px)
- Mobile (320px - 767px)

## 🚀 Building for Production

```bash
# Photograph
cd photograph
npm run build

# The built files will be in frontend/dist/
```

## 📖 API Endpoints

### Authentication
- `POST /api/auth/login.php` - User login
- `POST /api/auth/change-password.php` - Change password

### Users
- `GET /api/users/read.php` - Get all users (with pagination & search)
- `GET /api/users/read.php?id={id}` - Get single user
- `POST /api/users/create.php` - Create new user
- `POST /api/users/update.php` - Update user
- `POST /api/users/delete.php` - Delete user

## 🤝 Contributing

Feel free to submit issues and enhancement requests!

## 📄 License

MIT License - feel free to use this project for learning and development.

---

**Built with ❤️ using Vue.js 3 and PHP**
