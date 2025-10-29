# 4images - Modern Image Gallery Management System

**Fork von [4images v1.10](https://www.4homepages.de)** - Modernisiert mit Tailwind CSS, PHP 8.4+ und responsivem Design

---

## 🎯 Über diesen Fork

Dieses Projekt modernisiert die klassische 4images v1.10 Galerie mit moderner Web-Technologie, während das Original-Design 1:1 erhalten bleibt:

- ✅ **Tailwind CSS** statt veralteter Table-Layouts und Inline-Styles
- ✅ **Responsive Design** (Mobile-First) statt fixed-width
- ✅ **PHP 8.4+ kompatibel** (geplant)
- ✅ **Moderne HTML5-Struktur** (Flexbox/Grid statt Tables)
- ✅ **FontAwesome 7.0** Icons
- ✅ **CDN-basiert** - Keine Build-Tools nötig!

**Wichtig:** Visuell bleibt alles beim Original - nur der Code wird modernisiert!

---

## 🚀 Was ist neu?

### ✅ Bereits modernisiert (Task 2 - Teilweise):
- **[header.html](templates/default/header.html)** - Sticky Header, responsive Navigation, Hamburger-Menu
- **[footer.html](templates/default/footer.html)** - 3-Spalten Grid-Layout, moderne Struktur
- **[home.html](templates/default/home.html)** - Responsive Grid (Sidebar + Content), Cards mit Shadows

### 🔜 In Arbeit:
- details.html, categories.html, search.html (weitere ~40 Templates)
- PHP 8.4+ Kompatibilität (Task 1)
- Admin-Bereich Modernisierung (Task 3)

---

## 📦 Installation

### Voraussetzungen
- **Webserver** (Apache/Nginx)
- **PHP 7.4+** (aktuell), **PHP 8.4+** (nach Task 1)
- **MySQL/MariaDB**

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

## 🛠️ Tech Stack

| Komponente | Technologie |
|-----------|-------------|
| **Frontend** | Tailwind CSS 3.x (Play CDN) |
| **Icons** | FontAwesome 7.0.0 Free |
| **Backend** | PHP 8.4+ (geplant) |
| **Datenbank** | MySQL 5.7+ / MariaDB 10.3+ |
| **JavaScript** | Vanilla ES6+ |
| **Templates** | 4images Template-System (.html) |

**Keine Build-Tools nötig!** Alle Assets werden via CDN geladen.

---

## 📚 Dokumentation

- **[CLAUDE.md](CLAUDE.md)** - Entwicklungs-Richtlinien und Modernisierungs-Philosophie
- **[docs/](docs/)** - Original 4images Dokumentation (Lizenz, Installation, etc.)

---

## 🎨 Design-Philosophie

**1:1 Modernisierung** bedeutet:
- ✅ Original-Layout beibehalten (Spalten, Struktur, Positionen)
- ✅ Original-Farben verwenden (#004C75 Blau, #fbbf24 Gelb modernisiert)
- ✅ Tables durch `<div>` + Grid/Flexbox ersetzen
- ✅ GIF-Borders durch CSS-Borders/Shadows ersetzen
- ✅ Responsive machen (max-w-6xl Container, Mobile-First)
- ❌ **KEIN** neues Design erfinden!

---

## 📄 Lizenz

Bitte beachten Sie die Lizenzbedingungen in `docs/Lizenz.deutsch.txt` bzw. `docs/Licence.english.txt`.

**Wichtig:**
- Für **private Nutzung** ist 4images kostenlos
- Für **kommerzielle/nicht-private Nutzung** oder zum Entfernen des Copyright-Vermerks benötigen Sie eine Lizenz: https://www.4homepages.de/download-4images/

---

## 🔗 Original 4images Links

- **Download:** https://www.4homepages.de
- **Support Forum:** https://www.4homepages.de/forum
- **FAQ:** https://www.4homepages.de/forum/index.php?board=14.0

---

## 🤝 Contributing

Dieses Projekt ist ein privater Fork zur Modernisierung. Contributions sind willkommen:

1. Fork das Projekt
2. Erstelle einen Feature-Branch (`git checkout -b feature/AmazingFeature`)
3. Commit deine Änderungen (`git commit -m 'Add some AmazingFeature'`)
4. Push zum Branch (`git push origin feature/AmazingFeature`)
5. Öffne einen Pull Request

**Bitte beachte:** Alle Änderungen müssen die 1:1 Modernisierungs-Philosophie respektieren (siehe CLAUDE.md).

---

## 📝 Changelog

### [Unreleased]
- ✅ Header, Footer, Home modernisiert mit Tailwind CSS
- ✅ Responsive Design implementiert (Mobile-First)
- ✅ Repository aufgeräumt (Dev-Dateien entfernt)

### [v1.10.0] - Original
- Basis: 4images v1.10.0 von 4homepages.de

---

**Entwickelt mit ❤️ unter Beibehaltung des klassischen 4images Designs**
