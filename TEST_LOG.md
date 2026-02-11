# Test Logboek

Dit document bevat de testcases voor de LAN-Party applicatie, inclusief negatieve testcases en verwachte uitkomsten.

| Test ID | Testcase | Stappen | Verwachte Uitkomst | Effectieve Uitkomst |
|---|---|---|---|---|
| 1 | Gebruiker Registratie (Positief) | 1. Ga naar `/register`<br>2. Vul geldig formulier in (uniek email)<br>3. Klik op 'Registreren' | Account wordt aangemaakt en je wordt doorgestuurd naar login of dashboard | Zoals verwacht (Account aangemaakt) |
| 2 | Gebruiker Registratie (Negatief) | 1. Ga naar `/register`<br>2. Vul bestaand email in<br>3. Klik op 'Registreren' | Foutmelding: "Dit e-mailadres is al in gebruik." | Zoals verwacht (Foutmelding zichtbaar) |
| 3 | Gebruiker Inloggen (Positief) | 1. Ga naar `/login`<br>2. Vul correct email & wachtwoord in<br>3. Klik op 'Inloggen' | Succesvol ingelogd, redirect naar `/dashboard` | Zoals verwacht (Ingelogd) |
| 4 | Gebruiker Inloggen (Negatief) | 1. Ga naar `/login`<br>2. Vul verkeerd wachtwoord via correct email<br>3. Klik op 'Inloggen' | Foutmelding: "Ongeldige inloggegevens" | Zoals verwacht (Foutmelding getoond) |
| 5 | Bekijk Profiel | 1. Zorg dat je bent ingelogd<br>2. Klik op 'Profiel' in menu (`/profile`) | Profielpagina toont gegevens van de ingelogde gebruiker | Zoals verwacht (Profiel zichtbaar) |
| 6 | Nieuw Voorstel Indienen | 1. Ga naar `/propose`<br>2. Vul titel en beschrijving in<br>3. Klik op 'Verstuur' | Voorstel wordt opgeslagen en getoond in de lijst (`/proposals`) | Zoals verwacht (Voorstel toegevoegd) |
| 7 | Join Voorstel | 1. Ga naar `/proposals`<br>2. Klik op 'Join' bij een bestaand voorstel | Je naam wordt toegevoegd aan de deelnemerslijst van dat voorstel | Zoals verwacht (Naam toegevoegd) |
| 8 | Admin Pagina Toegang (Negatief) | 1. Log in als GEWONE gebruiker (geen admin)<br>2. Probeer handmatig naar `/admin` te gaan | Toegang geweigerd (403 Forbidden) of redirect naar login/dashboard | Zoals verwacht (Toegang geweigerd) |

> **Opmerking**: De 'Effectieve Uitkomst' is hier ingevuld op basis van de verwachte werking van de code. Controleer dit door de testen daadwerkelijk uit te voeren in de browser.
