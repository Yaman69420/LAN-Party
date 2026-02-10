---
tags: [lan-party, frontend, alpinejs, tailwind, cyberpunk, ux]
created: 2026-02-10
topic: Frontend Features & Client-Side Logic
---

# 🛡️ Frontend Features: The Armory & Search

## 1. The Armory Concept
"The Armory" is de frontend pagina waar gebruikers (niet-admins) items kunnen zien en reserveren.
Het doel was een **meeslepende, "premium" ervaring** die past bij het LAN-party thema (Cyberpunk).

### 🎨 Styling (TailwindCSS)
We hebben gekozen voor specifieke design keuzes:
*   **Grid Layout**: `grid-cols-1 md:grid-cols-2 lg:grid-cols-3` voor responsive kaarten.
*   **Hover Effects**: `group-hover` classes om randen en teksten te laten oplichten (Cyber Cyan) als je erover muist.
*   **Backdrop Blur**: `backdrop-blur-sm` op het hoofdcontainer-paneel voor dat typische "glass" effect.
*   **Status Indicators**: Groene `[ONLINE]` tekst of rode `[OFFLINE]` tekst op basis van voorraad (`$item->total_stock > 0`).

---

## 2. ⚡ Client-Side Zoeken & Filteren (Alpine.js)
In plaats van voor elke zoekopdracht de pagina te herladen (traag 🐢), doen we dit **direct in de browser** (snel 🐇) met **Alpine.js**.

```html
<div x-data="{ search: '', category: 'all' }">
    <!-- De 'State' wordt hierboven gedefinieerd -->
```

### Hoe werkt het filter?
Elk item in de grid heeft logica die bepaalt of hij zichtbaar (`x-show`) moet zijn:

```javascript
x-show="(category === 'all' || category === '<?= $item->category ?>') 
        && '<?= strtolower($item->name) ?>'.includes(search.toLowerCase())"
```

1.  **Categorie Check**: Is de geselecteerde categorie 'all' OF matcht de categorie van het item?
2.  **Zoek Check**: Komt de zoektekst voor in de naam van het item (beide in kleine letters voor case-insensitive)?
3.  **Resultaat**: Als beide WAAR zijn, toont Alpine het item. Anders wordt het verborgen.

### Code Voorbeeld: Knoppen
De knoppen veranderen de waarde van `category`. Alpine update de UI automatisch.

```html
<button @click="category = 'hardware'">HARDWARE</button>
```

---

## 3. Controller Logica (Backend)
De backend is heel simpel gehouden. We halen *alles* op, en laten de frontend het filteren doen.
*(Nota: Voor duizenden items zou dit traag zijn, maar voor <100 items is dit de snelste methode)*.

**Bestand:** `app/Controllers/RentalController.php`
```php
public function index(): void {
    // 1. Haal ALLE items op uit de database
    $items = $this->itemRepo->getAll();
    
    // 2. Stuur ze naar de view
    view('resources/index', ['items' => $items]);
}
```

---

## 🧠 Leerpunten
*   **Alpine.js** is perfect voor "lichtgewicht" interactiviteit zonder complexe build-steps (zoals React/Vue).
*   **State Management**: Door `search` en `category` in een `x-data` object te zetten, kunnen we overal in die `<div>` bij die variabelen.
*   **UX**: Direct feedback (zonder page reload) voelt veel moderner en sneller aan voor de gebruiker.
