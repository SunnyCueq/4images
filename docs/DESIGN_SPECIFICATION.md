# 4images Design Specification v2.0
**Professional Gallery System - Complete Design Refresh**

---

## Design Philosophy Change

### OLD (v1.0 - 1:1 Modernisierung):
- ❌ Original-Layout 1:1 beibehalten
- ❌ Nur Code modernisieren, Design unverändert
- ❌ Tables durch Divs ersetzen, aber gleiche Optik

### NEW (v2.0 - Professional Refresh):
- ✅ **Modernes, professionelles Galerie-Design**
- ✅ **Tailwind UI Patterns** als Basis
- ✅ **Beste UX-Practices** umsetzen
- ✅ **Mobile-First, aber Desktop-optimiert**
- ✅ **Konsistentes Design-System** überall

---

## Current Problems (Must Fix)

### 1. Inconsistent Component Styling
**Problem:** Card headers have different classes everywhere
```html
<!-- Beispiele aktuell: -->
<div class="bg-brand-blue text-white px-6 py-4 font-semibold flex items-center gap-2">
<div class="bg-brand-blue text-white px-4 py-3 font-semibold text-sm flex items-center gap-2">
<div class="bg-brand-blue text-white px-6 py-4 font-semibold">
```

**Solution:** Standardisierte Komponenten-Klassen definieren

### 2. Image Thumbnails Too Large
**Problem:** New images displayed at full size, no proper thumbnail logic
**Solution:** Fix thumbnail rendering, enforce aspect ratios

### 3. Outdated Navigation
**Problem:** Simple link-based navigation, no modern navbar
**Solution:** Tailwind UI Navbar with category dropdowns

### 4. Basic Breadcrumbs
**Problem:** No visual styling, just text links
**Solution:** Tailwind UI "Simple with slashes" pattern

### 5. Basic Footer
**Problem:** Simple 3-column layout, no company info
**Solution:** Tailwind UI "4-column with company mission"

### 6. No Proper Mobile Menu
**Problem:** Basic hamburger toggle, no icon menu
**Solution:** Tailwind UI "With icons in mobile menu"

### 7. Homepage Cluttered
**Problem:** Categories listed on homepage take too much space
**Solution:** Move categories to navbar dropdown, focus on latest images

---

## Design System

### Color Palette
```css
Primary (Brand Blue):    #004C75  /* Hauptfarbe - Buttons, Headers */
Primary Dark:            #003d5c  /* Hover States */
Primary Light:           #0F5475  /* Borders, Subtle */

Accent (Yellow):         #fbbf24  /* Highlights, Icons */
Accent Hover:            #f59e0b  /* Hover States */

Neutrals:
  - White:               #ffffff
  - Gray 50:             #f9fafb  /* Backgrounds */
  - Gray 100:            #f3f4f6  /* Hover Backgrounds */
  - Gray 200:            #e5e7eb  /* Borders */
  - Gray 300:            #d1d5db  /* Dividers */
  - Gray 600:            #4b5563  /* Secondary Text */
  - Gray 700:            #374151  /* Primary Text */
  - Gray 900:            #111827  /* Headings */

Semantic:
  - Success:             #10b981
  - Warning:             #f59e0b
  - Error:               #ef4444
  - Info:                #3b82f6
```

### Typography Scale
```css
Headings:
  - H1: text-4xl font-bold     (36px / 2.25rem)
  - H2: text-3xl font-bold     (30px / 1.875rem)
  - H3: text-2xl font-semibold (24px / 1.5rem)
  - H4: text-xl font-semibold  (20px / 1.25rem)
  - H5: text-lg font-medium    (18px / 1.125rem)

Body:
  - Large:    text-lg          (18px)
  - Regular:  text-base        (16px) - DEFAULT
  - Small:    text-sm          (14px)
  - XSmall:   text-xs          (12px)

Font Family:
  - System Font Stack (schnell, nativ)
  - font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, etc.
```

### Spacing System
```css
Container:
  - Max Width:   max-w-7xl     (1280px) - NEUE Breite (vorher 1152px)
  - Padding:     px-4 sm:px-6 lg:px-8

Component Spacing:
  - Section:     py-12 lg:py-16
  - Card:        p-6 lg:p-8
  - Button:      px-4 py-2
  - Icon Gap:    gap-2 (0.5rem)
  - Element Gap: gap-4 (1rem)
```

