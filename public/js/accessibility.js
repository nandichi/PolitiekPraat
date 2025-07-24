/**
 * PolitiekPraat Accessibility Features
 * European Accessibility Act (EAA) Compliance
 */

class AccessibilityManager {
  constructor() {
    this.focusableElements =
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    this.liveRegion = null;
    this.skipLinks = [];
    this.modals = [];
    this.init();
  }

  init() {
    this.createLiveRegion();
    this.setupSkipLinks();
    this.setupFocusManagement();
    this.setupKeyboardNavigation();
    this.setupModalAccessibility();
    this.setupFormAccessibility();
    this.setupHighContrastMode();
    this.setupReducedMotion();
    this.announcePageLoad();
  }

  // Live Region voor dynamische updates
  createLiveRegion() {
    this.liveRegion = document.createElement("div");
    this.liveRegion.setAttribute("aria-live", "polite");
    this.liveRegion.setAttribute("aria-atomic", "true");
    this.liveRegion.className = "live-region sr-only";
    this.liveRegion.id = "aria-live-region";
    document.body.appendChild(this.liveRegion);
  }

  announce(message, priority = "polite") {
    if (this.liveRegion) {
      this.liveRegion.setAttribute("aria-live", priority);
      this.liveRegion.textContent = message;

      // Reset na 1 seconde voor volgende announcements
      setTimeout(() => {
        if (this.liveRegion) {
          this.liveRegion.textContent = "";
        }
      }, 1000);
    }
  }

  // Focus Management
  setupFocusManagement() {
    // Focus visible indicator
    document.addEventListener("keydown", (e) => {
      if (e.key === "Tab") {
        document.body.classList.add("keyboard-navigation");
      }
    });

    document.addEventListener("mousedown", () => {
      document.body.classList.remove("keyboard-navigation");
    });

    // Focus trap voor modals
    this.setupFocusTrap();
  }

