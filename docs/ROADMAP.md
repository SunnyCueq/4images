# 4images Design Refresh - Implementation Roadmap

**Version 2.0 - Professional Gallery System**

---

## Overview

Diese Roadmap beschreibt die schrittweise Implementierung des Design-Refreshs basierend auf [DESIGN_SPECIFICATION.md](DESIGN_SPECIFICATION.md).

**Zeitrahmen:** 5-6 Wochen
**Methodik:** Schrittweise, testbar, commitbar nach jedem Task

---

## Phase 1: Fix Inconsistencies & Standardization
**Dauer:** 5-7 Tage | **Priority:** CRITICAL

### Goal
Alle existierenden Komponenten auf standardisierte Klassen umstellen. Keine neuen Features, nur Konsistenz.

### Task 1.1: Audit Current Templates
**Dauer:** 1 Tag

- [ ] Durchsuche alle 38 Templates nach inkonsistenten Klassen
- [ ] Dokumentiere alle Card-Header-Varianten
- [ ] Dokumentiere alle Button-Varianten
- [ ] Dokumentiere alle Form-Input-Varianten
- [ ] Erstelle Checklist mit betroffenen Dateien

**Output:** `docs/INCONSISTENCIES.md` mit vollständiger Liste

### Task 1.2: Standardize Card Headers
**Dauer:** 2 Tage

**Problem:** Card-Header haben unterschiedliche Klassen:
```html
<!-- Variante 1 -->
<div class="bg-brand-blue text-white px-6 py-4 font-semibold flex items-center gap-2">

<!-- Variante 2 -->
<div class="bg-brand-blue text-white px-4 py-3 font-semibold text-sm flex items-center gap-2">

<!-- Variante 3 -->
<div class="bg-brand-blue text-white px-6 py-4 font-semibold">
```

**Solution:** ALLE auf Standard umstellen:
```html
<div class="bg-brand-blue text-white px-6 py-4 flex items-center gap-2">
  <i class="fa-solid fa-icon text-lg"></i>
  <h3 class="text-lg font-semibold">Titel</h3>
</div>
```

**Affected Files:**
- home.html
- details.html
- categories.html
- search.html
- member.html
- comment_form.html
- user_logininfo.html
- whos_online.html
- register_form.html
- member_uploadform.html
- member_editprofile.html
- member_editimage.html
- member_editcomment.html
- (alle mit Card-Headers)

**Steps:**
1. Read template
2. Find card headers
3. Replace with standard classes
4. Verify icon size (`text-lg`)
5. Verify title size (`text-lg font-semibold`)
6. Test in browser
7. Commit einzeln: `"refactor: Standardize card header in [filename]"`

### Task 1.3: Fix Thumbnail Sizing
**Dauer:** 2 Tage

**Problem:** Thumbnails werden zu groß angezeigt, kein aspect-ratio enforcement.

**Solution:**
```html
<div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
  <img
    src="/thumbnails/image_thumb.jpg"
    alt="..."
    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
    loading="lazy"
  >
</div>
```

**Affected Files:**
- thumbnail_bit.html
- random_image.html
- random_cat_image.html
- home.html (new images section)
- categories.html (image grid)

**Steps:**
1. Update thumbnail_bit.html mit aspect-square
2. Add hover effects (scale-105)
3. Ensure object-cover für alle Thumbnails
4. Add loading="lazy"
5. Test verschiedene Bildgrößen
6. Commit: `"fix: Enforce aspect-square for all thumbnails"`

### Task 1.4: Standardize Buttons
**Dauer:** 1 Tag

**Standard Button Classes:**
```html
<!-- Primary -->
<button class="px-6 py-2.5 bg-brand-blue hover:bg-brand-blue-dark text-white font-semibold rounded-lg transition inline-flex items-center gap-2">
  <i class="fa-solid fa-icon"></i>
  Text
</button>

<!-- Secondary -->
<button class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition">
  Text
</button>

<!-- Danger -->
<button class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
  Text
</button>
```