### Border Radius
```css
- sm:  rounded-sm   (0.125rem / 2px)
- md:  rounded-md   (0.375rem / 6px)  - DEFAULT Cards
- lg:  rounded-lg   (0.5rem / 8px)    - Buttons
- xl:  rounded-xl   (0.75rem / 12px)  - Large Cards
- full: rounded-full                  - Avatars, Pills
```

### Shadow System
```css
- sm:  shadow-sm    - Subtle elevation
- md:  shadow-md    - DEFAULT Cards
- lg:  shadow-lg    - Hover States, Modals
- xl:  shadow-xl    - Popups, Dropdowns
```

---

## Component Library (STANDARDIZED)

### 1. Card Component (MUST USE!)

**Problem:** Inconsistent card headers everywhere!

**Standard Card Header:**
```html
<div class="bg-brand-blue text-white px-6 py-4 flex items-center gap-2">
  <i class="fa-solid fa-icon text-lg"></i>
  <h3 class="text-lg font-semibold">Titel</h3>
</div>
```

**Rules:**
- ✅ ALWAYS: `bg-brand-blue text-white px-6 py-4 flex items-center gap-2`
- ✅ Icon: `text-lg` (consistent!)
- ✅ Title: `text-lg font-semibold` (consistent!)
- ❌ NO variations in padding/sizing!

**Full Card Example:**
```html
<div class="bg-white rounded-lg shadow-md overflow-hidden">
  <!-- Header -->
  <div class="bg-brand-blue text-white px-6 py-4 flex items-center gap-2">
    <i class="fa-solid fa-images text-lg"></i>
    <h3 class="text-lg font-semibold">Neue Bilder</h3>
  </div>
  <!-- Content -->
  <div class="p-6">
    <!-- Card content here -->
  </div>
</div>
```

### 2. Navigation Components

#### Primary Navbar (Desktop)
**Reference:** Tailwind UI - Application UI / Navigation / Navbars
```html
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <div class="flex items-center gap-8">
        <a href="/" class="text-2xl font-bold text-brand-blue">4images</a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-6">
          <a href="/" class="text-gray-700 hover:text-brand-blue transition">Home</a>

          <!-- Categories Dropdown (with nested support) -->
          <div class="relative group">
            <button class="text-gray-700 hover:text-brand-blue transition flex items-center gap-1">
              Kategorien
              <i class="fa-solid fa-chevron-down text-xs"></i>
            </button>
            <!-- Mega Menu Dropdown -->
            <div class="hidden group-hover:block absolute top-full left-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 py-2">
              <!-- Category items with subcategories -->
            </div>
          </div>

          <a href="/search" class="text-gray-700 hover:text-brand-blue transition">Suche</a>
          <a href="/top" class="text-gray-700 hover:text-brand-blue transition">Top Bilder</a>
        </div>
      </div>

      <!-- Right Side: Search + User -->
      <div class="flex items-center gap-4">
        <!-- Quick Search -->
        <form action="/search" class="hidden lg:block">
          <input type="search" placeholder="Suche..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue w-64">
        </form>

        <!-- User Menu or Login -->
        <div class="flex items-center gap-2">
          <!-- User logged in: Profile dropdown -->
          <!-- User not logged in: Login/Register buttons -->
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden" id="mobile-menu-button">
          <i class="fa-solid fa-bars text-xl text-gray-700"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div class="hidden md:hidden" id="mobile-menu">
    <!-- Slide-down menu -->
  </div>
</nav>
```

#### Mobile Menu
**Reference:** Tailwind UI - With icons in mobile menu
```html
<div class="md:hidden border-t border-gray-200" id="mobile-menu">
  <div class="px-4 py-4 space-y-2">
    <a href="/" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
      <i class="fa-solid fa-home text-brand-blue"></i>
      <span class="font-medium">Home</span>
    </a>
    <a href="/categories" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
      <i class="fa-solid fa-folder text-brand-blue"></i>
      <span class="font-medium">Kategorien</span>
    </a>
    <a href="/search" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
      <i class="fa-solid fa-magnifying-glass text-brand-blue"></i>
      <span class="font-medium">Suche</span>
    </a>
    <a href="/top" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
      <i class="fa-solid fa-trophy text-brand-blue"></i>
      <span class="font-medium">Top Bilder</span>
    </a>
  </div>
</div>
```

