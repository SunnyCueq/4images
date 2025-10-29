# /plan redesign

## Ziel:
Die bestehende Website basiert auf einem sehr alten, tabellenbasierten Layout (ca. 2002) und soll vollständig in ein modernes, responsives HTML5/TailwindCSS-Design überführt werden.
Dabei soll die inhaltliche Struktur erhalten bleiben, das visuelle und technische Konzept jedoch komplett modernisiert werden.
Der Fokus liegt auf einer sauberen UI/UX-Architektur und einem professionellen, wiederverwendbaren Layoutgerüst.

## Kontext:
- Die Website nutzt bereits **Tailwind CSS Play CDN** (konfiguriert in header.html)
- **home.html** wurde bereits von Tables zu Flexbox modernisiert (als Referenz)
- Alle anderen Templates (details.html, categories.html, search.html, etc.) warten noch auf Modernisierung
- Das 4images Template-System verwendet Variablen wie `{header}`, `{footer}`, `{categories}`, `{user_box}`, etc.

## Bestehende Farbpalette:
```
Primary Blue: #004C75 (brand-blue)
Light Blue: #0F5475 (brand-blue-light)
Yellow (Navbar): #fcdc43 (brand-yellow)
Gray Light: #efefef (brand-gray-light)
Gray: #e8e8e8 (brand-gray)
Gray Dark: #e1e1e1 (brand-gray-dark)
Border: #004C75 (brand-border)
```
Diese Farben können modernisiert/angepasst werden, wenn es das Design verbessert.

## Rolle:
Handle aus der Perspektive eines erfahrenen UI/UX-Designers und Frontend-Architekten.
Ziel ist es, ein durchdachtes, modulares Designsystem zu planen, das mit TailwindCSS aufgebaut wird
und eine konsistente Nutzererfahrung über alle Seitentypen hinweg bietet.

## Gesamtziel:
Erstelle einen vollständigen **Redesign-Plan** für die gesamte Website – beginnend mit der Startseite,
aber mit einem klaren Framework, das auch auf Unterseiten (Detailseiten, Kategorien, Suche etc.) angewendet werden kann.

## Grundsätze:
- Komplett weg vom Tabellenlayout → moderne semantische HTML-Struktur mit Flexbox oder Grid
- Responsives, modulares Design (Mobile First)
- Aufbau mit TailwindCSS Utility-Klassen und Komponenten-Patterns
- Keine Inline-Styles oder externe CSS-Dateien (außer style.css für 4images-Kompatibilität)
- Trennung von Layout, Content und Funktionalität

## Layoutstruktur (Gesamtgerüst):

### Header:
- Enthält Branding/Logo, Navigation, Suchfunktion
- Navigation mit Dropdowns (Haupt-, Unter-, Unter-Unterkategorien)
- Responsiv (Hamburger-Menü auf mobilen Geräten)
- Integration: `{site_name}`, `{url_search}`, `{lang_search}`, Navigation-Links

### Main Content (Basisstruktur):
- Flexible Container für Seiteninhalte (Startseite, Kategorien, Detailseiten)
- Einheitliches Spacing, Typografie und Farbkonzept
- Integration von dynamischen Daten und bestehenden Template-Variablen
- Beispiele: `{categories}`, `{new_images}`, `{random_image}`, `{whos_online}`, `{clickstream}`

### Sidebar (optional, je nach Seitentyp):
- User-Info-Box: `{user_box}`, `{lang_registered_user}`
- Random Image: `{random_image}`, `{lang_random_image}`
- Flexible Breite (z.B. 200-250px auf Desktop, full-width auf Mobile)

### Footer:
- Einheitlicher Footer mit Kontakt, Impressum, Social Media, optionaler Sitemap
- Integration: `{footer}` Template-Variable

## Designsystem & UI/UX-Leitlinien:
- Modernes, cleanes, helles Design (2025-Stil)
- Klare Typografie-Hierarchie (Überschriften, Text, Meta)
- Konsistente Farbpalette (freundlich, neutral, barrierefrei)
- Großzügige Abstände und klare visuelle Trennung der Bereiche
- Wiederverwendbare Komponenten (Header, Footer, Cards, Listen, Buttons, Navigation)
- Orientierung an bewährten Tailwind-Designmustern (z. B. aus Tailwind UI oder modernen Open-Source-Vorlagen)

## Datenintegration:
Bestehende Template-Variablen aus dem 4images-System:

**Navigation & Meta:**
- `{header}`, `{footer}`, `{site_name}`, `{clickstream}`
- `{url_search}`, `{url_top_images}`, `{url_new_images}`
- `{lang_*}` (Sprachvariablen für alle Texte)

**Content-Bereiche:**
- `{categories}`, `{new_images}`, `{user_box}`, `{random_image}`
- `{whos_online}`, `{category_dropdown_form}`, `{setperpage_dropdown_form}`
- `{lang_site_stats}`, `{msg}` (Fehlermeldungen)

**Detailseiten (später):**
- `{image}`, `{image_name}`, `{image_description}`, `{comments}`
- `{rate_form}`, `{download_button}`, `{lightbox_button}`

Diese Variablen sollen logisch in die neue Struktur integriert werden.

## Ergebnis:
Erstelle einen vollständigen **UI/UX- und Layout-Plan**, der das gesamte Designsystem beschreibt:

1. **Struktur und Aufbau der Website**
   - Generelles Layout-Gerüst (Container, Spacing, Breakpoints)
   - Grid-System und Responsive-Regeln

2. **Modularer Aufbau von Komponenten**
   - Header (Logo, Nav, Search)
   - Footer (Links, Copyright)
   - Content-Bereiche (Cards, Listen, Grids)
   - Sidebar (User-Box, Random Image)

3. **Designprinzipien**
   - Farbpalette (modernisiert oder bestehend)
   - Typografie (Schriftgrößen, Hierarchie)
   - Spacing-System (Abstände, Padding, Margins)
   - Schatten, Borders, Radius

4. **Komponentenstruktur**
   - Navigation (Desktop/Mobile)
   - Karten/Cards (Bilder, Kategorien)
   - Listen (Thumbnails, Text)
   - Formulare (Suche, Upload, Kommentare)
   - Buttons (Primary, Secondary, Icon-Buttons)

5. **Responsive Regeln**
   - Mobile: bis 640px
   - Tablet: 641px - 1024px
   - Desktop: 1025px+

6. **Empfehlungen für die Umsetzung mit TailwindCSS**
   - Welche Utility-Klassen für welche Komponenten
   - Wiederverwendbare Patterns
   - Custom Config (falls nötig)

Dieser Plan dient als Grundlage für die spätere Umsetzung (`/code`), bei der jede Seite
auf Basis dieses durchdachten Frameworks erstellt wird.