**Affected Files:** Alle Templates mit Buttons

**Steps:**
1. Grep nach `<button` und `<input type="submit"`
2. Standardisiere Klassen
3. Ensure `px-6 py-2.5` überall
4. Commit: `"refactor: Standardize button styles across templates"`

### Task 1.5: Standardize Form Inputs
**Dauer:** 1 Tag

**Standard Input Classes:**
```html
<input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent">
```

**Affected Files:**
- comment_form.html
- search_form.html
- register_form.html
- member_uploadform.html
- member_editprofile.html
- member_lostpassword.html
- member_mailform.html
- user_loginform.html

**Steps:**
1. Standardisiere alle `<input>`, `<textarea>`, `<select>`
2. Ensure focus states überall
3. Commit: `"refactor: Standardize form input styles"`

---

## Phase 2: Navigation Upgrade
**Dauer:** 7-10 Tage | **Priority:** HIGH

### Task 2.1: Design New Navbar Structure
**Dauer:** 1 Tag

- [ ] Sketch navbar layout (Desktop + Mobile)
- [ ] Plan category dropdown structure (Kategorie → Unterkategorie → Unter-Unterkategorie)
- [ ] Design mobile menu with icons
- [ ] Plan responsive breakpoints

**Output:** Mockup/Wireframe für Navbar

### Task 2.2: Implement Desktop Navbar
**Dauer:** 3 Tage

**Reference:** Tailwind UI - Application UI / Navigation / Navbars

**Features:**
- Logo links
- Navigation Links (Home, Kategorien-Dropdown, Suche, Top Bilder)
- Quick Search Bar (Desktop only, lg+)
- User Menu (Login/Profile Dropdown)
- Sticky on scroll

**File:** header.html

**Steps:**
1. Create new navbar structure
2. Implement category dropdown with hover
3. Add search bar (hidden on mobile)
4. Add user menu dropdown
5. Make sticky (z-50)
6. Test on verschiedenen Bildschirmgrößen
7. Commit: `"feat: Implement professional desktop navbar with dropdowns"`

### Task 2.3: Implement Category Mega Menu
**Dauer:** 2 Tage

**Features:**
- Dropdown on hover (Desktop)
- Shows categories with subcategories
- Max 3 levels deep (Kategorie → Sub → Sub-Sub)
- Icons for top-level categories
- "Alle anzeigen" Link

**Structure:**
```
Kategorien ▼
├─ Natur 🌲
│  ├─ Landschaften
│  ├─ Tiere
│  └─ Pflanzen
├─ Architektur 🏛️
│  ├─ Modern
│  └─ Historisch
└─ Alle Kategorien →
```

**File:** header.html (category dropdown section)

**Steps:**
1. Create dropdown structure
2. Add category tree logic (PHP generates HTML)
3. Style with proper spacing
4. Add icons for top categories
5. Implement hover states
6. Test nested categories
7. Commit: `"feat: Add category mega menu with subcategories"`

### Task 2.4: Implement Mobile Menu
**Dauer:** 2 Tage

**Reference:** Tailwind UI - With icons in mobile menu

**Features:**
- Hamburger button (visible < md)
- Slide-down animation
- Icons for each menu item
- Collapsible category tree
- User profile section at bottom

**File:** header.html + scripts.js

**Steps:**
1. Add mobile menu button
2. Create slide-down menu structure
3. Add icons for each item
4. Implement toggle JavaScript
5. Add smooth transitions
6. Test auf verschiedenen Mobile-Geräten
7. Commit: `"feat: Implement mobile menu with icons and animations"`

### Task 2.5: Redesign Breadcrumbs
**Dauer:** 1 Tag

**Reference:** Tailwind UI - Simple with slashes

**Before:**
```
Home > Kategorien > Natur > Bild
```

**After:**
```
🏠 / Kategorien / Natur / Bild
```

