# Laravel 13 + Vue 3 + Inertia Admin Starter Kit

A cutting-edge, professional starter kit for building SaaS applications and Admin Dashboards. Built with the latest **Laravel 13**, **Inertia.js**, and **Vue 3**, featuring a robust Role-Based Access Control (RBAC) system.

![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red)
![Vue Version](https://img.shields.io/badge/Vue-3.x-green)
![Inertia Version](https://img.shields.io/badge/Inertia-v1.x-blue)

##  Features

-  Full RBAC Integration: Powered by [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission). Manage Users, Roles, and Permissions seamlessly.
-  Laravel 13 Ready: Utilizes the latest v13 slim structure and improved routing patterns.
-  Modern Frontend: Vue 3 (Composition API) + Inertia.js for a smooth SPA experience without the API complexity.
-  Shadcn UI & Tailwind: Beautifully styled with a focus on dark mode and accessibility.
-  Production-Ready CRUDs:
    - User Management: Full search, pagination, and role synchronization.
    - Role Management: Create and assign permissions to roles.
    - Permission Management: Granular control over your application logic.
-  Default Role Assignment: Automated logic to assign a 'user' role to every new registration via Fortify Actions.

---

##  Tech Stack

- Framework: Laravel 13.x
- Frontend: Vue 3 (TypeScript)
- Inertia.js
- Auth: Laravel Fortify
- Authorization: Spatie Permissions
- Design: Tailwind CSS / Shadcn UI
- Icons: Lucide Vue Next

---

##  Quick Start

1. **Clone & Install:**
   ```bash
   git clone https://github.com/ardianimeri/laravel-starter-kit-with-roles-and-permissions.git
   cd laravel-starter-kit-with-roles-and-permissions
   composer install
   npm install

2.   **Environment Setup:**
      ```bash
      cp .env.example .env
      php artisan key:generate
3. **Database & Seeding:**
    ```bash
   php artisan migrate --seed
4. **Launch:**
   ```bash
   php artisan serve
   npm run dev
