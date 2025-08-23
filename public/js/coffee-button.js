/**
 * Simple Buy Me A Coffee Button
 * Eenvoudige, stabiele implementatie zonder bugs
 */

(function () {
  "use strict";

  // Wacht tot DOM geladen is
  function initCoffeeButton() {
    // Check of button al bestaat
    if (document.querySelector(".coffee-button")) {
      return;
    }

    // Maak button element
    const button = document.createElement("a");
    button.href = "https://buymeacoffee.com/politiekpraat";
    button.target = "_blank";
    button.rel = "noopener noreferrer";
    button.className = "coffee-button";
    button.setAttribute("aria-label", "Steun PolitiekPraat met een koffie");
    button.title = "Buy me a coffee";

    // Voeg HTML content toe
    button.innerHTML = `
      <svg class="coffee-icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
        <path d="M18.5 3H6a1 1 0 0 0-1 1v11a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4v-3h1.5A2.5 2.5 0 0 0 23 9.5v-4A2.5 2.5 0 0 0 20.5 3H18.5zM13 17H9a2 2 0 0 1-2-2V5h8v10A2 2 0 0 1 13 17zM21 9.5a.5.5 0 0 1-.5.5H19V7h1.5a.5.5 0 0 1 .5.5v2z"/>
        <circle cx="8" cy="7" r="0.5"/>
        <circle cx="10" cy="7" r="0.5"/>
        <circle cx="12" cy="7" r="0.5"/>
      </svg>
      <span class="coffee-text">Doneer een kop koffie</span>
    `;

    // Voeg click tracking toe
    button.addEventListener("click", function () {
      console.log("Coffee button clicked");

      // Analytics tracking (als beschikbaar)
      if (typeof gtag !== "undefined") {
        gtag("event", "click", {
          event_category: "engagement",
          event_label: "buy_me_coffee",
        });
      }
    });

    // Voeg button toe aan pagina
    document.body.appendChild(button);

    console.log("Coffee button initialized");
  }

  // Initialize wanneer DOM ready is
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCoffeeButton);
  } else {
    initCoffeeButton();
  }
})();