**Features:**
- Home icon
- Slashes as separators
- Hover effects on links
- Current page bold/no link

**Affected Files:**
- details.html
- categories.html
- search.html
- member.html
- (all pages with breadcrumbs)

**Steps:**
1. Create breadcrumb component
2. Add home icon
3. Use slashes as separators
4. Style hover states
5. Update all templates
6. Commit: `"feat: Redesign breadcrumbs with slashes style"`

### Task 2.6: Update Container Width
**Dauer:** 1 Tag

**Change:** `max-w-6xl` (1152px) → `max-w-7xl` (1280px)

**Reason:** Mehr Platz für moderne Layouts

**Affected Files:** ALLE Templates

**Steps:**
1. Global search/replace `max-w-6xl` → `max-w-7xl`
2. Verify layout nicht bricht
3. Test auf großen Bildschirmen
4. Commit: `"refactor: Increase container width to max-w-7xl"`

---

## Phase 3: Footer & Layout Improvements
**Dauer:** 5-7 Tage | **Priority:** MEDIUM

### Task 3.1: Redesign Footer
**Dauer:** 2 Tage

**Reference:** Tailwind UI - 4-column with company mission

**Structure:**
```
┌──────────────────────────────────────────────┐
│ [Company Mission]    [Nav]  [Account] [Info]│
│                                              │
│ ─────────────────────────────────────────── │
│                                              │
│ © 2025 | Links                              │
└──────────────────────────────────────────────┘
```

**Features:**
- 4 columns on desktop (2 cols mobile)
- Company mission (2 columns wide)
- Navigation links
- Account links
- Social media icons
- Bottom bar with copyright

**File:** footer.html

**Steps:**
1. Create new footer structure
2. Add 4-column grid
3. Write company mission text
4. Add social media icons
5. Style links with hover effects
6. Add bottom bar
7. Test responsive behavior
8. Commit: `"feat: Redesign footer with 4-column layout and mission"`

### Task 3.2: Homepage Layout Improvements
**Dauer:** 2 Tage

**Changes:**
- Move categories to navbar (already done in 2.3)
- Focus on latest images
- Add "Top Rated" section
- Improve sidebar widgets

**File:** home.html

**Steps:**
1. Remove category list from content area
2. Increase image grid size (4 columns)
3. Add "Neue Bilder" section
4. Add "Top bewertete" section
5. Style sidebar widgets consistently
6. Test layout
7. Commit: `"refactor: Improve homepage layout, focus on images"`

### Task 3.3: Sidebar Widgets Redesign
**Dauer:** 2 Tage

**Widgets:**
- User Login/Info Box
- Random Image
- Who's Online
- (potentially more)

**Standard Widget Structure:**
```html
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
  <div class="bg-brand-blue text-white px-6 py-4 flex items-center gap-2">
    <i class="fa-solid fa-icon text-lg"></i>
    <h3 class="text-lg font-semibold">Titel</h3>
  </div>
  <div class="p-6">
    <!-- Widget Content -->
  </div>
</div>
```

**Files:**
- user_loginform.html
- user_logininfo.html
- random_image.html
- whos_online.html

**Steps:**
1. Ensure all widgets use standard card structure
2. Consistent spacing (p-6)
3. Consistent headers
4. Test sidebar stacking (mobile)
5. Commit: `"refactor: Standardize sidebar widgets design"`

---

## Phase 4: Image Components Enhancement
**Dauer:** 7-10 Tage | **Priority:** HIGH

### Task 4.1: Enhanced Thumbnail Cards
**Dauer:** 2 Tage

**Features:**
- Hover overlay with quick actions
- Like/Favorite button
- Share button
- View count, likes, comments below
- Smooth animations

**File:** thumbnail_bit.html

**Steps:**
1. Add overlay container
2. Add quick action buttons
3. Add stats below image (views, likes, comments)
4. Style hover effects
5. Add smooth transitions
6. Test interactivity
7. Commit: `"feat: Add hover overlay and stats to thumbnail cards"`

