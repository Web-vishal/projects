# 📍 Vue 3 Concepts - Where to Find Them

This guide shows you exactly where each Vue 3 concept is used in the codebase with file locations and line references.

---

## 🎯 Quick Reference

| Concept | File Location | Description |
|---------|--------------|-------------|
| Composition API | All `.vue` files | `<script setup>` syntax |
| ref() | `LoginView.vue`, `UsersView.vue` | Reactive primitives |
| reactive() | `stores/auth.js` | Reactive objects |
| computed() | `DashboardView.vue`, `stores/auth.js` | Derived state |
| watch() | `DashboardView.vue` | Side effects |
| onMounted() | `DashboardView.vue`, `App.vue` | Lifecycle |
| Pinia Store | `stores/auth.js`, `stores/users.js` | State management |
| Vue Router | `router/index.js` | Navigation |
| Composables | `composables/useNotification.js` | Reusable logic |
| Props | `MainLayout.vue` | Parent to child |
| Emits | `Dashboard.vue` (old) | Child to parent |
| Slots | `MainLayout.vue` | Content distribution |
| Directives | All views | v-model, v-if, v-for |
| Custom Directives | `directives/index.js` | v-click-outside |

---

## 📁 Detailed File Guide

### 1. Composition API (`<script setup>`)

**Every Vue component uses this!**

```
✅ frontend/src/views/LoginView.vue
✅ frontend/src/views/DashboardView.vue
✅ frontend/src/views/UsersView.vue
✅ frontend/src/views/ChangePasswordView.vue
✅ frontend/src/views/ProfileView.vue
✅ frontend/src/components/MainLayout.vue
```

**Look for:**
```vue
<script setup>
import { ref, computed } from 'vue'
// This is Composition API!
</script>
```

---

### 2. Reactive State

#### ref() - Primitive Values
**File:** `frontend/src/views/LoginView.vue`
```javascript
const username = ref('')
const password = ref('')
const loading = ref(false)
```

**File:** `frontend/src/views/UsersView.vue`
```javascript
const loading = ref(false)
const showDialog = ref(false)
const searchQuery = ref('')
```

#### reactive() - Objects
**File:** `frontend/src/stores/users.js`
```javascript
const pagination = ref({
  total: 0,
  page: 1,
  limit: 10,
  total_pages: 0
})
```

---

### 3. Computed Properties

**File:** `frontend/src/views/DashboardView.vue` (lines ~80-90)
```javascript
const welcomeMessage = computed(() => {
  const hour = new Date().getHours()
  let greeting = 'Good evening'
  if (hour < 12) greeting = 'Good morning'
  else if (hour < 18) greeting = 'Good afternoon'
  return `${greeting}, ${authStore.userName}!`
})
```

**File:** `frontend/src/stores/auth.js` (lines ~30-40)
```javascript
const isAuthenticated = computed(() => !!user.value)
const userType = computed(() => user.value?.type || 'guest')
const userName = computed(() => user.value?.full_name || 'Guest')
const isAdmin = computed(() => user.value?.type === 'admin')
```

**File:** `frontend/src/views/UsersView.vue` (lines ~50-60)
```javascript
const dialogTitle = computed(() => {
  return dialogMode.value === 'create' ? 'Create New User' : 'Edit User'
})
```

---

### 4. Watchers

**File:** `frontend/src/views/DashboardView.vue` (lines ~100-110)
```javascript
watch(
  () => usersStore.users,  // What to watch
  (newUsers) => {           // Callback when it changes
    updateStats(newUsers)
    recentUsers.value = newUsers.slice(0, 5)
  },
  { deep: true }  // Options: deep watch
)
```

---

### 5. Lifecycle Hooks

**File:** `frontend/src/App.vue` (lines ~20-25)
```javascript
onMounted(() => {
  authStore.initAuth()  // Initialize auth from localStorage
})
```

**File:** `frontend/src/views/DashboardView.vue` (lines ~140-150)
```javascript
onMounted(async () => {
  try {
    await usersStore.fetchUsers(1, 50)
    updateStats(usersStore.users)
    recentUsers.value = usersStore.users.slice(0, 5)
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  } finally {
    loading.value = false
  }
})
```

