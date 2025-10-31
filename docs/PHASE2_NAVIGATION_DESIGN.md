# Phase 2: Navigation Design Specification

**Date:** 2025-01-31
**Status:** Planning
**Goal:** Modern navigation with category mega menu

---

## Current State Analysis

### What Exists Now:
- ✅ Sticky header with logo and search
- ✅ Desktop navigation bar (Home, Top, New, Search)
- ✅ Mobile menu with hamburger toggle
- ✅ Search form in header (desktop + mobile)
- ❌ **NO categories in navigation!**
- ❌ Categories only shown on homepage body
- ❌ Basic breadcrumbs (chevron separators)

### Problems:
1. **Categories not accessible from navigation** - User muss zur Homepage gehen
2. **No category hierarchy in menu** - Keine schnelle Navigation zu Unterkategorien
3. **Breadcrumbs use chevrons** - Sollen Slashes verwenden (Tailwind UI Standard)

---

## New Navigation Structure

### Desktop Navbar (lg+)

```
┌─────────────────────────────────────────────────────────────────┐
│ [Logo] 4images      [Search Box...]  [🔍 Search]                │
├─────────────────────────────────────────────────────────────────┤
│ 🏠 Home  📁 Kategorien ▼  ⭐ Top  🕐 Neu  🔍 Suche  │  👤 Login  │
│          └─ Mega Menu (hover)                                   │
└─────────────────────────────────────────────────────────────────┘
```

**Features:**
- **Logo + Site Name** (links)
- **Search Bar** (rechts, immer sichtbar)
- **Navigation Links** mit Icons
- **Kategorien Dropdown** (Mega Menu on hover)
- **User Menu** (Login/Profile, rechts)

### Mobile Menu (<lg)

```
┌────────────────────────┐
│ [Logo]          [☰]    │
├────────────────────────┤
│ [Slide-Down Menu]      │
│                        │
│ [Search Box...]  [🔍]  │
│                        │
│ 🏠 Home                │
│ 📁 Kategorien ▼        │
│   └─ Natur            │
│   └─ Architektur      │
│   └─ Mehr...          │
│ ⭐ Top Bilder          │
│ 🕐 Neue Bilder         │
│ 🔍 Erweiterte Suche    │
│ ─────────────────      │
│ 👤 Login / Profil      │
└────────────────────────┘
```

**Features:**
- **Hamburger Button** (fa-bars)
- **Slide-down animation**
- **Search at top** (mobil-optimiert)
- **Collapsible Categories** (accordion-style)
- **User section at bottom**

---

## Category Mega Menu Design

### Structure (3 Levels Maximum)

```html
<div class="category-mega-menu">
  <!-- Level 1: Main Categories (mit Icons) -->
  <div class="category-column">
    <a href="/cat/natur" class="category-main">
      <i class="fa-solid fa-tree"></i>
      Natur
    </a>
    <!-- Level 2: Subcategories -->
    <ul class="subcategories">
      <li><a href="/cat/natur/landschaften">Landschaften</a></li>
      <li><a href="/cat/natur/tiere">Tiere</a></li>
      <li><a href="/cat/natur/pflanzen">Pflanzen</a></li>
    </ul>
  </div>

  <div class="category-column">
    <a href="/cat/architektur" class="category-main">
      <i class="fa-solid fa-building"></i>
      Architektur
    </a>
    <ul class="subcategories">
      <li><a href="/cat/architektur/modern">Modern</a></li>
      <li><a href="/cat/architektur/historisch">Historisch</a></li>
    </ul>
  </div>

  <!-- More columns... -->

  <!-- Footer Link -->
  <div class="category-footer">
    <a href="/categories.php">
      Alle Kategorien anzeigen →
    </a>
  </div>
</div>
```

### Styling (Tailwind Classes)

```html
<!-- Desktop Dropdown Trigger -->
<button class="group relative flex items-center gap-1 text-gray-700 hover:text-brand-blue transition">
  <i class="fa-solid fa-folder-open"></i>
  Kategorien
  <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
</button>

<!-- Mega Menu Container (hidden by default, shown on hover) -->
<div class="absolute top-full left-0 mt-2 w-screen max-w-4xl bg-white rounded-lg shadow-2xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
  <div class="p-6 grid grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Category Column -->
    <div>
      <a href="/cat/natur" class="flex items-center gap-2 font-semibold text-brand-blue hover:text-brand-blue-dark mb-3 group">
        <i class="fa-solid fa-tree text-lg"></i>
        <span class="group-hover:underline">Natur</span>
        <span class="text-xs text-gray-500">(245)</span>
      </a>
      <ul class="space-y-2 ml-7">
        <li>
          <a href="/cat/natur/landschaften" class="text-sm text-gray-700 hover:text-brand-blue hover:underline">
            Landschaften
          </a>
        </li>
        <li>
          <a href="/cat/natur/tiere" class="text-sm text-gray-700 hover:text-brand-blue hover:underline">
            Tiere
          </a>
        </li>
      </ul>
    </div>

    <!-- More columns... -->

  </div>

  <!-- Footer -->
  <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
    <a href="/categories.php" class="text-sm font-medium text-brand-blue hover:text-brand-blue-dark inline-flex items-center gap-2">
      Alle Kategorien anzeigen
      <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>
</div>
```