### Task 4.2: Image Detail Page Redesign
**Dauer:** 3 Tage

**Layout:**
```
┌────────────────────────────────────────┐
│ Navbar                                 │
├────────────────────────────────────────┤
│ Breadcrumbs                            │
├────────────────────────────────────────┤
│ ┌──────────────┬───────────────────┐  │
│ │ Image (60%)  │ Info (40%)        │  │
│ │              │ - Title           │  │
│ │              │ - User            │  │
│ │              │ - Stats           │  │
│ │              │ - Description     │  │
│ │              │ - Actions         │  │
│ └──────────────┴───────────────────┘  │
├────────────────────────────────────────┤
│ Tabs: Info | Comments | EXIF          │
├────────────────────────────────────────┤
│ Related Images                         │
└────────────────────────────────────────┘
```

**File:** details.html

**Steps:**
1. Redesign layout (60/40 split)
2. Move info to right sidebar
3. Add tabs for Info/Comments/EXIF
4. Style action buttons
5. Add related images section
6. Test responsive behavior
7. Commit: `"feat: Redesign image detail page with modern layout"`

### Task 4.3: Image Grid Improvements
**Dauer:** 1 Tag

**Changes:**
- Consistent spacing (gap-4)
- Responsive columns (2/3/4)
- Consistent thumbnail cards
- Pagination styling

**Affected Files:**
- categories.html
- search.html
- home.html

**Steps:**
1. Ensure all grids use `grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4`
2. Consistent thumbnail cards
3. Style pagination
4. Test responsive behavior
5. Commit: `"refactor: Standardize image grid layouts"`

### Task 4.4: Lightbox/Modal Implementation
**Dauer:** 2-3 Tage

**Features:**
- Click to enlarge image
- Prev/Next navigation
- Close button
- Smooth transitions
- ESC key to close

**Files:**
- scripts.js (new lightbox code)
- Create lightbox.html component

**Steps:**
1. Design lightbox overlay
2. Implement open/close logic
3. Add prev/next navigation
4. Add keyboard shortcuts
5. Style with Tailwind
6. Test on verschiedenen Bildschirmgrößen
7. Commit: `"feat: Implement modern lightbox for image viewing"`

---

## Phase 5: Forms & User Areas
**Dauer:** 7-10 Tage | **Priority:** MEDIUM

### Task 5.1: Comment Form Redesign
**Dauer:** 1 Tag

**File:** comment_form.html

**Changes:**
- Standard form inputs
- BBCode toolbar styled
- Preview option
- Character counter

**Steps:**
1. Apply standard input styles
2. Redesign BBCode toolbar
3. Add character counter
4. Commit: `"refactor: Redesign comment form with modern styling"`

### Task 5.2: Upload Form Enhancement
**Dauer:** 2 Tage

**File:** member_uploadform.html

**Features:**
- Drag & drop zone for files
- Image preview before upload
- Progress bar during upload
- Better CAPTCHA styling

**Steps:**
1. Add drag & drop zone (JavaScript)
2. Add image preview
3. Style upload button
4. Improve CAPTCHA section
5. Test file uploads
6. Commit: `"feat: Enhance upload form with drag-drop and preview"`

### Task 5.3: User Profile Pages
**Dauer:** 2 Tage

**Files:**
- member_profile.html
- member_editprofile.html

**Changes:**
- Tab navigation (Profile, Images, Settings)
- Avatar upload section
- Stats dashboard
- Image gallery for user

**Steps:**
1. Create tab navigation
2. Redesign profile view
3. Improve edit profile form
4. Add stats section
5. Commit: `"feat: Redesign user profile pages with tabs"`

### Task 5.4: Search Page Enhancement
**Dauer:** 1 Tag

**File:** search_form.html, search.html

**Features:**
- Advanced filters collapsible
- Search suggestions (optional)
- Recent searches
- Better result grid

