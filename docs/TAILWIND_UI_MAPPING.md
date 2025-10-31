# Tailwind UI Component Mapping für 4images

**Welche Tailwind UI Blocks verwenden wir für welche 4images Komponenten?**

---

## Navigation Components

### 1. Primary Navbar (header.html)

**Tailwind UI Block:** [Application UI → Navigation → Navbars](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/navbars)

**Empfehlung:** **"With search in column layout"** oder **"With search"**

**Warum?**
- Integrierte Suchfunktion (wichtig für Bildergalerie!)
- Unterstützt Dropdown-Menüs für Kategorien
- Responsive mit Mobile Menu
- Professionelles, modernes Design

**Features die wir brauchen:**
- Logo links
- Navigation Links (Home, Kategorien-Dropdown, Top Bilder)
- Suchleiste (Desktop: rechts, Mobile: ausklappbar)
- User Menu (Login/Profile mit Dropdown)
- Sticky on scroll
- Mobile Menu Button

**4images Anpassungen:**
- Kategorien-Dropdown mit 3 Ebenen (Kategorie → Unterkategorie → Unter-Unterkategorie)
- Eigene Brand-Farben (#004C75 statt Tailwind Default)

---

### 2. Mobile Menu

**Tailwind UI Block:** Integriert in Navbar-Komponente

**Features:**
- Slide-down Animation
- Icons für jeden Menu-Punkt
- Collapsible Category Tree
- User Section am Ende

**Icons:**
- 🏠 Home
- 📁 Kategorien (mit Expand/Collapse)
- 🔍 Suche
- 🏆 Top Bilder
- 👤 Login/Profil

---

### 3. Breadcrumbs

**Tailwind UI Block:** [Application UI → Navigation → Breadcrumbs](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/breadcrumbs)

**Empfehlung:** **"Simple with slashes"** (Light Variante)

**Warum?**
- Minimalistisch und clean
- Slashes als Trenner (modern)
- Leicht verständlich
- Nimmt wenig Platz ein

**Beispiel:**
```
🏠 / Kategorien / Natur / Landschaften / Berge
```

**4images Anpassungen:**
- Home Icon statt Text
- Letzte Seite (aktuell) nicht anklickbar, fett
- Brand-Blue für Links

---

## Layout Components

### 4. Main Container (alle Seiten)

**Tailwind UI Block:** [Application UI → Page Examples → Home Screens](https://tailwindcss.com/plus/ui-blocks/application-ui/page-examples/home-screens)

**Empfehlung:** **"Sidebar Layout"**

**Layout-Struktur:**
```
┌──────────────────────────────────────┐
│ Navbar (sticky)                      │
├──────────────────────────────────────┤
│ Container (max-w-7xl)                │
│ ┌────────┬───────────────────────┐  │
│ │Sidebar │ Main Content          │  │
│ │(3 col) │ (9 col)               │  │
│ │        │                       │  │
│ │ User   │ Breadcrumbs           │  │
│ │ Random │ Page Content          │  │
│ │ Online │                       │  │
│ └────────┴───────────────────────┘  │
├──────────────────────────────────────┤
│ Footer                               │
└──────────────────────────────────────┘
```

**Responsive:**
- Mobile (<768px): Sidebar UNTER Content (Full Width)
- Tablet (768-1024px): Sidebar links (4 Spalten), Content (8 Spalten)
- Desktop (>1024px): Sidebar links (3 Spalten), Content (9 Spalten)

---

### 5. Cards (Sidebar Widgets)

**Tailwind UI Block:** [Application UI → Layout → Cards](https://tailwindcss.com/plus/ui-blocks/application-ui/layout/cards)

**Empfehlung:** **"Card with header"** für alle Sidebar Widgets

**Struktur:**
```html
<div class="bg-white rounded-lg shadow-md overflow-hidden">
  <!-- Header (STANDARDISIERT!) -->
  <div class="bg-brand-blue text-white px-6 py-4 flex items-center gap-2">
    <i class="fa-solid fa-icon text-lg"></i>
    <h3 class="text-lg font-semibold">Widget Titel</h3>
  </div>

  <!-- Content -->
  <div class="p-6">
    <!-- Widget Content -->
  </div>
</div>
```

**Verwendung:**
- User Login/Info Box
- Random Image Widget
- Who's Online Widget
- Category Filter (wenn sidebar)

---

## Image Components

### 6. Image Grid (Thumbnail Gallery)

**Tailwind UI Block:** [Application UI → Lists → Grid Lists](https://tailwindcss.com/plus/ui-blocks/application-ui/lists/grid-lists)

**Empfehlung:** **"Images with details"**

**Warum?**
- Speziell für Bilder mit Zusatzinfos designed
- Hover-Effekte eingebaut
- Responsive Grid
- Aspect Ratio Support

**Features:**
- aspect-square Container
- Hover Overlay mit Actions
- Image Stats (Views, Likes, Comments)
- User Info
- Image Title

**Grid Columns:**
- Mobile: 2 Spalten
- Tablet: 3 Spalten
- Desktop: 4 Spalten

**Code-Struktur:**
```html
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
  <!-- Thumbnail Card -->
  <a href="/details?id=123" class="group">
    <div class="aspect-square overflow-hidden rounded-lg bg-gray-100 relative">
      <img
        src="thumbnail.jpg"
        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
        loading="lazy"
      >
      <!-- Hover Overlay -->
      <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition">
        <!-- Quick Actions (Favorite, Share) -->
      </div>
    </div>

    <!-- Image Info -->
    <div class="mt-2">
      <h4 class="font-semibold text-gray-900 line-clamp-1">Titel</h4>
      <p class="text-sm text-gray-600">von Username</p>
      <div class="flex items-center gap-2 text-xs text-gray-500">
        <span>👁️ 1,234</span>
        <span>❤️ 56</span>
        <span>💬 12</span>
      </div>
    </div>
  </a>
</div>
```

---

### 7. Image Detail View

**Tailwind UI Block:** Custom (keine direkte Entsprechung)

**Inspiriert von:** E-Commerce Product Detail Pages

**Layout:**
```
┌─────────────────────────────────────────┐
│ ┌────────────────┬──────────────────┐  │
│ │ Image (60%)    │ Info Sidebar     │  │
│ │                │ (40%)            │  │
│ │ - Main Image   │ - Title          │  │
│ │ - Lightbox     │ - User           │  │
│ │                │ - Upload Date    │  │
│ │                │ - Stats          │  │
│ │                │ - Description    │  │
│ │                │ - Tags           │  │
│ │                │ - Actions        │  │
│ │                │   (Like, Share,  │  │
│ │                │    Download)     │  │
│ └────────────────┴──────────────────┘  │
└─────────────────────────────────────────┘
```

---

## Form Components

### 8. Login Form (user_loginform.html)

**Tailwind UI Block:** [Application UI → Forms → Form Layouts](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/form-layouts)

**Empfehlung:** **"Stacked"**

**Warum?**
- Kompakt und fokussiert
- Tailwind empfiehlt "Stacked" explizit für Login/Registrierung
- Einfach auf Mobile
- Schnell ausfüllbar

**Features:**
- Username/Email Input
- Password Input
- "Remember me" Checkbox
- Login Button (Primary)
- Links zu "Passwort vergessen" und "Registrieren"

---

### 9. Upload Form (member_uploadform.html)

**Tailwind UI Block:** [Application UI → Forms → Form Layouts](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/form-layouts)

**Empfehlung:** **"Two-column"** oder **"Two-column with cards"**

**Warum?**
- Upload-Forms sind komplex (viele Felder)
- 2-Spalten spart Platz
- Logische Gruppierung (Media links, Info rechts)

**Layout:**
```
┌────────────────────────────────────┐
│ Card Header: Upload Bild           │
├──────────────┬─────────────────────┤
│ Media File   │ Image Name          │
│ Thumb File   │ Description         │
│              │ Keywords            │
│              │ Category            │
├──────────────┴─────────────────────┤
│ CAPTCHA (full width)               │
├────────────────────────────────────┤
│ [Submit] [Reset]                   │
└────────────────────────────────────┘
```

---

### 10. Comment Form (comment_form.html)

**Tailwind UI Block:** [Application UI → Forms → Form Layouts](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/form-layouts)

**Empfehlung:** **"Stacked"**

**Warum?**
- Einfach und schnell
- Fokus auf Text-Eingabe
- Wenige Felder
- BBCode Toolbar darüber

---

### 11. Search Form (search_form.html)

**Tailwind UI Block:** [Application UI → Forms → Form Layouts](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/form-layouts)

**Empfehlung:** **"Stacked"** mit Collapsible Advanced Filters

**Features:**
- Keywords Input (groß, prominent)
- "New Images Only" Checkbox
- Advanced Filters (ausklappbar):
  - User Filter
  - Category Dropdown
  - Date Range
  - Search Fields (Name/Description/Keywords)

---

## Marketing Components

### 12. Footer

**Tailwind UI Block:** [Marketing → Sections → Footers](https://tailwindcss.com/plus/ui-blocks/marketing/sections/footers)

**Empfehlung:** **"4-column with company mission"** (Dark Variante)

**Warum?**
- Professionell
- Platz für Mission Statement
- 4 Spalten für verschiedene Link-Gruppen
- Social Media Integration
- Bottom Bar für Copyright

**Layout:**
```
┌───────────────────────────────────────────┐
│ [Mission (2 cols)]  [Nav]  [Account]      │
│                                           │
│ 4images Gallery     - Home  - Login       │
│ Moderne Galerie...  - Suche - Registrieren│
│                     - Top   - Profil      │
│ [Social Icons]                            │
├───────────────────────────────────────────┤
│ © 2025 | Datenschutz | AGB | Kontakt     │
└───────────────────────────────────────────┘
```

**Farben:**
- Background: `bg-gray-900`
- Text: `text-white` / `text-gray-400`
- Links Hover: `hover:text-white`
- Social Icons: Brand-Yellow on Hover

---

## Buttons & Elements

### 13. Buttons (Standardized!)

**Tailwind UI Block:** [Application UI → Elements → Buttons](https://tailwindcss.com/plus/ui-blocks/application-ui/elements/buttons)

**Primary Button:**
```html
<button class="px-6 py-2.5 bg-brand-blue hover:bg-brand-blue-dark text-white font-semibold rounded-lg transition inline-flex items-center gap-2">
  <i class="fa-solid fa-icon"></i>
  Button Text
</button>
```

**Secondary Button:**
```html
<button class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold rounded-lg transition">
  Cancel
</button>
```

**Danger Button:**
```html
<button class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition inline-flex items-center gap-2">
  <i class="fa-solid fa-trash"></i>
  Delete
</button>
```

---

### 14. Input Fields (Standardized!)

**Tailwind UI Block:** [Application UI → Forms → Input Groups](https://tailwindcss.com/plus/ui-blocks/application-ui/forms/input-groups)

**Text Input:**
```html
<div>
  <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
  <input
    type="text"
    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent"
  >
</div>
```

**Textarea:**
```html
<textarea
  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent resize-y"
  rows="5"
></textarea>
```

**Select:**
```html
<select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent">
  <option>Option 1</option>
</select>
```

---

## Special Components

### 15. Notifications/Alerts

**Tailwind UI Block:** [Application UI → Feedback → Notifications](https://tailwindcss.com/plus/ui-blocks/application-ui/feedback/notifications)

**Verwendung:**
- Erfolgs-Meldungen (Upload erfolgreich)
- Fehler-Meldungen (Login fehlgeschlagen)
- Info-Meldungen (Neue Bilder verfügbar)

**Types:**
- Success (green)
- Error (red)
- Warning (yellow)
- Info (blue)

---

### 16. Modal Dialogs

**Tailwind UI Block:** [Application UI → Overlays → Modals](https://tailwindcss.com/plus/ui-blocks/application-ui/overlays/modals)

**Verwendung:**
- Lightbox für Bilder
- Confirm Dialogs (Löschen bestätigen)
- Image Upload Preview
- Login Modal (optional)

---

### 17. Pagination

**Tailwind UI Block:** [Application UI → Navigation → Pagination](https://tailwindcss.com/plus/ui-blocks/application-ui/navigation/pagination)

**Verwendung:**
- Category Image List
- Search Results
- User Image Gallery

**Features:**
- Prev/Next Buttons
- Page Numbers (1 2 3 ... 10)
- "Showing X of Y results"

---

## Summary: Component Priority

### Phase 1 (Must Have - Woche 1-2)
1. ✅ **Navbar** - "With search in column layout"
2. ✅ **Breadcrumbs** - "Simple with slashes"
3. ✅ **Cards** - "Card with header" (für Sidebar)
4. ✅ **Buttons** - Standardized (Primary, Secondary, Danger)
5. ✅ **Input Fields** - Standardized (Text, Textarea, Select)

### Phase 2 (Important - Woche 3-4)
6. ✅ **Image Grid** - "Images with details"
7. ✅ **Footer** - "4-column with company mission" (Dark)
8. ✅ **Form Layouts** - Stacked & Two-column
9. ✅ **Mobile Menu** - Integrated in Navbar
10. ✅ **Pagination** - Simple with Prev/Next

### Phase 3 (Nice to Have - Woche 5-6)
11. ✅ **Modals** - Lightbox & Confirms
12. ✅ **Notifications** - Toast-Style
13. ✅ **Image Detail** - Custom Layout
14. ✅ **Advanced Search** - Collapsible Filters

---

## Access Note

**Wichtig:** Die meisten Tailwind UI Blocks sind **kostenpflichtig** (Tailwind UI Plus).

**Alternativen:**
1. **Gratis Tailwind Components:** https://tailwindcomponents.com/
2. **DaisyUI:** https://daisyui.com/ (Tailwind Plugin mit Components)
3. **Flowbite:** https://flowbite.com/ (Open Source Tailwind Components)
4. **Headless UI:** https://headlessui.com/ (von Tailwind Team, kostenlos)

**Oder:** Wir bauen die Components selbst nach den **Patterns** die wir gesehen haben!

---

## Next Steps

1. **Start Phase 1:** Navbar + Breadcrumbs + Card Standardization
2. **Use Tailwind Docs:** https://tailwindcss.com/docs als Referenz
3. **Check Flowbite:** https://flowbite.com/docs/components/navbar/ für kostenlose Component-Inspiration
4. **Build Custom:** Eigene Components basierend auf Tailwind Utility Classes

**Bereit zum Start?** 🚀
