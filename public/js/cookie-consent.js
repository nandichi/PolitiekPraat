/**
 * GDPR Cookie Consent Manager
 * Voldoet aan European Privacy Laws
 */

class CookieConsentManager {
  constructor() {
    this.consentTypes = {
      necessary: { name: "Noodzakelijk", required: true, enabled: true },
      analytics: { name: "Analytics", required: false, enabled: false },
      marketing: { name: "Marketing", required: false, enabled: false },
      functional: { name: "Functioneel", required: false, enabled: false },
    };

    this.consentKey = "politiekpraat_cookie_consent";
    this.consentVersion = "1.0";
    this.isConsentGiven = false;

    this.init();
  }

  init() {
    this.loadExistingConsent();
    if (!this.isConsentGiven) {
      this.showConsentBanner();
    } else {
      this.applyConsent();
    }
    this.setupConsentButton();
  }

  loadExistingConsent() {
    try {
      const stored = localStorage.getItem(this.consentKey);
      if (stored) {
        const consent = JSON.parse(stored);
        if (consent.version === this.consentVersion) {
          this.consentTypes = { ...this.consentTypes, ...consent.preferences };
          this.isConsentGiven = true;
        }
      }
    } catch (e) {
      console.warn("Error loading cookie consent:", e);
    }
  }

  saveConsent() {
    const consent = {
      version: this.consentVersion,
      timestamp: new Date().toISOString(),
      preferences: this.consentTypes,
    };

    try {
      localStorage.setItem(this.consentKey, JSON.stringify(consent));
    } catch (e) {
      console.warn("Error saving cookie consent:", e);
    }
  }

  showConsentBanner() {
    const banner = this.createConsentBanner();
    document.body.appendChild(banner);

    // Announce to screen readers
    setTimeout(() => {
      if (window.announceToScreenReader) {
        window.announceToScreenReader(
          "Cookie toestemming vereist. Gebruik Tab om te navigeren naar de opties.",
          "assertive"
        );
      }
    }, 500);
  }

  createConsentBanner() {
    const banner = document.createElement("div");
    banner.id = "cookie-consent-banner";
    banner.className = "cookie-consent-banner";
    banner.setAttribute("role", "dialog");
    banner.setAttribute("aria-labelledby", "consent-title");
    banner.setAttribute("aria-describedby", "consent-description");

    banner.innerHTML = `
            <div class="consent-overlay"></div>
            <div class="consent-content">
                <div class="consent-header">
                    <h2 id="consent-title" class="consent-title">🍪 Cookie Voorkeuren</h2>
                    <p id="consent-description" class="consent-description">
                        Wij gebruiken cookies om uw ervaring te verbeteren en onze website te analyseren. 
                        U kunt uw voorkeuren hieronder instellen.
                    </p>
                </div>
                
                <div class="consent-options">
                    ${this.createConsentToggles()}
                </div>
                
                <div class="consent-info">
                    <p class="text-sm text-gray-600">
                        <a href="/privacy-policy" class="underline">Meer informatie over onze cookies</a> | 
                        <a href="/privacy-policy" class="underline">Privacy Policy</a>
                    </p>
                </div>
                
                <div class="consent-actions">
                    <button id="accept-all-cookies" class="btn btn-primary" type="button">
                        Alles Accepteren
                    </button>
                    <button id="save-cookie-preferences" class="btn btn-secondary" type="button">
                        Voorkeuren Opslaan
                    </button>
                    <button id="reject-all-cookies" class="btn btn-outline" type="button">
                        Alleen Noodzakelijk
                    </button>
                </div>
            </div>
        `;

    this.addConsentEventListeners(banner);
    return banner;
  }

  createConsentToggles() {
    return Object.entries(this.consentTypes)
      .map(
        ([key, type]) => `
            <div class="consent-toggle-group">
                <div class="consent-toggle-header">
                    <label class="consent-toggle-label" for="consent-${key}">
                        <input type="checkbox" 
                               id="consent-${key}" 
                               ${type.enabled ? "checked" : ""} 
                               ${type.required ? "disabled" : ""}
                               data-consent-type="${key}"
                               class="consent-checkbox">
                        <span class="consent-toggle-slider" aria-hidden="true"></span>
                        <span class="consent-toggle-text">
                            ${type.name}
                            ${type.required ? " (Verplicht)" : ""}
                        </span>
                    </label>
                </div>
                <div class="consent-toggle-description">
                    ${this.getConsentDescription(key)}
                </div>
            </div>
        `
      )
      .join("");
  }

  getConsentDescription(type) {
    const descriptions = {
      necessary:
        "Deze cookies zijn essentieel voor het functioneren van de website.",
      analytics:
        "Helpen ons begrijpen hoe bezoekers onze website gebruiken via anonieme statistieken.",
      marketing:
        "Gebruikt voor gerichte advertenties en het meten van campagne-effectiviteit.",
      functional:
        "Onthouden uw voorkeuren en instellingen voor een betere gebruikerservaring.",
    };
    return descriptions[type] || "";
  }

