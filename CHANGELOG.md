# Changelog & Verdeling Verantwoordelijkheden

Dit document geeft een overzicht van de gerealiseerde functionaliteiten en de verdeling van het werk per groepslid.

## 👥 Gezamenlijke Verantwoordelijkheden
**Algemene Verbeteringen & Integratie**
*   Refactoring van de code voor een consistente stijl over alle pagina's heen.
*   Optimalisatie van de gebruikerservaring (UX) en interface (UI) consistentie.
*   Foutoplossing en integratie van de verschillende modules tot één werkend geheel.

---

## 👤 Yaman
**Rol:** Backend Architecture, Security & Core Features

### Uitgewerkte Onderdelen
1.  **Gebruikersauthenticatie (Login & Registratie)**
    *   Volledige implementatie van veilige registratie en inlogsystemen.
    *   Wachtwoord hashing en sessiebeheer.
    *   *Bestanden:* `AuthController.php`, `app/Views/auth/login.php`, `app/Views/auth/register.php`.

2.  **Resource Management (Armory/Rentals)**
    *   Ontwikkeling van het verhuursysteem voor hardware en resources.
    *   Implementatie van de logistieke beheerderskant voor rentals.
    *   *Bestanden:* `RentalController.php`, `app/Views/resources/*`.

3.  **Proposals Systeem**
    *   Realisatie van de volledige functionaliteit voor het indienen en beheren van voorstellen.
    *   *Bestanden:* `ProposeController.php`, `app/Views/proposals/*`.

4.  **Beveiliging**
    *   Implementatie van 'Slug Security' om directe ID-referenties in URL's te voorkomen en data veiliger op te halen.
    *   Beveiliging tegen SQL-injectie en XSS in de door hem beheerde controllers.

---

## 👤 Magaly
**Rol:** User Dashboard, Profile Management & Admin Administration

### Uitgewerkte Onderdelen
1.  **Gebruikers Dashboard**
    *   Ontwikkeling van het centrale dashboard waar gebruikers hun status en activiteiten kunnen zien.
    *   *Bestanden:* `UserDashboardController.php`, `app/Views/dashboard.php`.

2.  **Profielbeheer & Publieke User Pages**
    *   Realisatie van aanpasbare gebruikersprofielen.
    *   Ontwikkeling van publieke profielpagina's zodat gebruikers elkaar kunnen vinden.
    *   *Bestanden:* `ProfileController.php`, `app/Views/user/profile.php`.

3.  **Admin: LAN-Parties Management**
    *   Volledige beheerdersinterface voor het aanmaken en beheren van LAN-party evenementen.
    *   *Bestanden:* `AdminController.php` (LAN-specifieke methodes), `app/Views/admin/lans/*`.

4.  **Admin: Proposal Management**
    *   Implementatie van het beheerdersoverzicht voor ingediende voorstellen (goedkeuren/afwijzen).
    *   *Bestanden:* `AdminController.php` (Proposal-specifieke methodes).