---

### 6. Pinia Store (State Management)

**File:** `frontend/src/stores/auth.js` - Complete example
```javascript
export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(null)
  
  // Getters
  const isAuthenticated = computed(() => !!user.value)
  
  // Actions
  async function login(username, password) {
    // Login logic
  }
  
  return { user, token, isAuthenticated, login }
})
```

**File:** `frontend/src/stores/users.js` - CRUD operations
```javascript
export const useUsersStore = defineStore('users', () => {
  const users = ref([])
  
  async function fetchUsers() { /* ... */ }
  async function createUser() { /* ... */ }
  async function updateUser() { /* ... */ }
  async function deleteUser() { /* ... */ }
  
  return { users, fetchUsers, createUser, updateUser, deleteUser }
})
```

**Using stores in components:**
```javascript
// frontend/src/views/LoginView.vue
const authStore = useAuthStore()
await authStore.login(username.value, password.value)
```

---

### 7. Vue Router

**File:** `frontend/src/router/index.js`

**Route Definition:**
```javascript
const routes = [
  {
    path: '/',
    name: 'Login',
    component: () => import('../views/LoginView.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/DashboardView.vue'),
    meta: { requiresAuth: true }
  }
]
```

**Navigation Guard:**
```javascript
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' })
  } else {
    next()
  }
})
```

**Using router in components:**
```javascript
// frontend/src/views/LoginView.vue
const router = useRouter()
router.push({ name: 'Dashboard' })
```

---

### 8. Composables (Reusable Logic)

**File:** `frontend/src/composables/useNotification.js`
```javascript
export function useNotification() {
  const toast = useToast()
  
  const showSuccess = (message, summary = 'Success') => {
    toast.add({ severity: 'success', summary, detail: message, life: 3000 })
  }
  
  const showError = (message, summary = 'Error') => {
    toast.add({ severity: 'error', summary, detail: message, life: 5000 })
  }
  
  return { showSuccess, showError }
}
```

**File:** `frontend/src/composables/useFormValidation.js`
```javascript
export function useFormValidation(initialValues, validationRules) {
  const formData = ref({ ...initialValues })
  const errors = ref({})
  
  const validateForm = () => { /* ... */ }
  const resetForm = () => { /* ... */ }
  
  return { formData, errors, validateForm, resetForm }
}
```

**Using composables:**
```javascript
// frontend/src/views/ChangePasswordView.vue
const { showSuccess, showError } = useNotification()
showSuccess('Password changed successfully')
```

---

### 9. Props (Parent to Child)

**File:** `frontend/src/components/MainLayout.vue`
```javascript
const props = defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  }
})
```

**Using props:**
```vue
<!-- Parent component -->
<MainLayout title="User Management">
  <!-- content -->
</MainLayout>
```

---

### 10. Emits (Child to Parent)

**File:** `frontend/src/components/Dashboard.vue` (old component)
```javascript
const emit = defineEmits(['logout'])

const handleLogout = () => {
  emit('logout')
}
```

**Listening to emits:**
```vue
<Dashboard @logout="handleLogout" />
```

---

### 11. Slots (Content Distribution)

**File:** `frontend/src/components/MainLayout.vue`
```vue
<template>
  <div class="layout">
    <header>...</header>
    <main class="content">
      <!-- Slot: child content goes here -->
      <slot></slot>
    </main>
  </div>
</template>
```

**Using slots:**
```vue
<!-- Parent component -->
<MainLayout>
  <h1>This content goes into the slot</h1>
  <p>Any content here will be rendered inside MainLayout</p>
</MainLayout>
```

---

### 12. Built-in Directives

#### v-model (Two-way Binding)
**File:** `frontend/src/views/LoginView.vue`
```vue
<InputText v-model="username" />
```

#### v-if / v-else (Conditional Rendering)
**File:** `frontend/src/views/ChangePasswordView.vue`
```vue
<Message v-if="showSuccessMessage" severity="success">
  Success!
</Message>
```