  setupFocusTrap() {
    const modals = document.querySelectorAll('.modal, [role="dialog"]');

    modals.forEach((modal) => {
      const focusableElements = modal.querySelectorAll(this.focusableElements);
      if (focusableElements.length === 0) return;

      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];

      modal.addEventListener("keydown", (e) => {
        if (e.key === "Tab") {
          if (e.shiftKey) {
            if (document.activeElement === firstElement) {
              e.preventDefault();
              lastElement.focus();
            }
          } else {
            if (document.activeElement === lastElement) {
              e.preventDefault();
              firstElement.focus();
            }
          }
        }

        if (e.key === "Escape") {
          this.closeModal(modal);
        }
      });
    });
  }

  // Keyboard Navigation
  setupKeyboardNavigation() {
    document.addEventListener("keydown", (e) => {
      // Global keyboard shortcuts
      switch (e.key) {
        case "F1":
          e.preventDefault();
          this.showAccessibilityHelp();
          break;
        case "/":
          if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
            this.focusSearchField();
          }
          break;
        case "h":
          if (e.altKey) {
            e.preventDefault();
            this.navigateToHomepage();
          }
          break;
      }
    });

    // Arrow key navigation voor lijsten en grids
    this.setupArrowKeyNavigation();
  }

  setupArrowKeyNavigation() {
    const navigationGroups = document.querySelectorAll(
      '[role="menu"], [role="menubar"], [role="tablist"], .keyboard-nav-group'
    );

    navigationGroups.forEach((group) => {
      const items = group.querySelectorAll(
        '[role="menuitem"], [role="tab"], .keyboard-nav-item'
      );

      group.addEventListener("keydown", (e) => {
        const currentIndex = Array.from(items).indexOf(document.activeElement);
        let nextIndex;

        switch (e.key) {
          case "ArrowDown":
          case "ArrowRight":
            e.preventDefault();
            nextIndex = (currentIndex + 1) % items.length;
            items[nextIndex].focus();
            break;
          case "ArrowUp":
          case "ArrowLeft":
            e.preventDefault();
            nextIndex = (currentIndex - 1 + items.length) % items.length;
            items[nextIndex].focus();
            break;
          case "Home":
            e.preventDefault();
            items[0].focus();
            break;
          case "End":
            e.preventDefault();
            items[items.length - 1].focus();
            break;
        }
      });
    });
  }

  // Modal Accessibility
  setupModalAccessibility() {
    document.addEventListener("click", (e) => {
      const trigger = e.target.closest("[data-modal-trigger]");
      if (trigger) {
        e.preventDefault();
        const modalId = trigger.getAttribute("data-modal-trigger");
        this.openModal(modalId);
      }

      const closeBtn = e.target.closest("[data-modal-close]");
      if (closeBtn) {
        e.preventDefault();
        const modal = closeBtn.closest('.modal, [role="dialog"]');
        this.closeModal(modal);
      }
    });
  }

  openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    // Store previous focus
    modal.previousActiveElement = document.activeElement;

    // Show modal
    modal.classList.remove("hidden");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("modal-open");

    // Focus first focusable element
    const firstFocusable = modal.querySelector(this.focusableElements);
    if (firstFocusable) {
      firstFocusable.focus();
    }

    // Announce modal opening
    const modalTitle = modal.querySelector('h1, h2, h3, [role="heading"]');
    if (modalTitle) {
      this.announce(`Modal geopend: ${modalTitle.textContent}`);
    }
  }

  closeModal(modal) {
    if (!modal) return;

    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("modal-open");

    // Restore previous focus
    if (modal.previousActiveElement) {
      modal.previousActiveElement.focus();
    }

    this.announce("Modal gesloten");
  }

  // Form Accessibility
  setupFormAccessibility() {
    // Auto-generate IDs and associate labels
    document.querySelectorAll("input, textarea, select").forEach((input) => {
      if (!input.id) {
        input.id = "input-" + Math.random().toString(36).substr(2, 9);
      }

      // Find associated label
      let label = document.querySelector(`label[for="${input.id}"]`);
      if (!label) {
        label = input.closest("label");
      }

      if (label && !label.getAttribute("for")) {
        label.setAttribute("for", input.id);
      }

      // Add required indicator
      if (input.hasAttribute("required")) {
        input.setAttribute("aria-required", "true");

        if (label && !label.textContent.includes("*")) {
          label.innerHTML +=
            ' <span class="required-indicator" aria-label="verplicht">*</span>';
        }
      }
    });

    // Form validation feedback
    document.addEventListener(
      "invalid",
      (e) => {
        e.target.setAttribute("aria-invalid", "true");

        // Create or update error message
        let errorId = e.target.id + "-error";
        let errorElement = document.getElementById(errorId);

        if (!errorElement) {
          errorElement = document.createElement("div");
          errorElement.id = errorId;
          errorElement.className = "error-message";
          errorElement.setAttribute("role", "alert");
          e.target.insertAdjacentElement("afterend", errorElement);
        }

        errorElement.textContent = e.target.validationMessage;
        e.target.setAttribute("aria-describedby", errorId);

        this.announce(
          `Fout in formulier: ${e.target.validationMessage}`,
          "assertive"
        );
      },
      true
    );

    document.addEventListener("input", (e) => {
      if (e.target.checkValidity()) {
        e.target.setAttribute("aria-invalid", "false");
        const errorElement = document.getElementById(e.target.id + "-error");
        if (errorElement) {
          errorElement.remove();
          e.target.removeAttribute("aria-describedby");
        }
      }
    });
  }

  // High Contrast Mode Detection
  setupHighContrastMode() {
    const mediaQuery = window.matchMedia("(prefers-contrast: high)");

    const handleContrastChange = (e) => {
      if (e.matches) {
        document.body.classList.add("high-contrast-mode");
        this.announce("Hoog contrast modus geactiveerd");
      } else {
        document.body.classList.remove("high-contrast-mode");
      }
    };

    mediaQuery.addListener(handleContrastChange);
    handleContrastChange(mediaQuery);
  }

  // Reduced Motion Detection
  setupReducedMotion() {
    const mediaQuery = window.matchMedia("(prefers-reduced-motion: reduce)");

    const handleMotionChange = (e) => {
      if (e.matches) {
        document.body.classList.add("reduced-motion");
      } else {
        document.body.classList.remove("reduced-motion");
      }
    };

    mediaQuery.addListener(handleMotionChange);
    handleMotionChange(mediaQuery);
  }

  // Helper Functions
  showAccessibilityHelp() {
    const helpModal = this.createHelpModal();
    document.body.appendChild(helpModal);
    this.openModal(helpModal.id);
  }

  createHelpModal() {
    const modal = document.createElement("div");
    modal.id = "accessibility-help-modal";
    modal.className = "modal";
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-labelledby", "help-title");
    modal.innerHTML = `
            <div class="modal-content">
                <h2 id="help-title">Toegankelijkheids Help</h2>
                <div class="help-content">
                    <h3>Sneltoetsen:</h3>
                    <ul>
                        <li><kbd>Tab</kbd> - Navigeer door elementen</li>
                        <li><kbd>Shift + Tab</kbd> - Navigeer terug</li>
                        <li><kbd>Enter/Space</kbd> - Activeer links en knoppen</li>
                        <li><kbd>Escape</kbd> - Sluit modals</li>
                        <li><kbd>F1</kbd> - Toon deze help</li>
                        <li><kbd>Alt + H</kbd> - Ga naar homepage</li>
                        <li><kbd>Ctrl/Cmd + /</kbd> - Focus zoekbalk</li>
                    </ul>
                    <h3>Stemwijzer navigatie:</h3>
                    <ul>
                        <li><kbd>1</kbd> - Eens</li>
                        <li><kbd>2</kbd> - Neutraal</li>
                        <li><kbd>3</kbd> - Oneens</li>
                        <li><kbd>→</kbd> - Volgende vraag</li>
                        <li><kbd>←</kbd> - Vorige vraag</li>
                    </ul>
                </div>
                <button data-modal-close class="btn-primary">Sluiten</button>
            </div>
        `;
    return modal;
  }

  focusSearchField() {
    const searchField = document.querySelector(
      'input[type="search"], input[name="search"], #search'
    );
    if (searchField) {
      searchField.focus();
      this.announce("Zoekbalk gefocust");
    }
  }

  navigateToHomepage() {
    window.location.href = "/";
  }

  announcePageLoad() {
    const pageTitle = document.title;
    const mainHeading = document.querySelector("h1");

    setTimeout(() => {
      if (mainHeading) {
        this.announce(
          `Pagina geladen: ${pageTitle}. Hoofdinhoud: ${mainHeading.textContent}`
        );
      } else {
        this.announce(`Pagina geladen: ${pageTitle}`);
      }
    }, 500);
  }

  // Public API voor andere scripts
  static getInstance() {
    if (!window.accessibilityManager) {
      window.accessibilityManager = new AccessibilityManager();
    }
    return window.accessibilityManager;
  }
}

// Auto-initialize when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    AccessibilityManager.getInstance();
  });
} else {
  AccessibilityManager.getInstance();
}

// Global function voor gebruik door andere scripts
window.announceToScreenReader = function (message, priority = "polite") {
  const manager = AccessibilityManager.getInstance();
  manager.announce(message, priority);
};

window.openAccessibleModal = function (modalId) {
  const manager = AccessibilityManager.getInstance();
  manager.openModal(modalId);
};

window.closeAccessibleModal = function (modal) {
  const manager = AccessibilityManager.getInstance();
  manager.closeModal(modal);
};