#### Breadcrumbs
**Reference:** Tailwind UI - Simple with slashes
```html
<nav class="flex py-3" aria-label="Breadcrumb">
  <ol class="flex items-center space-x-2 text-sm">
    <li>
      <a href="/" class="text-gray-500 hover:text-brand-blue transition">
        <i class="fa-solid fa-home"></i>
      </a>
    </li>
    <li class="text-gray-300">/</li>
    <li>
      <a href="/categories" class="text-gray-500 hover:text-brand-blue transition">Kategorien</a>
    </li>
    <li class="text-gray-300">/</li>
    <li>
      <a href="/category/1" class="text-gray-500 hover:text-brand-blue transition">Natur</a>
    </li>
    <li class="text-gray-300">/</li>
    <li class="text-gray-900 font-medium">Bild Detail</li>
  </ol>
</nav>
```

### 3. Image Components

#### Image Thumbnail (FIXED SIZE!)
```html
<a href="/details?id=123" class="group block">
  <div class="aspect-square overflow-hidden rounded-lg bg-gray-100 relative">
    <!-- Image with proper sizing -->
    <img
      src="/thumbnails/image_thumb.jpg"
      alt="Image Title"
      class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
      loading="lazy"
    >

    <!-- Overlay on hover -->
    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center">
      <div class="opacity-0 group-hover:opacity-100 transition flex gap-2">
        <button class="p-2 bg-white rounded-full hover:bg-gray-100">
          <i class="fa-solid fa-heart text-red-500"></i>
        </button>
        <button class="p-2 bg-white rounded-full hover:bg-gray-100">
          <i class="fa-solid fa-share-nodes text-brand-blue"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Image Info -->
  <div class="mt-2">
    <h4 class="font-semibold text-gray-900 line-clamp-1">Bildtitel</h4>
    <p class="text-sm text-gray-600">von Username</p>
    <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
      <span class="flex items-center gap-1">
        <i class="fa-solid fa-eye"></i>
        1,234
      </span>
      <span class="flex items-center gap-1">
        <i class="fa-solid fa-heart"></i>
        56
      </span>
      <span class="flex items-center gap-1">
        <i class="fa-solid fa-comment"></i>
        12
      </span>
    </div>
  </div>
</a>
```

**Rules:**
- ✅ ALWAYS: `aspect-square` for consistent grid
- ✅ ALWAYS: `object-cover` to fill space
- ✅ ALWAYS: `loading="lazy"` for performance
- ✅ Hover effects: scale + overlay
- ❌ NO full-size images in thumbnails!

#### Image Grid
```html
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
  <!-- Thumbnail cards here -->
</div>
```

### 4. Form Components (STANDARDIZED)

#### Text Input
```html
<div>
  <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
  <input
    type="text"
    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent"
  >
</div>
```

#### Textarea
```html
<textarea
  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent resize-y"
  rows="5"
></textarea>
```

#### Select
```html
<select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent">
  <option>Option 1</option>
  <option>Option 2</option>
</select>
```

#### Buttons (STANDARDIZED!)

**UPDATED 2025-01-31:** Button standards updated to reflect actual usage after audit

```html
<!-- Primary Button (Large - Form Submits) -->
<input type="submit" value="Submit" class="px-6 py-3 bg-brand-blue hover:bg-brand-blue-dark text-white font-semibold rounded-lg transition cursor-pointer min-w-[140px]" />

<!-- Primary Button (Medium - Inline Actions) -->
<button class="px-6 py-2.5 bg-brand-blue hover:bg-brand-blue-dark text-white font-semibold rounded-lg transition inline-flex items-center gap-2">
  <i class="fa-solid fa-icon"></i>
  Button Text
</button>

<!-- Primary Button (Small - Compact Controls) -->
<button class="px-4 py-2 bg-brand-blue hover:bg-brand-blue-dark text-white font-semibold rounded-lg transition text-sm">
  Go
</button>

<!-- Secondary Button (Cancel/Reset) -->
<input type="reset" value="Reset" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition cursor-pointer min-w-[140px]" />

<!-- Danger Button (Delete Actions) -->
<input type="submit" value="Delete" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition cursor-pointer min-w-[140px]" />

<!-- Success Button (Confirmations) -->
<button class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition cursor-pointer min-w-[140px]">
  Confirm
</button>
```