### Hover Behavior

**Desktop:**
- Hover über "Kategorien" → Mega Menu erscheint (opacity 0→100, invisible→visible)
- Mouse leave → Menu verschwindet (200ms delay)
- Smooth transitions

**Mobile:**
- Click auf "Kategorien" → Accordion öffnet sich
- Shows top 5 categories + "Mehr..." link
- No mega menu on mobile (zu komplex)

---

## Breadcrumbs Redesign

### Before (Aktuell):
```html
<nav class="flex items-center gap-2 text-sm text-gray-600 mb-6">
  <a href="{url_home}">
    <i class="fa-solid fa-home"></i>
  </a>
  <i class="fa-solid fa-chevron-right text-xs"></i>
  <span>{clickstream}</span>
</nav>
```

### After (Tailwind UI Style):
```html
<nav class="flex items-center gap-2 text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
  <ol class="flex items-center gap-2">
    <li>
      <a href="{url_home}" class="text-gray-500 hover:text-brand-blue transition" aria-label="Home">
        <i class="fa-solid fa-home"></i>
      </a>
    </li>
    <li class="text-gray-400">/</li>
    <li>
      <a href="{url_categories}" class="text-gray-700 hover:text-brand-blue hover:underline">
        Kategorien
      </a>
    </li>
    <li class="text-gray-400">/</li>
    <li>
      <a href="{url_category_natur}" class="text-gray-700 hover:text-brand-blue hover:underline">
        Natur
      </a>
    </li>
    <li class="text-gray-400">/</li>
    <li class="text-gray-900 font-medium" aria-current="page">
      Landschaften
    </li>
  </ol>
</nav>
```

**Changes:**
- ✅ Slashes `/` instead of chevrons `>`
- ✅ Semantic `<ol>` list
- ✅ `aria-label` for accessibility
- ✅ Current page: bold, no link, aria-current
- ✅ Hover underlines on links

---

## Container Width Update

**Current:** `max-w-6xl` (1152px)
**New:** `max-w-7xl` (1280px) - Per DESIGN_SPECIFICATION.md

**Affected:**
- header.html: Main container
- All page templates: Main content container

**Change:**
```diff
- <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
+ <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
```

---

## Implementation Plan

### Task 2.1: Add Categories to Navigation ✓ (This Document)
- [x] Design navbar structure
- [x] Design mega menu layout
- [x] Design breadcrumbs
- [x] Plan responsive behavior

### Task 2.2: Implement Category Mega Menu (2-3 days)
**Files:**
- header.html (add dropdown structure)
- scripts.js (hover logic if needed, or use CSS :hover)

**Steps:**
1. Add "Kategorien" dropdown button to desktop nav
2. Create mega menu structure (hidden by default)
3. Add category data (from PHP variables)
4. Style with Tailwind (grid layout, hover states)
5. Test hover behavior
6. Ensure accessible (keyboard navigation)

### Task 2.3: Mobile Category Menu (1 day)
**Files:**
- header.html (mobile menu section)
- scripts.js (accordion toggle)

**Steps:**
1. Add "Kategorien" accordion to mobile menu
2. Implement toggle JavaScript
3. Show top 5-10 categories + "Mehr..." link
4. Smooth transitions

### Task 2.4: Redesign Breadcrumbs (1 day)
**Files:**
- All templates with breadcrumbs (home, details, categories, search, member)

**Steps:**
1. Create new breadcrumb pattern
2. Replace all `{clickstream}` usages
3. Use slashes instead of chevrons
4. Add semantic HTML + ARIA

### Task 2.5: Update Container Width (30 min)
**Files:**
- header.html
- All templates

**Steps:**
1. Replace `max-w-6xl` → `max-w-7xl`
2. Test on verschiedenen Bildschirmgrößen

---

## Required PHP Variables

**Need to check if these exist:**
- `{categories}` - Category tree data
- `{category_dropdown}` - Dropdown HTML (might exist)
- `{url_categories}` - Link to categories page

**If not available:**
- May need to create custom PHP logic in includes/header.php
- Or use existing category_bit.html template pattern

---

## Next Steps

1. ✅ Read PHP code to find category variables
2. Implement mega menu structure
3. Add JavaScript for hover/accordion
4. Update breadcrumbs
5. Test on desktop + mobile
6. Commit each task separately

**Estimated Time:** 4-5 days for all tasks
