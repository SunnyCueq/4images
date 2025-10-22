# CLAUDE.md - 3videos Projekt

## TECH STACK
- PHP 8.4+ 

## REIHENFOLGE (STRICT BEFOLGEN)
**Task 1 → Task 2 → Task 3 → Task 4 → Task 5 → Task 6**
- **NICHT mischen!** Erst Task 1 fertig, dann Task 2
- **Status zeigen:** "Task 1: 80% → Task 2 starten?"

## WAS ZU MACHEN IST
- Es sollen keine unnötigen Dateien dem Script zugefügt werden!

### Task 1: PHP 8.4+ kompatibel machen
- **KEIN declare(strict_types=1)** (85% Welt-Standard)
- **Deprecated Functions entfernen, korrigieren oder ersetzen** (mysql_* → PDO)
- `match` statt `switch` wo sinnvoll
- `readonly` Properties wo sinnvoll

### Task 2: Funktionen modernisieren (Legacy-Code)
- Legacy-Funktionen in Klassen umwandeln
- Klassen nutzen wenn möglich und integrieren das sie genutzt werden
- Funktionen bündeln wenn sinnvoll oder Klassen nutzen
- Code-Struktur verbessern, verständlich und Legacy ähnlich bleiben
- Passwort-Sicherheit kontrollieren

### Task 3
- Verweise aktualisieren
- Konsistenz sicherstellen

### Task 4: Template-System modernisieren (Smarty-ähnlich, KEIN externes Smarty)
- **WEBSPACE-KOMPATIBEL**: Eigenes Template-System modernisieren (KEIN Composer/externe Abhängigkeiten)
- **Smarty-ähnliche Syntax**: {$variable}, {if}, {foreach} etc.
- **Performance**: caching system modernisieren (und eventuell Template-Caching)
- **Backward Compatible**: Alte Syntax {VARIABLE} weiterhin unterstützen

## CODING RULES

### BACKUP & SICHERHEIT
- **BACKUP:** Vor großen Changes: `git commit -m "backup-[task]-[date]"`
- Immer Backup vor kritischen Änderungen

### CODE-QUALITÄT
- Bevorzuge immer einfache Lösungen
- Vermeide wann immer möglich die Duplizierung von Code – prüfe ob ähnliche Funktionalitäten existieren
- Sei sorgfältig und nimm nur Änderungen vor, die angefordert wurden oder gut verstanden sind
- Erstelle nicht unbedacht neue Dateien

### FEHLERBEHEBUNG
- Bei Fehlern: Schöpfe bestehende Implementierung aus, bevor neue Technologien eingeführt werden
- Wenn neue Technik: Alte Implementierung danach entfernen (keine doppelte Logik)

### KOMMENTIERUNG
- Kommentiere wo es Sinn macht (nicht übertreiben):
  - **PHP:** PHPDoc für Klassen/Methoden (@param, @return, @throws)
  - **TypeScript:** JSDoc für wichtige Functions/Interfaces
  - **.tpl:** Smarty-Kommentare {* ... *} für komplexe Logik

### ORGANISATION
- Halte Code sauber und gut organisiert
- Vermeide Skripte in Dateien, wenn sie nur einmal ausgeführt werden