**Rules:**
- ✅ Large Buttons (Form Submits): `px-6 py-3` + `min-w-[140px]`
- ✅ Medium Buttons (Actions): `px-6 py-2.5`
- ✅ Small Buttons (Compact): `px-4 py-2` + `text-sm`
- ✅ ALWAYS: `font-semibold rounded-lg transition`
- ✅ Icon + Text: `inline-flex items-center gap-2`
- ✅ Input buttons: `cursor-pointer` class required
- ✅ Secondary buttons use `gray-500` (NOT gray-200 - better visual hierarchy)
- ❌ NO other padding variations!

### 5. Footer Component

**Reference:** Tailwind UI - 4-column with company mission
```html
<footer class="bg-gray-900 text-white mt-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

      <!-- Column 1: Company Mission -->
      <div class="lg:col-span-2">
        <h3 class="text-2xl font-bold mb-4">4images Gallery</h3>
        <p class="text-gray-400 mb-4 leading-relaxed">
          Moderne Bildergalerie-Verwaltung mit PHP und Tailwind CSS.
          Perfekt für Fotografen, Künstler und kreative Projekte.
        </p>
        <!-- Social Links -->
        <div class="flex gap-4">
          <a href="#" class="text-gray-400 hover:text-white transition">
            <i class="fa-brands fa-facebook text-xl"></i>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition">
            <i class="fa-brands fa-twitter text-xl"></i>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition">
            <i class="fa-brands fa-instagram text-xl"></i>
          </a>
        </div>
      </div>

      <!-- Column 2: Navigation -->
      <div>
        <h4 class="font-semibold text-lg mb-4">Navigation</h4>
        <ul class="space-y-2">
          <li><a href="/" class="text-gray-400 hover:text-white transition">Home</a></li>
          <li><a href="/categories" class="text-gray-400 hover:text-white transition">Kategorien</a></li>
          <li><a href="/search" class="text-gray-400 hover:text-white transition">Suche</a></li>
          <li><a href="/top" class="text-gray-400 hover:text-white transition">Top Bilder</a></li>
        </ul>
      </div>

      <!-- Column 3: Account -->
      <div>
        <h4 class="font-semibold text-lg mb-4">Account</h4>
        <ul class="space-y-2">
          <li><a href="/login" class="text-gray-400 hover:text-white transition">Login</a></li>
          <li><a href="/register" class="text-gray-400 hover:text-white transition">Registrieren</a></li>
          <li><a href="/member" class="text-gray-400 hover:text-white transition">Mein Bereich</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
      <p class="text-gray-400 text-sm">
        © 2025 4images Gallery. Powered by <a href="https://www.4homepages.de" class="text-brand-yellow hover:underline">4homepages.de</a>
      </p>
      <div class="flex gap-6 text-sm">
        <a href="/privacy" class="text-gray-400 hover:text-white transition">Datenschutz</a>
        <a href="/terms" class="text-gray-400 hover:text-white transition">AGB</a>
        <a href="/contact" class="text-gray-400 hover:text-white transition">Kontakt</a>
      </div>
    </div>
  </div>
</footer>
```

---

## Page Layouts

### Homepage
```
┌─────────────────────────────────────────┐
│ Navbar (sticky)                         │
├─────────────────────────────────────────┤
│ ┌─────────┬─────────────────────────┐  │
│ │ Sidebar │ Content                 │  │
│ │ (3 col) │ (9 col)                 │  │
│ │         │                         │  │
│ │ User    │ Neue Bilder (Grid 3x3)  │  │
│ │ Random  │                         │  │
│ │ Online  │ Top Bilder (Grid 3x3)   │  │
│ │         │                         │  │
│ └─────────┴─────────────────────────┘  │
├─────────────────────────────────────────┤
│ Footer (4-column)                       │
└─────────────────────────────────────────┘
```

### Category View
```
┌─────────────────────────────────────────┐
│ Navbar                                  │
├─────────────────────────────────────────┤
│ Breadcrumbs (with slashes)              │
├─────────────────────────────────────────┤
│ ┌─────────┬─────────────────────────┐  │
│ │ Sidebar │ Category Header         │  │
│ │         ├─────────────────────────┤  │
│ │ Filter  │ Image Grid (3-4 cols)   │  │
│ │ Sorting │                         │  │
│ │         │ Pagination              │  │
│ └─────────┴─────────────────────────┘  │
├─────────────────────────────────────────┤
│ Footer                                  │
└─────────────────────────────────────────┘
```

