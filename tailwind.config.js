/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.php",
    "./controllers/**/*.php",
    "./public/**/*.{html,js,php}",
    "./includes/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#1a365d",
        "primary-dark": "#0f2a44",
        "primary-light": "#2d4a6b",
        secondary: "#c41e3a",
        "secondary-dark": "#9e1829",
        "secondary-light": "#d63856",
        accent: "#F59E0B",
      },
      typography: {
        DEFAULT: {
          css: {
            color: "#374151",
            a: {
              color: "#1a56db",
              "&:hover": {
                color: "#1e429f",
              },
            },
            strong: {
              color: "#111827",
            },
            h1: {
              color: "#111827",
            },
            h2: {
              color: "#111827",
            },
            h3: {
              color: "#111827",
            },
            h4: {
              color: "#111827",
            },
            blockquote: {
              borderLeftColor: "#1a56db",
              color: "#4B5563",
            },
            code: {
              color: "#1a56db",
              backgroundColor: "#EEF2FF",
              padding: "0.2em 0.4em",
              borderRadius: "0.25rem",
              fontWeight: "500",
            },
            "code::before": {
              content: '""',
            },
            "code::after": {
              content: '""',
            },
            pre: {
              backgroundColor: "#1E293B",
              color: "#E5E7EB",
              code: {
                backgroundColor: "transparent",
                color: "inherit",
                padding: "0",
              },
            },
          },
        },
      },
    },
  },
  plugins: [require("@tailwindcss/typography")],
};
