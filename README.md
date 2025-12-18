# Haushaltsetat-Planung – Backend

Backend-API für das Projekt „Haushaltsetat-Planung“.

Dieses Repository ist Teil einer End-to-End-Arbeitsprobe bestehend aus:
- React Native App (Datenerfassung): https://github.com/Twist1978/budget-app
- Laravel API + MySQL (dieses Repository)

---

## Inhalt

1. [Projektziele](#projektziele)
2. [Funktionen](#funktionen)
3. [Technischer Überblick](#technischer-überblick)
4. [Voraussetzungen](#voraussetzungen)
5. [Lokale Entwicklung](#lokale-entwicklung)
6. [Konfiguration (.env)](#konfiguration-env)
7. [Datenbank & Migrations](#datenbank--migrations)
8. [API-Übersicht](#api-übersicht)
9. [Tests](#tests)
10. [Deployment (Raspberry Pi)](#deployment-raspberry-pi)
11. [Roadmap](#roadmap)

---

## Projektziele

Dieses Backend soll:

- Ausgaben aus der Mobile-App entgegennehmen und in einer relationalen Datenbank speichern.
- Kategorien, Budgets und ggf. Konten/Wallets verwalten.
- Eine Basis für spätere KI-Funktionen bieten:
    - Beleg-Fotos empfangen
    - Belegtext via OCR extrahieren
    - Belegdaten via LLM interpretieren und automatisch Felder befüllen.

- Das Backend wird nicht nur als reine API, sondern später auch eine ansicht haben. Wie genau das aussehen wird steht noch nicht fest. Entweder als eine React anwendung, oder einfach nur eine Web-App.
    - Hier wird es dann auch ein Web-Admin (z. B. für Kategorien/Budgets) geben.

---

## Funktionen

### MVP (aktueller Stand / erste Version)

- Anlegen von Ausgaben (Betrag, Datum, Kategorie, Notiz, optional Foto-Referenz)
- Auslesen von Ausgaben (Liste, Detail)
- Kategorienverwaltung (CRUD)
- Einfache Authentifizierung (z. B. Token- oder Session-basiert)
- Felder von `Expense` (`id`, `vendor` `amount`, `date`, `category`, `debitAccount`)

### Geplante Funktionen

- Upload von Beleg-Fotos von der App zum Backend
- Anbindung eines LLM (z. B. OpenAI, lokales Modell) zur automatischen Belegerkennung
- Auswertungen/Reports (Monatsübersicht, Kategorie-Statistiken, Budget-Warnungen)
- KI-Services soll synchron (bei API-Call) laufen

---

## Technischer Überblick

- **Framework:** Laravel (aktuelle Version)
- **Programmiersprache:** PHP 8.3+
- **Datenbank:** MySQL
- **Auth:** z. B. Laravel Sanctum
- **Deployment-Ziel:** Raspberry Pi (Linux, Nginx oder Apache)

---

## Voraussetzungen

- PHP (mind 8.3+)
- Composer
- Datenbank (MySQL)
- Node.js & npm (für Laravel Mix/Vite, falls nötig)
- Git
- Jenkins auf dem entwicklungsrechner zum späteren deployment auf dem rasp.

Optional (für Raspberry Pi):

- Apache

---

## Lokale Entwicklung

### 1. Repository klonen

```
git clone <REPO-URL> budget-backend
cd budget-backend
```
### 2. Abhängigkeiten installieren

```
composer install
```

Für die Prod-Umgebung:
```
composer install --no-dev --optimize-autoloader
```

Falls Frontend-Assets oder Laravel Breeze/Jetstream genutzt werden:

```
npm install
npm run dev    # oder: npm run build
```

### 3. .env erstellen

```
cp .env.example .env
php artisan key:generate
```
Anschließend DB_*, APP_URL etc. in .env anpassen, siehe Konfiguration.

### 4. Migrations ausführen

```
php artisan migrate
```

Optional mit Seedern:

```
php artisan migrate --seed
```

### 5. Lokalen Server starten

```
php artisan serve
```

Standard: http://127.0.0.1:8000

## Konfiguration (.env)

Wichtige .env-Variablen:
```
APP_NAME="Budget Backend"
APP_ENV=local
APP_KEY=base64:<>
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=<DB_HOST>
DB_PORT=3306
DB_DATABASE=<DB_DATABASE>
DB_USERNAME=<DB_USERNAME>
DB_PASSWORD=<DB_PASSWORD>

# Auth / Tokens (Beispiel)
SANCTUM_STATEFUL_DOMAINS=localhost
SESSION_DOMAIN=localhost

# KI / OCR (geplant)
OCR_PROVIDER=
OCR_API_KEY=
LLM_PROVIDER=
LLM_API_KEY=
```

## Datenbank & Migrations

Geplante Kern-Tabellen (Beispiele):

- users – Nutzerkonten (optional, falls Multi-User)
- categories – Kategorien (z. B. Lebensmittel, Miete, Freizeit)
- expenses – Ausgaben (mit Foreign-Key auf categories und ggf. users)

Beispiel: Migration für expenses (vereinfachtes Schema):
```
Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->unsignedBigInteger('category_id')->nullable();
    $table->decimal('amount', 10, 2);
    $table->string('currency', 3)->default('EUR');
    $table->date('date');
    $table->timestamps();
});
```

## API-Enpoints
Die Mobile-App kommuniziert über eine REST-basierte JSON-API.

**Base URL:** `/api`

**Auth**
- Authorization: Bearer <token>

**Health**
- GET /health – Der Health-Endpunkt wird für Monitoring und einfache Deploy-Checks verwendet.

** API **
- GET /api/expenses – Liste aller Ausgaben
- POST /api/expenses – Neue Ausgabe anlegen
- GET /api/expenses/{id} – Details einer Ausgabe
- PUT /api/expenses/{id} – Ausgabe bearbeiten
- DELETE /api/expenses/{id} – Ausgabe löschen
- GET /api/categories – Liste der Kategorien
- POST /api/categories – Kategorie anlegen

Später:
- POST /api/receipts – Upload eines Belegs (Bilddatei)

### Scribe

Die Api wird durch Scribe dokumentiert.

Einmal für das Setup:
```
vendor:publish --tag=scribe-config
```

Zum Bauen der Dokumentation (auch bei jeder Änderung):
```
php artisan scribe:generate
```

## Tests
Laravel bietet Unterstützung für:
- Feature-Tests (HTTP-Endpunkte)
- Unit-Tests (Services, Models, Helper)

Beispiele:
```
php artisan test
```

oder gezielt:
```
php artisan test --filter=ExpenseTest
```

## Deployment (Raspberry Pi)

Geplanter grober Ablauf:
1. Code auf den Raspberry Pi deployen (git pull, rsync, o. Ä.).
2. Abhängigkeiten installieren:
   ```
   composer install --no-dev --optimize-autoloader
   ```
3. .env für Produktions-Setup anlegen (APP_ENV=production, APP_DEBUG=false, DB-Zugänge).
4. Migrations ausführen:
   ```
   php artisan migrate --force
   ```
5. Webserver konfigurieren:
   - Apache als Reverse Proxy zu php-fpm
   - APP_URL auf die Pi-URL setzen (z. B. http://budget-pi.local)
6. Optional: Queue-Worker einrichten (Supervisor / systemd) für KI-Jobs.

## Roadmap

### Kurzfristig:
- Basis-Migrations (expenses, categories, ggf. users)
- Basis-API für Ausgaben & Kategorien
- Verbindung zur Mobile-App herstellen (erste End-to-End-Tests)
- Authentifizierungsstrategie festlegen und implementieren

### Mittelfristig:
- Beleg-Upload-Endpoint implementieren
- LLM-Integration für Belegauswertung (z. B. via Queue-Job)

### Langfristig:
- Statistiken/Reports
- Budget-Alerts/Notifications
- Web-UI für Admin/Übersichten