### Image Detail
```
┌─────────────────────────────────────────┐
│ Navbar                                  │
├─────────────────────────────────────────┤
│ Breadcrumbs                             │
├─────────────────────────────────────────┤
│ ┌────────────────┬──────────────────┐  │
│ │ Image (60%)    │ Info (40%)       │  │
│ │                │ - Title          │  │
│ │                │ - Stats          │  │
│ │                │ - Description    │  │
│ │                │ - Actions        │  │
│ └────────────────┴──────────────────┘  │
├─────────────────────────────────────────┤
│ Comments Section                        │
├─────────────────────────────────────────┤
│ Related Images (Grid)                   │
├─────────────────────────────────────────┤
│ Footer                                  │
└─────────────────────────────────────────┘
```

---

## Responsive Behavior

### Breakpoints
```css
sm:  640px   - Mobile landscape, small tablets
md:  768px   - Tablets
lg:  1024px  - Small desktops
xl:  1280px  - Large desktops
2xl: 1536px  - Extra large screens
```

### Layout Changes
```
Mobile (<768px):
  - Sidebar below content (full width)
  - Image grid: 2 columns
  - Navbar: Hamburger menu

Tablet (768-1024px):
  - Sidebar left (4 columns)
  - Content right (8 columns)
  - Image grid: 3 columns
  - Navbar: Simplified

Desktop (>1024px):
  - Sidebar left (3 columns)
  - Content right (9 columns)
  - Image grid: 3-4 columns
  - Navbar: Full featured
```

---

## Animation & Transitions

### Standard Transitions
```css
Links:      transition duration-150
Buttons:    transition duration-200
Cards:      transition duration-300
Images:     transition duration-300
Modals:     transition duration-200
```

### Hover Effects
```css
Cards:      hover:shadow-lg
Images:     hover:scale-105
Buttons:    hover:bg-*-dark
Links:      hover:text-brand-blue
```

---

## Accessibility

### Focus States (MANDATORY!)
```css
All interactive elements:
  focus:outline-none
  focus:ring-2
  focus:ring-brand-blue
  focus:ring-offset-2
```

### Semantic HTML
- Use `<nav>`, `<main>`, `<aside>`, `<footer>`
- Use `<article>` for image cards
- Use `<button>` not `<div>` for clickable elements
- Use proper heading hierarchy (h1 → h6)

### ARIA Labels
```html
<nav aria-label="Main navigation">
<nav aria-label="Breadcrumb">
<button aria-label="Close menu">
<img alt="Descriptive text">
```

---

## Performance

### Image Optimization
```html
<!-- Lazy loading -->
<img loading="lazy" src="..." alt="...">

<!-- Responsive images -->
<img
  srcset="image-400w.jpg 400w, image-800w.jpg 800w"
  sizes="(max-width: 640px) 400px, 800px"
  src="image-800w.jpg"
  alt="..."
>
```

### CSS Performance
- Tailwind CSS Play CDN (development)
- Purged CSS for production (TODO)
- Critical CSS inline for first paint

### JavaScript
- External scripts.js (defer loading)
- Minimal inline JavaScript
- No jQuery (Vanilla ES6+)

---

## Implementation Priority

See [ROADMAP.md](ROADMAP.md) for detailed implementation plan.

### Phase 1: Fix Inconsistencies (Week 1)
1. Standardize all card headers
2. Fix thumbnail sizing
3. Standardize button styles
4. Fix form inputs

### Phase 2: Navigation Upgrade (Week 2)
1. New navbar with dropdowns
2. Breadcrumbs redesign
3. Mobile menu with icons
4. Category mega menu

### Phase 3: Footer & Layout (Week 3)
1. New footer (4-column)
2. Homepage layout improvements
3. Sidebar widgets redesign

### Phase 4: Image Components (Week 4)
1. Thumbnail cards with overlays
2. Image detail page redesign
3. Image grid improvements
4. Lightbox/modal

### Phase 5: Forms & Polish (Week 5)
1. All forms standardized
2. User profile pages
3. Upload interface
4. Admin area (basic)

---

**Next:** See [ROADMAP.md](ROADMAP.md) for step-by-step implementation guide.
