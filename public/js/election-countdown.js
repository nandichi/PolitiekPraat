/**
 * Verkiezingen Countdown Timer
 * Countdown naar de Nederlandse gemeenteraadsverkiezingen op 29 oktober 2025
 */

(function () {
  "use strict";

  // Verkiezingsdatum: 29 oktober 2025, 07:30 (stembureaus openen)
  const electionDate = new Date("2025-10-29T07:30:00").getTime();

  // Elementen ophalen
  const daysElement = document.getElementById("countdown-days");
  const hoursElement = document.getElementById("countdown-hours");
  const minutesElement = document.getElementById("countdown-minutes");
  const secondsElement = document.getElementById("countdown-seconds");

  // Controleer of alle elementen bestaan
  if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
    console.error("Countdown elementen niet gevonden");
    return;
  }

  /**
   * Update de countdown met animatie
   */
  function updateCountdown() {
    const now = new Date().getTime();
    const distance = electionDate - now;

    // Als de verkiezingsdag is bereikt
    if (distance < 0) {
      daysElement.textContent = "0";
      hoursElement.textContent = "0";
      minutesElement.textContent = "0";
      secondsElement.textContent = "0";
      return;
    }

    // Bereken tijd componenten
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Update met fade animatie effect
    updateElementWithAnimation(daysElement, days);
    updateElementWithAnimation(hoursElement, hours);
    updateElementWithAnimation(minutesElement, minutes);
    updateElementWithAnimation(secondsElement, seconds);
  }

  /**
   * Update element met een subtiele animatie
   */
  function updateElementWithAnimation(element, newValue) {
    const currentValue = element.textContent;
    const formattedValue = String(newValue).padStart(2, "0");

    if (currentValue !== formattedValue) {
      // Voeg animatie klasse toe
      element.style.transform = "scale(1.1)";
      element.style.transition = "transform 0.2s ease";

      // Update waarde
      setTimeout(() => {
        element.textContent = formattedValue;

        // Reset transformatie
        setTimeout(() => {
          element.style.transform = "scale(1)";
        }, 100);
      }, 100);
    }
  }

  /**
   * Format getal met voorloopnul
   */
  function formatNumber(num) {
    return String(num).padStart(2, "0");
  }

  /**
   * Initialiseer countdown
   */
  function initCountdown() {
    // Eerste update meteen uitvoeren
    updateCountdown();

    // Update elke seconde
    setInterval(updateCountdown, 1000);
  }

  // Start countdown wanneer DOM geladen is
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCountdown);
  } else {
    initCountdown();
  }

  // Voeg visuele feedback toe bij hover
  const countdownItems = document.querySelectorAll(".countdown-item");
  countdownItems.forEach((item) => {
    item.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-8px) scale(1.05)";
    });

    item.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)";
    });
  });

  // Performance optimalisatie: pauzeer countdown bij tab niet actief
  let countdownInterval;
  let isTabActive = true;

  document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
      isTabActive = false;
    } else {
      isTabActive = true;
      updateCountdown(); // Update meteen bij terugkomen naar tab
    }
  });
})();