**Steps:**
1. Make advanced filters collapsible
2. Improve form layout
3. Better result presentation
4. Commit: `"feat: Enhance search page with better UX"`

### Task 5.5: Registration/Login Flow
**Dauer:** 2 Tage

**Files:**
- register_form.html
- register_signup.html
- user_loginform.html

**Features:**
- Modern form styling
- Password strength indicator
- Social login buttons (placeholder)
- Better CAPTCHA

**Steps:**
1. Redesign login form
2. Improve registration form
3. Add password strength indicator (JavaScript)
4. Style agreement page
5. Commit: `"feat: Modernize registration and login flow"`

---

## Phase 6: Admin Area (Basic)
**Dauer:** 5-7 Tage | **Priority:** LOW

### Task 6.1: Admin Navbar
**Dauer:** 1 Tag

**Create:** admin/templates/header.html

**Features:**
- Similar to frontend navbar
- Admin-specific navigation
- Quick stats in header

### Task 6.2: Admin Dashboard
**Dauer:** 2 Tage

**Features:**
- Stats cards (total images, users, comments)
- Recent activity
- Quick actions

### Task 6.3: Admin Tables
**Dauer:** 2 Tage

**Style all admin tables:**
- Consistent styling
- Action buttons
- Pagination
- Sorting indicators

### Task 6.4: Admin Forms
**Dauer:** 1-2 Tage

**Standardize:**
- Image edit form
- Category edit form
- User management forms

---

## Phase 7: Polish & Performance
**Dauer:** 3-5 Tage | **Priority:** MEDIUM

### Task 7.1: Accessibility Audit
**Dauer:** 1 Tag

- [ ] Test keyboard navigation
- [ ] Verify ARIA labels
- [ ] Check color contrast
- [ ] Test screen readers

### Task 7.2: Performance Optimization
**Dauer:** 2 Tage

- [ ] Optimize images (WebP, lazy loading)
- [ ] Minimize JavaScript
- [ ] Add service worker (optional)
- [ ] Test Core Web Vitals

### Task 7.3: Cross-Browser Testing
**Dauer:** 1 Tag

- [ ] Test Chrome, Firefox, Safari, Edge
- [ ] Test mobile browsers
- [ ] Fix browser-specific issues

### Task 7.4: Documentation Update
**Dauer:** 1 Tag

- [ ] Update README.md
- [ ] Update CHANGELOG.txt
- [ ] Create DEVELOPER_GUIDE.md
- [ ] Create TEMPLATE_GUIDE.md

---

## Deployment Checklist

### Pre-Deploy
- [ ] All tests passing
- [ ] No console errors
- [ ] Accessibility checks done
- [ ] Performance metrics good
- [ ] Documentation updated

### Deploy
- [ ] Backup database
- [ ] Backup files
- [ ] Deploy new templates
- [ ] Clear cache
- [ ] Test live site

### Post-Deploy
- [ ] Monitor errors
- [ ] Check analytics
- [ ] Gather user feedback
- [ ] Fix critical bugs

---

## Success Metrics

### Technical
- ✅ All templates use standardized components
- ✅ Page load time < 2 seconds
- ✅ Lighthouse score > 90
- ✅ Zero accessibility errors
- ✅ Works on all target browsers

### User Experience
- ✅ Intuitive navigation
- ✅ Fast search results
- ✅ Easy image uploads
- ✅ Mobile-friendly
- ✅ Positive user feedback

---

## Notes

- **Commit after each task** - macht Rollbacks einfacher
- **Test auf verschiedenen Geräten** - Desktop, Tablet, Mobile
- **Dokumentiere Änderungen** - CHANGELOG.txt aktuell halten
- **Hole Feedback** - teste mit echten Usern
- **Bleib konsistent** - Design-System befolgen

---

**Next Steps:** Start with Phase 1, Task 1.1 - Audit Current Templates