#### v-for (List Rendering)
**File:** `frontend/src/views/DashboardView.vue`
```vue
<Card v-for="(stat, index) in stats" :key="index">
  {{ stat.label }}: {{ stat.value }}
</Card>
```

#### v-show (Toggle Display)
```vue
<div v-show="isVisible">Content</div>
```

#### v-bind / : (Attribute Binding)
**File:** `frontend/src/views/UsersView.vue`
```vue
<Button :loading="loading" :disabled="!isFormValid" />
```

#### v-on / @ (Event Handling)
**File:** `frontend/src/views/LoginView.vue`
```vue
<form @submit.prevent="handleLogin">
  <Button @click="handleLogin" />
</form>
```

---

### 13. Custom Directives

**File:** `frontend/src/directives/index.js`

**Click Outside Directive:**
```javascript
export const clickOutside = {
  beforeMount(el, binding) {
    el.clickOutsideEvent = function(event) {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event)
      }
    }
    document.addEventListener('click', el.clickOutsideEvent)
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent)
  }
}
```

**Focus Directive:**
```javascript
export const focus = {
  mounted(el) {
    el.focus()
  }
}
```

**Lazy Load Directive:**
```javascript
export const lazy = {
  mounted(el, binding) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          el.src = binding.value
          observer.unobserve(el)
        }
      })
    })
    observer.observe(el)
  }
}
```

**Using custom directives:**
```vue
<div v-click-outside="closeMenu">Menu</div>
<input v-focus />
<img v-lazy="imageUrl" />
```

---

### 14. PrimeVue Components

**File:** `frontend/src/views/UsersView.vue` - DataTable
```vue
<DataTable
  :value="usersStore.users"
  :loading="loading"
  stripedRows
  paginator
  :rows="10"
>
  <Column field="username" header="Username" sortable />
  <Column field="email" header="Email" sortable />
</DataTable>
```

**File:** `frontend/src/views/UsersView.vue` - Dialog
```vue
<Dialog
  v-model:visible="showDialog"
  :header="dialogTitle"
  :modal="true"
>
  <!-- Dialog content -->
</Dialog>
```

**File:** `frontend/src/App.vue` - Toast
```vue
<Toast position="top-right" />
```

**File:** `frontend/src/views/DashboardView.vue` - Chart
```vue
<Chart type="line" :data="chartData" :options="chartOptions" />
```

---

## 🎓 Learning Path

### Beginner
1. Start with `LoginView.vue` - Simple form with ref() and computed()
2. Look at `stores/auth.js` - Basic Pinia store
3. Check `router/index.js` - Simple routing

### Intermediate
4. Study `UsersView.vue` - Complete CRUD with all concepts
5. Explore `composables/useNotification.js` - Reusable logic
6. Review `MainLayout.vue` - Props, slots, and layout

### Advanced
7. Analyze `DashboardView.vue` - Watch, lifecycle, complex state
8. Study `stores/users.js` - Advanced store patterns
9. Look at `directives/index.js` - Custom directives

---

## 📝 Code Comments

**Every file has extensive comments explaining:**
- ✅ What each function does
- ✅ Why we use specific Vue concepts
- ✅ How data flows
- ✅ Best practices

**Just open any file and read the comments!**

---

## 🔍 Search Tips

To find specific concepts in VS Code:

```
Ctrl/Cmd + Shift + F (Find in Files)

Search for:
- "ref(" - Find all ref() usage
- "computed(" - Find all computed properties
- "watch(" - Find all watchers
- "onMounted(" - Find all lifecycle hooks
- "defineStore" - Find all Pinia stores
- "defineProps" - Find all props
- "defineEmits" - Find all emits
- "v-model" - Find all two-way bindings
- "v-for" - Find all loops
```

---

## ✨ Summary

This codebase is a **complete learning resource** with:

✅ Every Vue 3 concept demonstrated  
✅ Real-world usage examples  
✅ Extensive code comments  
✅ Best practices throughout  
✅ Production-ready patterns  

**Just open the files and start reading!** 🎉

---

**Happy Learning! 📚**
