# Vue.js 3 Concepts - Complete Reference Guide

This document explains all Vue.js 3 concepts used in this project with examples and comments.

## Table of Contents
1. [Composition API](#composition-api)
2. [Reactive State](#reactive-state)
3. [Computed Properties](#computed-properties)
4. [Watchers](#watchers)
5. [Lifecycle Hooks](#lifecycle-hooks)
6. [Pinia Store](#pinia-store)
7. [Vue Router](#vue-router)
8. [Composables](#composables)
9. [Props & Emits](#props--emits)
10. [Slots](#slots)
11. [Directives](#directives)
12. [Teleport](#teleport)
13. [Suspense](#suspense)
14. [Dynamic Components](#dynamic-components)

---

## 1. Composition API

### What is it?
The Composition API is Vue 3's new way to organize component logic. It uses the `<script setup>` syntax for cleaner code.

### Example from LoginView.vue:
```vue
<script setup>
// Import Vue functions
import { ref, computed } from 'vue'

// Reactive state
const username = ref('')
const password = ref('')

// Computed property
const isFormValid = computed(() => {
  return username.value.trim() !== '' && password.value.trim() !== ''
})

// Method
const handleLogin = async () => {
  // Login logic
}
</script>
```

### Why use it?
- Better code organization
- Easier to reuse logic
- Better TypeScript support
- More flexible than Options API

---

## 2. Reactive State

### ref() - For Primitive Values
```javascript
// Creates a reactive reference
const count = ref(0)

// Access value with .value
console.log(count.value) // 0

// Update value
count.value++
```

### reactive() - For Objects
```javascript
// Creates a reactive object
const user = reactive({
  name: 'John',
  age: 30
})

// Access directly (no .value needed)
console.log(user.name) // 'John'

// Update directly
user.age = 31
```

### Example from UsersView.vue:
```javascript
// Reactive state for component
const loading = ref(false)
const showDialog = ref(false)
const formData = ref({
  username: '',
  email: '',
  password: ''
})
```

---

## 3. Computed Properties

### What is it?
Computed properties are reactive values derived from other reactive data.

### Example from DashboardView.vue:
```javascript
// Computed property that updates automatically
const welcomeMessage = computed(() => {
  const hour = new Date().getHours()
  let greeting = 'Good evening'
  if (hour < 12) greeting = 'Good morning'
  else if (hour < 18) greeting = 'Good afternoon'
  return `${greeting}, ${authStore.userName}!`
})
```

### Why use it?
- Cached based on dependencies
- Only re-computes when dependencies change
- Better performance than methods

---

## 4. Watchers

### watch() - Watch Specific Values
```javascript
// Watch a single ref
watch(username, (newValue, oldValue) => {
  console.log(`Username changed from ${oldValue} to ${newValue}`)
})

// Watch multiple sources
watch([username, password], ([newUser, newPass]) => {
  console.log('Form changed')
})
```

### Example from DashboardView.vue:
```javascript
// Watch for changes in users store
watch(
  () => usersStore.users,  // Source
  (newUsers) => {           // Callback
    updateStats(newUsers)
    recentUsers.value = newUsers.slice(0, 5)
  },
  { deep: true }  // Options: deep watch for nested properties
)
```

### watchEffect() - Auto-track Dependencies
```javascript
// Automatically tracks all reactive dependencies
watchEffect(() => {
  console.log(`Count is: ${count.value}`)
  // Will re-run whenever count changes
})
```

---

## 5. Lifecycle Hooks

### Available Hooks
- `onBeforeMount()` - Before component is mounted
- `onMounted()` - After component is mounted
- `onBeforeUpdate()` - Before reactive data changes
- `onUpdated()` - After reactive data changes
- `onBeforeUnmount()` - Before component is destroyed
- `onUnmounted()` - After component is destroyed

### Example from App.vue:
```javascript
import { onMounted } from 'vue'

// Runs after component is mounted to DOM
onMounted(() => {
  // Initialize authentication from localStorage
  authStore.initAuth()
})
```

### Example from DashboardView.vue:
```javascript
onMounted(async () => {
  try {
    // Fetch data when component mounts
    await usersStore.fetchUsers(1, 50)
    updateStats(usersStore.users)
  } catch (error) {
    console.error('Failed to load data:', error)
  } finally {
    loading.value = false
  }
})
```

---

## 6. Pinia Store

### What is it?
Pinia is Vue 3's official state management library (replaces Vuex).

### Example from stores/auth.js:
```javascript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

// Define a store
export const useAuthStore = defineStore('auth', () => {
  // State (using ref)
  const user = ref(null)
  const token = ref(null)
  
  // Getters (using computed)
  const isAuthenticated = computed(() => !!user.value)
  const userName = computed(() => user.value?.full_name || 'Guest')
  
  // Actions (functions)
  async function login(username, password) {
    const response = await axios.post('/api/login', { username, password })
    user.value = response.data.user
    token.value = response.data.token
  }
  
  function logout() {
    user.value = null
    token.value = null
  }
  
  // Return everything to make it available
  return {
    user,
    token,
    isAuthenticated,
    userName,
    login,
    logout
  }
})
```

### Using the Store in Components:
```javascript
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

// Access state
console.log(authStore.user)

// Access getters
console.log(authStore.isAuthenticated)

// Call actions
await authStore.login('admin', 'password')
```

---

## 7. Vue Router

### Route Definition (router/index.js):
```javascript
import { createRouter, createWebHistory } from 'vue-router'

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

const router = createRouter({
  history: createWebHistory(),
  routes
})
```

### Navigation Guards:
```javascript
// Global guard - runs before every route
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // Redirect to login if not authenticated
    next({ name: 'Login' })
  } else {
    // Allow navigation
    next()
  }
})
```

### Using Router in Components:
```javascript
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()  // For navigation
const route = useRoute()    // For current route info

// Navigate programmatically
router.push('/dashboard')
router.push({ name: 'Users' })

// Get current route
console.log(route.path)
console.log(route.params)
```

---

## 8. Composables

### What is it?
Composables are reusable functions that encapsulate stateful logic.

### Example from composables/useNotification.js:
```javascript
import { useToast } from 'primevue/usetoast'

// Composable function (starts with "use")
export function useNotification() {
  const toast = useToast()
  
  // Helper function for success notifications
  const showSuccess = (message, summary = 'Success') => {
    toast.add({
      severity: 'success',
      summary,
      detail: message,
      life: 3000
    })
  }
  
  // Helper function for error notifications
  const showError = (message, summary = 'Error') => {
    toast.add({
      severity: 'error',
      summary,
      detail: message,
      life: 5000
    })
  }
  
  // Return functions to use in components
  return {
    showSuccess,
    showError
  }
}
```

### Using Composables:
```javascript
import { useNotification } from '@/composables/useNotification'

// Use the composable
const { showSuccess, showError } = useNotification()

// Use the functions
showSuccess('User created successfully')
showError('Failed to save data')
```

---

## 9. Props & Emits

### Props - Parent to Child Communication

#### Defining Props:
```javascript
// Child component
<script setup>
const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  title: {
    type: String,
    default: 'Dashboard'
  }
})

// Access props
console.log(props.user)
console.log(props.title)
</script>
```

#### Using Props:
```vue
<!-- Parent component -->
<template>
  <ChildComponent :user="currentUser" title="My Dashboard" />
</template>
```

### Emits - Child to Parent Communication

#### Defining Emits:
```javascript
// Child component
<script setup>
const emit = defineEmits(['logout', 'update'])

// Emit event
const handleLogout = () => {
  emit('logout')
}

// Emit with data
const handleUpdate = (data) => {
  emit('update', data)
}
</script>
```

#### Listening to Emits:
```vue
<!-- Parent component -->
<template>
  <ChildComponent 
    @logout="handleLogout" 
    @update="handleUpdate"
  />
</template>

<script setup>
const handleLogout = () => {
  console.log('User logged out')
}

const handleUpdate = (data) => {
  console.log('Updated:', data)
}
</script>
```

---

## 10. Slots

### What is it?
Slots allow parent components to pass content to child components.

### Example from MainLayout.vue:
```vue
<!-- Child component (MainLayout.vue) -->
<template>
  <div class="layout">
    <header>...</header>
    <main>
      <!-- Slot: content from parent goes here -->
      <slot></slot>
    </main>
  </div>
</template>
```

### Using Slots:
```vue
<!-- Parent component -->
<template>
  <MainLayout>
    <!-- This content will be inserted into the slot -->
    <h1>Dashboard</h1>
    <p>Welcome to the dashboard</p>
  </MainLayout>
</template>
```

### Named Slots:
```vue
<!-- Child component -->
<template>
  <div>
    <header>
      <slot name="header"></slot>
    </header>
    <main>
      <slot></slot>  <!-- Default slot -->
    </main>
    <footer>
      <slot name="footer"></slot>
    </footer>
  </div>
</template>

<!-- Parent component -->
<template>
  <MyComponent>
    <template #header>
      <h1>Header Content</h1>
    </template>
    
    <p>Main content</p>
    
    <template #footer>
      <p>Footer Content</p>
    </template>
  </MyComponent>
</template>
```

---

## 11. Directives

### Built-in Directives

#### v-model - Two-way Binding
```vue
<template>
  <input v-model="username" />
  <!-- Equivalent to: -->
  <input 
    :value="username" 
    @input="username = $event.target.value"
  />
</template>
```

#### v-if / v-else / v-show
```vue
<template>
  <!-- v-if: conditionally render (removes from DOM) -->
  <div v-if="isLoggedIn">Welcome!</div>
  <div v-else>Please login</div>
  
  <!-- v-show: conditionally display (uses CSS display) -->
  <div v-show="isVisible">Visible content</div>
</template>
```

#### v-for - List Rendering
```vue
<template>
  <!-- Loop through array -->
  <div v-for="user in users" :key="user.id">
    {{ user.name }}
  </div>
  
  <!-- Loop with index -->
  <div v-for="(user, index) in users" :key="user.id">
    {{ index }}: {{ user.name }}
  </div>
</template>
```

#### v-bind - Attribute Binding
```vue
<template>
  <!-- Bind attribute -->
  <img v-bind:src="imageUrl" />
  
  <!-- Shorthand -->
  <img :src="imageUrl" />
  
  <!-- Bind multiple attributes -->
  <div :class="{ active: isActive }" :style="{ color: textColor }">
</template>
```

#### v-on - Event Handling
```vue
<template>
  <!-- Bind event -->
  <button v-on:click="handleClick">Click</button>
  
  <!-- Shorthand -->
  <button @click="handleClick">Click</button>
  
  <!-- With modifiers -->
  <form @submit.prevent="handleSubmit">
  <input @keyup.enter="search">
</template>
```

### Custom Directives

#### Example from directives/index.js:
```javascript
// Click outside directive
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

#### Using Custom Directives:
```vue
<template>
  <div v-click-outside="closeMenu">
    Menu content
  </div>
</template>

<script setup>
const closeMenu = () => {
  console.log('Clicked outside')
}
</script>
```

---

## 12. Teleport

### What is it?
Teleport allows you to render content in a different part of the DOM tree.

### Example:
```vue
<template>
  <div>
    <button @click="showModal = true">Open Modal</button>
    
    <!-- Teleport modal to body -->
    <Teleport to="body">
      <div v-if="showModal" class="modal">
        <p>Modal content</p>
        <button @click="showModal = false">Close</button>
      </div>
    </Teleport>
  </div>
</template>
```

### Why use it?
- Render modals at document root
- Avoid z-index issues
- Better for accessibility

---

## 13. Suspense

### What is it?
Suspense handles async components with loading states.

### Example:
```vue
<template>
  <Suspense>
    <!-- Async component -->
    <template #default>
      <AsyncComponent />
    </template>
    
    <!-- Loading fallback -->
    <template #fallback>
      <div>Loading...</div>
    </template>
  </Suspense>
</template>

<script setup>
// Async component
const AsyncComponent = defineAsyncComponent(() =>
  import('./components/HeavyComponent.vue')
)
</script>
```

---

## 14. Dynamic Components

### What is it?
Switch between components dynamically.

### Example:
```vue
<template>
  <div>
    <button @click="currentTab = 'Home'">Home</button>
    <button @click="currentTab = 'Profile'">Profile</button>
    
    <!-- Dynamic component -->
    <component :is="currentTab" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Home from './Home.vue'
import Profile from './Profile.vue'

const currentTab = ref('Home')
</script>
```

### With KeepAlive:
```vue
<template>
  <!-- Keep component state when switching -->
  <KeepAlive>
    <component :is="currentTab" />
  </KeepAlive>
</template>
```

---

## Summary

This project demonstrates:
- ✅ Composition API with `<script setup>`
- ✅ Reactive state with `ref()` and `reactive()`
- ✅ Computed properties with `computed()`
- ✅ Watchers with `watch()` and `watchEffect()`
- ✅ Lifecycle hooks (`onMounted`, etc.)
- ✅ Pinia store for state management
- ✅ Vue Router for navigation
- ✅ Composables for reusable logic
- ✅ Props and Emits for component communication
- ✅ Slots for content distribution
- ✅ Built-in and custom directives
- ✅ Teleport for DOM manipulation
- ✅ Suspense for async components
- ✅ Dynamic components

Each concept is used in real-world scenarios throughout the application!
