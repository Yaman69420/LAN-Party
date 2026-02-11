# Architectuur Documentatie

## 1. Uitleg van de MVC-opzet

In dit project wordt gebruik gemaakt van het **Model-View-Controller (MVC)** ontwerppatroon. Dit patroon scheidt de applicatie in drie logische componenten om de code overzichtelijk, onderhoudbaar en schaalbaar te houden.

De flow van een verzoek (request) in deze applicatie verloopt als volgt:

1.  **Entry Point (`public/index.php`)**: Elk verzoek komt binnen via `index.php`. Hier wordt de sessie gestart, de autoloader geladen en de `Router` geïnitialiseerd.
2.  **Routing (`app/Core/Router.php`)**: De router analyseert de URL en bepaalt welke **Controller** en methode verantwoordelijk zijn voor het verzoek. De routes zijn gedefinieerd in `app/routes.php`.
3.  **Controller (`app/Controllers/*`)**: De controller ontvangt het verzoek. Hij haalt indien nodig data op via een **Model** (of in dit geval specifiek via **Repositories**) en verwerkt deze logica.
4.  **View (`app/Views/*`)**: De controller stuurt de verwerkte data door naar een **View** via de `view()` helper functie. De view genereert de uiteindelijke HTML die naar de gebruiker wordt gestuurd.

## 2. Verantwoordelijkheden

Hieronder wordt beschreven waar de verantwoordelijkheden liggen en waarom:

### **Model (Repositories & Models)**
*   **Locatie**: `app/Models` en `app/Repositories`.
*   **Verantwoordelijkheid**: Communicatie met de database en datamanipulatie.
*   **Waarom**: Door data-toegang te scheiden van de controller (via het Repository Pattern), blijft de controller slank en is de code herbruikbaar. Als de database verandert, hoef je alleen de repository aan te passen.

### **View**
*   **Locatie**: `app/Views`.
*   **Verantwoordelijkheid**: Het presenteren van informatie aan de gebruiker (HTML). Views bevatten geen complexe logica, enkel weergave-logica (loops, if-statements voor weergave).
*   **Waarom**: Door presentatie te scheiden van logica, kun je het design aanpassen zonder de business logic te breken.

### **Controller**
*   **Locatie**: `app/Controllers`.
*   **Verantwoordelijkheid**: De "manager" van het verzoek. Hij neemt input van de gebruiker (HTTP request), roept de juiste modellen aan en kiest de juiste view om terug te sturen.
*   **Waarom**: De controller koppelt de invoer aan de juiste acties en uitvoer, en zorgt ervoor dat Models en Views niet direct van elkaar afhankelijk zijn.

### **Core (Router, Database, Helpers)**
*   **Locatie**: `app/Core`.
*   **Verantwoordelijkheid**: De infrastructuur van het framework. De Router koppelt URL's aan controllers, de Database class beheert de verbinding en helpers bieden algemene functies.

## 3. Concrete Ontwerpkeuzes

### Keuze 1: Repository Design Pattern
In de code zien we bijvoorbeeld `UserRepository`. Dit pattern wordt gebruikt om alle logica voor het ophalen en opslaan van data te isoleren van de Controllers.
*   **Uitleg**: In plaats van dat de Controller en Model direct SQL queries uitvoeren, zit er een tussenlaag (Repository) die methodes biedt zoals `findByEmail` of `create`.
*   **Cursusbegrip**: **Abstractie** en **Separation of Concerns**. De controller hoeft niet te weten *hoe* de data wordt opgehaald (SQL, API, bestand), alleen *dat* hij data krijgt. Dit maakt de code testbaarder en makkelijker aan te passen.

### Keuze 2: Singleton Database Connectie
In `app/Core/Database.php` wordt de methode `getInstance()` gebruikt om de connectie op te halen.
*   **Uitleg**: Deze klasse zorgt ervoor dat er **maximaal één** databaseverbinding wordt gemaakt per request. Als je op meerdere plekken (`Repositories`) de database nodig hebt, wordt dezelfde verbinding hergebruikt.
*   **Cursusbegrip**: **Singleton Pattern**. Dit is een "creational design pattern" dat garandeert dat een klasse slechts één instantie heeft en een globaal toegangspunt biedt. Dit is geoptimaliseerd voor prestaties en voorkomt onnodige belasting van de database-server.