  addConsentEventListeners(banner) {
    // Accept all
    banner
      .querySelector("#accept-all-cookies")
      .addEventListener("click", () => {
        Object.keys(this.consentTypes).forEach((key) => {
          this.consentTypes[key].enabled = true;
        });
        this.acceptConsent();
      });

    // Save preferences
    banner
      .querySelector("#save-cookie-preferences")
      .addEventListener("click", () => {
        this.updatePreferencesFromForm(banner);
        this.acceptConsent();
      });

    // Reject all (only necessary)
    banner
      .querySelector("#reject-all-cookies")
      .addEventListener("click", () => {
        Object.keys(this.consentTypes).forEach((key) => {
          this.consentTypes[key].enabled = this.consentTypes[key].required;
        });
        this.acceptConsent();
      });

    // Checkbox changes
    banner.querySelectorAll(".consent-checkbox").forEach((checkbox) => {
      checkbox.addEventListener("change", (e) => {
        const type = e.target.dataset.consentType;
        this.consentTypes[type].enabled = e.target.checked;
      });
    });

    // Keyboard navigation
    banner.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        // Reject all on escape
        this.rejectAllCookies();
      }
    });
  }

  updatePreferencesFromForm(banner) {
    banner.querySelectorAll(".consent-checkbox").forEach((checkbox) => {
      const type = checkbox.dataset.consentType;
      this.consentTypes[type].enabled = checkbox.checked;
    });
  }

  acceptConsent() {
    this.isConsentGiven = true;
    this.saveConsent();
    this.removeConsentBanner();
    this.applyConsent();

    if (window.announceToScreenReader) {
      window.announceToScreenReader("Cookie voorkeuren opgeslagen");
    }
  }

  rejectAllCookies() {
    Object.keys(this.consentTypes).forEach((key) => {
      this.consentTypes[key].enabled = this.consentTypes[key].required;
    });
    this.acceptConsent();
  }

  removeConsentBanner() {
    const banner = document.getElementById("cookie-consent-banner");
    if (banner) {
      banner.remove();
    }
  }

  applyConsent() {
    // Google Analytics
    if (this.consentTypes.analytics.enabled) {
      this.enableGoogleAnalytics();
    } else {
      this.disableGoogleAnalytics();
    }

    // Marketing cookies
    if (this.consentTypes.marketing.enabled) {
      this.enableMarketing();
    } else {
      this.disableMarketing();
    }

    // Functional cookies
    if (this.consentTypes.functional.enabled) {
      this.enableFunctional();
    } else {
      this.disableFunctional();
    }

    // Update data layer for GTM
    this.updateDataLayer();
  }

  enableGoogleAnalytics() {
    if (typeof gtag !== "undefined") {
      gtag("consent", "update", {
        analytics_storage: "granted",
      });
    }

    // Set GA cookie
    this.setCookie("_ga_consent", "granted", 365);
  }

  disableGoogleAnalytics() {
    if (typeof gtag !== "undefined") {
      gtag("consent", "update", {
        analytics_storage: "denied",
      });
    }

    // Remove GA cookies
    this.deleteCookie("_ga");
    this.deleteCookie("_ga_*");
    this.deleteCookie("_gid");
  }

  enableMarketing() {
    if (typeof gtag !== "undefined") {
      gtag("consent", "update", {
        ad_storage: "granted",
      });
    }
  }

  disableMarketing() {
    if (typeof gtag !== "undefined") {
      gtag("consent", "update", {
        ad_storage: "denied",
      });
    }
  }

  enableFunctional() {
    this.setCookie("functional_consent", "granted", 365);
  }

  disableFunctional() {
    this.deleteCookie("functional_consent");
  }

  updateDataLayer() {
    if (typeof dataLayer !== "undefined") {
      dataLayer.push({
        event: "cookie_consent_update",
        consent_preferences: this.consentTypes,
      });
    }
  }

  setupConsentButton() {
    // Add consent management button to footer
    const consentButton = document.createElement("button");
    consentButton.id = "manage-cookie-consent";
    consentButton.className = "cookie-settings-btn";
    consentButton.innerHTML = "🍪 Cookie Instellingen";
    consentButton.setAttribute("aria-label", "Cookie voorkeuren beheren");

    consentButton.addEventListener("click", () => {
      this.showConsentBanner();
    });

    // Add to footer
    const footer = document.querySelector("footer");
    if (footer) {
      footer.appendChild(consentButton);
    }
  }

  // Cookie utilities
  setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
  }

  getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(";");
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === " ") c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

  deleteCookie(name) {
    // Handle wildcard patterns
    if (name.includes("*")) {
      const pattern = name.replace("*", "");
      const cookies = document.cookie.split(";");
      cookies.forEach((cookie) => {
        const cookieName = cookie.split("=")[0].trim();
        if (cookieName.startsWith(pattern)) {
          document.cookie = `${cookieName}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
        }
      });
    } else {
      document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;`;
    }
  }

  // Public API
  hasConsent(type) {
    return this.consentTypes[type] && this.consentTypes[type].enabled;
  }

  revokeConsent() {
    localStorage.removeItem(this.consentKey);
    this.isConsentGiven = false;
    Object.keys(this.consentTypes).forEach((key) => {
      this.consentTypes[key].enabled = this.consentTypes[key].required;
    });
    this.showConsentBanner();
  }

  static getInstance() {
    if (!window.cookieConsentManager) {
      window.cookieConsentManager = new CookieConsentManager();
    }
    return window.cookieConsentManager;
  }
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    CookieConsentManager.getInstance();
  });
} else {
  CookieConsentManager.getInstance();
}

// Global API
window.cookieConsent = {
  hasConsent: (type) => CookieConsentManager.getInstance().hasConsent(type),
  showSettings: () => CookieConsentManager.getInstance().showConsentBanner(),
  revoke: () => CookieConsentManager.getInstance().revokeConsent(),
};
