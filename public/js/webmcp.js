/**
 * WebMCP integratie voor PolitiekPraat.
 *
 * Stelt site-acties beschikbaar als tools aan AI-agents die een browser
 * gebruiken die de WebMCP-specificatie ondersteunt:
 * https://webmachinelearning.github.io/webmcp/
 *
 * Feature-detect op navigator.modelContext voordat we iets registreren,
 * zodat browsers zonder ondersteuning geen errors geven.
 */

(function () {
  'use strict';

  if (typeof navigator === 'undefined' || !navigator.modelContext || typeof navigator.modelContext.provideContext !== 'function') {
    return;
  }

  var origin = window.location.origin || 'https://politiekpraat.nl';

  function navigateTo(path) {
    try {
      window.location.assign(origin + path);
      return { ok: true, url: origin + path };
    } catch (err) {
      return { ok: false, error: String(err) };
    }
  }

  function slugify(value) {
    return String(value || '')
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
  }

  async function fetchJson(url) {
    var res = await fetch(url, {
      headers: { 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    if (!res.ok) {
      throw new Error('HTTP ' + res.status);
    }
    return res.json();
  }

  var tools = [
    {
      name: 'openPartijMeter',
      description: 'Open de PolitiekPraat PartijMeter: een gratis online stemhulp met ~30 stellingen die de match met alle Nederlandse politieke partijen berekent.',
      inputSchema: { type: 'object', properties: {}, additionalProperties: false },
      execute: function () { return navigateTo('/partijmeter'); }
    },
    {
      name: 'openStemwijzer',
      description: 'Open de gemeentelijke Stemwijzer voor de gemeente Ede (gemeenteraadsverkiezingen 2026).',
      inputSchema: { type: 'object', properties: {}, additionalProperties: false },
      execute: function () { return navigateTo('/stemwijzer'); }
    },
    {
      name: 'openPolitiekKompas',
      description: 'Open het Politieke Kompas met visuele partijvergelijking op progressief-conservatieve en links-rechts assen.',
      inputSchema: { type: 'object', properties: {}, additionalProperties: false },
      execute: function () { return navigateTo('/politiek-kompas'); }
    },
    {
      name: 'openBlogs',
      description: 'Open het blogsoverzicht met politieke analyses, opinies en columns.',
      inputSchema: { type: 'object', properties: {}, additionalProperties: false },
      execute: function () { return navigateTo('/blogs'); }
    },
    {
      name: 'openBlog',
      description: 'Open een specifieke blog op PolitiekPraat aan de hand van zijn slug.',
      inputSchema: {
        type: 'object',
        properties: {
          slug: { type: 'string', description: 'URL-slug van de blog (bv. "kabinet-valt").' }
        },
        required: ['slug'],
        additionalProperties: false
      },
      execute: function (args) {
        var slug = slugify(args && args.slug);
        if (!slug) { return { ok: false, error: 'slug_required' }; }
        return navigateTo('/blogs/' + encodeURIComponent(slug));
      }
    },
    {
      name: 'openPartij',
      description: 'Open de detailpagina van een Nederlandse politieke partij.',
      inputSchema: {
        type: 'object',
        properties: {
          slug: { type: 'string', description: 'URL-slug van de partij (bv. "vvd", "groenlinks-pvda").' }
        },
        required: ['slug'],
        additionalProperties: false
      },
      execute: function (args) {
        var slug = slugify(args && args.slug);
        if (!slug) { return { ok: false, error: 'slug_required' }; }
        return navigateTo('/partijen/' + encodeURIComponent(slug));
      }
    },
    {
      name: 'openThema',
      description: 'Open de detailpagina van een politiek thema (bv. klimaat, zorg, wonen).',
      inputSchema: {
        type: 'object',
        properties: {
          slug: { type: 'string', description: 'URL-slug van het thema.' }
        },
        required: ['slug'],
        additionalProperties: false
      },
      execute: function (args) {
        var slug = slugify(args && args.slug);
        if (!slug) { return { ok: false, error: 'slug_required' }; }
        return navigateTo('/thema/' + encodeURIComponent(slug));
      }
    },
    {
      name: 'openForum',
      description: 'Open het PolitiekPraat forum met discussies over actuele politieke onderwerpen.',
      inputSchema: { type: 'object', properties: {}, additionalProperties: false },
      execute: function () { return navigateTo('/forum'); }
    },
    {
      name: 'searchBlogs',
      description: 'Zoek politieke blogs van PolitiekPraat op basis van een trefwoord. Retourneert titel, slug en samenvatting van gevonden artikelen.',
      inputSchema: {
        type: 'object',
        properties: {
          query: { type: 'string', minLength: 2, description: 'Trefwoord om in titel en inhoud te zoeken.' },
          limit: { type: 'integer', minimum: 1, maximum: 20, default: 10 }
        },
        required: ['query'],
        additionalProperties: false
      },
      execute: async function (args) {
        var query = String((args && args.query) || '').trim();
        if (query.length < 2) {
          return { ok: false, error: 'query_too_short' };
        }
        var limit = Math.min(20, Math.max(1, parseInt((args && args.limit) || 10, 10)));
        try {
          var data = await fetchJson('/api/blogs?search=' + encodeURIComponent(query) + '&limit=' + limit);
          var items = (data && data.data && (data.data.blogs || data.data)) || [];
          if (!Array.isArray(items)) { items = []; }
          return {
            ok: true,
            count: items.length,
            results: items.map(function (b) {
              return {
                id: b.id || null,
                title: b.title || '',
                slug: b.slug || '',
                summary: b.summary || '',
                url: origin + '/blogs/' + (b.slug || '')
              };
            })
          };
        } catch (err) {
          return { ok: false, error: String(err) };
        }
      }
    },
    {
      name: 'searchPartij',
      description: 'Zoek een Nederlandse politieke partij op naam of afkorting.',
      inputSchema: {
        type: 'object',
        properties: {
          name: { type: 'string', minLength: 1, description: 'Naam of afkorting van de partij (bv. "VVD", "GroenLinks-PvdA").' }
        },
        required: ['name'],
        additionalProperties: false
      },
      execute: async function (args) {
        var name = String((args && args.name) || '').trim().toLowerCase();
        if (!name) { return { ok: false, error: 'name_required' }; }
        try {
          var data = await fetchJson('/api/parties');
          var all = (data && data.data && (data.data.parties || data.data)) || [];
          if (!Array.isArray(all)) { all = []; }
          var matches = all.filter(function (p) {
            var fields = [p.name, p.short_name, p.acronym, p.slug]
              .filter(Boolean)
              .map(function (s) { return String(s).toLowerCase(); });
            return fields.some(function (f) { return f.indexOf(name) !== -1; });
          }).slice(0, 10);
          return {
            ok: true,
            count: matches.length,
            results: matches.map(function (p) {
              return {
                id: p.id || null,
                name: p.name || '',
                slug: p.slug || '',
                url: origin + '/partijen/' + (p.slug || '')
              };
            })
          };
        } catch (err) {
          return { ok: false, error: String(err) };
        }
      }
    }
  ];

  try {
    navigator.modelContext.provideContext({
      name: 'politiekpraat',
      description: 'Nederlandse politieke informatie, stemhulpen en blogs op PolitiekPraat.',
      tools: tools
    });
  } catch (err) {
    if (window.console && console.warn) {
      console.warn('[WebMCP] provideContext mislukt:', err);
    }
  }
})();
