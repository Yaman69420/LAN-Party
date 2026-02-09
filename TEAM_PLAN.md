# TEAM_PLAN.md - LAN-Party Groepswerk

## Gekozen Domein
**LAN-Party Beheer & Resource Systeem**

Een platform waar gebruikers LAN-parties kunnen aanvragen (Propos LAN), materiaal kunnen reserveren (The Armory) en hun planning kunnen beheren. Admins beheren de gebruikers, aanvragen en resources.

---

## Entiteiten & Database Structuur

### 1. Users
| Kolom | Type | Beschrijving |
|-------|------|--------------|
| id | INT PK | Unieke ID |
| username | VARCHAR | Gebruikersnaam |
| email | VARCHAR | Emailadres (Uniek) |
| password | VARCHAR | Gehashte wachtwoord |
| role | ENUM | 'user' of 'admin' |
| is_active | BOOLEAN | Voor block/unblock (default 1) |
| bio | TEXT | Profiel beschrijving (optioneel) |
| avatar | VARCHAR | Pad naar profielfoto (optioneel) |

### 2. Parties (LAN Events)
| Kolom | Type | Beschrijving |
|-------|------|--------------|
| id | INT PK | Unieke ID |
| name | VARCHAR | Naam van de LAN |
| description | TEXT | Beschrijving |
| start_date | DATETIME | Start moment |
| end_date | DATETIME | Eind moment |
| max_participants | INT | Verwacht aantal deelnemers |
| status | ENUM | 'pending', 'approved', 'cancelled' |
| organizer_id | INT FK | User die het aanvraagt |

### 3. Resources (The Armory)
| Kolom | Type | Beschrijving |
|-------|------|--------------|
| id | INT PK | Unieke ID |
| name | VARCHAR | Naam (bv. "Samsung 24inch") |
| type | VARCHAR | Categorie (bv. "monitor", "kabel") |
| description | TEXT | Info over het item |
| is_deleted | BOOLEAN | Soft-delete status (default 0) |

### 4. Reservations (Leningen)
| Kolom | Type | Beschrijving |
|-------|------|--------------|
| id | INT PK | Unieke ID |
| user_id | INT FK | Wie leent het? |
| resource_id | INT FK | Wat wordt geleend? |
| start_time | DATETIME | Begin reservatie |
| end_time | DATETIME | Eind reservatie |
| status | ENUM | 'active', 'returned', 'cancelled' |

---

## Taakverdeling (Voorbeeld)

| Feature / Component | Verantwoordelijke |
|---------------------|-------------------|
| **Epic 1: Fundament** | |
| - Setup & Auth (Login/Register) | [Naam] |
| - Database helpers & Validatie | [Naam] |
| **Epic 2: Frontend** | |
| - Dashboard & Profile | [Naam] |
| - Propos LAN (Party forms) | [Naam] |
| - The Armory (Resource Grid) | [Naam] |
| **Epic 3: Admin** | |
| - User Management | [Naam] |
| - Party Approvals | [Naam] |
| - Resource CRUD | [Naam] |

---

## Afspraken

### Git Workflow
1. **Branching**: `main` (clean) <- `dev` (integration) <- `feature/naam`
2. **Commit style**: `[Epic] korte beschrijving` (bv. `[Auth] add login controller`)
3. **Pull Requests**: Code review voor merge naar `dev`

### Coding Standards
- Strict types: `declare(strict_types=1);` bovenaan elk bestand.
- Engels voor code (variabelen, functies), Nederlands mag voor tekst/content.
- **Validatie**: Altijd server-side checks op input.
- **Views**: Geen queries in views! Gebruik variabelen vanuit de Controller.
