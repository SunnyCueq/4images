# 4images - Modern Image Gallery System

**Fork von [4images v1.10](https://www.4homepages.de)** - Modernisiert mit Tailwind CSS, PHP 8.4+ und responsivem Design

---

## Über diesen Fork

Dieses Projekt modernisiert die klassische 4images v1.10 Galerie mit moderner Web-Technologie, während das Original-Design 1:1 erhalten bleibt:

- **Tailwind CSS** statt veralteter Table-Layouts und Inline-Styles
- **Responsive Design** (Mobile-First) statt fixed-width
- **PHP 8.4+ kompatibel** (geplant)
- **Moderne HTML5-Struktur** (Flexbox/Grid statt Tables)
- **FontAwesome 7.0** Icons
- **CDN-basiert** - Keine Build-Tools nötig!

**Wichtig:** Visuell bleibt alles beim Original - nur der Code wird modernisiert!

---

## Installation

### Voraussetzungen
- **Webserver** (Apache/Nginx)
- **PHP 7.4+** (aktuell), **PHP 8.4+** (nach Task 1)
- **MySQL/MariaDB 5.7+**

### Quick Start

1. **Repository klonen:**
   ```bash
   git clone https://github.com/SunnyCueq/4images.git
   cd 4images
   ```

2. **Konfiguration:**
   - Kopiere `config.php.dist` → `config.php`
   - Passe Datenbank-Zugangsdaten an

3. **Installation:**
   - Führe die Installation über `/admin/` aus
   - Folge den Anweisungen in `docs/Installation.deutsch.txt`

4. **Fertig!** Das modernisierte Template ist bereits aktiv.

---

## Tech Stack

| Komponente | Technologie |
|-----------|-------------|
| **Frontend** | Tailwind CSS 3.x (Play CDN) |
| **Icons** | FontAwesome 7.0.0 Free |
| **Backend** | PHP 7.4+ (8.4+ geplant) |
| **Datenbank** | MySQL 5.7+ / MariaDB 10.3+ |
| **JavaScript** | Vanilla ES6+ |
| **Templates** | 4images Template-System (.html) |

**Keine Build-Tools nötig!** Alle Assets werden via CDN geladen.

---

## Design-Philosophie

**1:1 Modernisierung** bedeutet:
- Original-Layout beibehalten (Spalten, Struktur, Positionen)
- Original-Farben verwenden (#004C75 Blau, #fbbf24 Gelb modernisiert)
- Tables durch `<div>` + Grid/Flexbox ersetzen
- GIF-Borders durch CSS-Borders/Shadows ersetzen
- Responsive machen (max-w-6xl Container, Mobile-First)
- **KEIN** neues Design erfinden!

---

## Dokumentation

- **[docs/Installation.deutsch.txt](docs/Installation.deutsch.txt)** - Installationsanleitung (DE)
- **[docs/Installation.english.txt](docs/Installation.english.txt)** - Installation Guide (EN)
- **[docs/MODERNIZATION.deutsch.txt](docs/MODERNIZATION.deutsch.txt)** - Modernisierungs-Dokumentation (DE)
- **[docs/MODERNIZATION.english.txt](docs/MODERNIZATION.english.txt)** - Modernization Documentation (EN)
- **[docs/](docs/)** - Weitere Dokumentation (Lizenz, FAQ, etc.)

---

## Changelog

### [1.10.1] - 2025-01-XX (In Entwicklung)

**Frontend-Modernisierung (KOMPLETT):**
- Alle 38 Templates in `templates/default/` mit Tailwind CSS modernisiert
- Kompletter Ersatz aller Table-Layouts durch CSS Grid/Flexbox
- Responsive Design für alle Seiten (Mobile-First, max-w-6xl Container)
- FontAwesome 7.0 Icons statt veralteter GIF-Grafiken
- Moderne Form-Inputs mit Focus-States und Hover-Effekten

**Entfernte Features:**
- Postkarten-System entfernt (postcard_*.html)
- Keine GIF-Border-Grafiken mehr - alles CSS
- Docker-Support entfernt (für Webspace-Fokus)

**Templates modernisiert:**
- Hauptseiten: header, footer, home, details, categories, search, member, register, lightbox, top, error
- Member-Bereich: uploadform, profile, editimage, editcomment, deleteimage, deletecomment, editprofile, lostpassword, mailform (9 Templates)
- Bits/Widgets: comment_bit, thumbnail_bit, category_bit, exif_bit, iptc_bit, random_image, random_cat_image (7 Templates)
- Formulare: comment_form, rate_form, search_form, register_form, register_signup, user_loginform, user_logininfo (7 Templates)
- Utilities: bbcode, whos_online, category_dropdown_form, setperpage_dropdown_form (4 Templates)

**Technische Verbesserungen:**
- Code-Reduktion: 20-64% weniger Zeilen pro Template
- Konsistente 3/9 Grid-Layout-Struktur (Sidebar/Content)
- Tailwind CSS Play CDN Integration (keine Build-Tools)
- System Font Stack für schnelleres Laden
- Alternating row colors in Tabellen für bessere Lesbarkeit

**Dokumentation:**
- Umfangreiche Modernisierungs-Dokumentation (DE/EN) in docs/
- Installation Guides aktualisiert mit Tailwind CSS Anforderungen
- README.md überarbeitet

**Geplant für nächste Releases:**
- PHP 8.4+ Kompatibilität (PDO Migration, Deprecations)
- Admin-Bereich Modernisierung
- JavaScript Konsolidierung in externe Dateien

### [1.10.0] - Original
- Basis: 4images v1.10.0 von 4homepages.de

Vollständiger Changelog: [docs/CHANGELOG.txt](docs/CHANGELOG.txt)

---

## Lizenz

Bitte beachten Sie die Lizenzbedingungen in `docs/Lizenz.deutsch.txt` bzw. `docs/Licence.english.txt`.

**Wichtig:**
- Für **private Nutzung** ist 4images kostenlos
- Für **kommerzielle/nicht-private Nutzung** oder zum Entfernen des Copyright-Vermerks benötigen Sie eine Lizenz: https://www.4homepages.de/download-4images/

---

## Original 4images Links

- **Download:** https://www.4homepages.de
- **Support Forum:** https://www.4homepages.de/forum
- **FAQ:** https://www.4homepages.de/forum/index.php?board=14.0

---

## Contributing

Contributions sind willkommen! Bitte beachte:

1. Fork das Projekt
2. Erstelle einen Feature-Branch (`git checkout -b feature/NeuesFeature`)
3. Commit deine Änderungen (`git commit -m 'Feature: Beschreibung'`)
4. Push zum Branch (`git push origin feature/NeuesFeature`)
5. Öffne einen Pull Request

**Wichtig:** Alle Änderungen sollten die 1:1 Modernisierungs-Philosophie respektieren.

---

Entwickelt unter Beibehaltung des klassischen 4images Designs
