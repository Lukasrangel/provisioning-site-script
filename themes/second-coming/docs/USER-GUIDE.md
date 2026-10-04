# Second Coming — user guide

Matrix-style Full Site Editing theme. Dark canvas, neon type, digital rain. Version **1.4.0** adds a vocabulary of block patterns and an optional **Generate draft** field so the site owner can describe a page in one sentence and get a draft assembled **only** from those patterns.

## Requirements

- WordPress 6.0 or later. Generate and Abilities need **WordPress 7** (Connectors screen).
- PHP 7.4+.
- No API key in the theme.

Without AI the theme still works: rain, templates, patterns in the inserter. Generate stays off until a connector is ready.

## Patterns (the vocabulary)

In the block editor: **Patterns → Second Coming**.

| Slug | Role |
|---|---|
| `second-coming/hero-terminal` | Access-node hero (start here) |
| `second-coming/signal-strip` | One-line status bar |
| `second-coming/classified-grid` | Three file cards (dossiers) |
| `second-coming/stack-cards` | Three stacked cards (services / team) |
| `second-coming/terminal-dump` | Monospace briefing |
| `second-coming/protocol-list` | Numbered steps |
| `second-coming/cta-jack-in` | Closing CTA (end here) |
| `second-coming/access-denied` | Restricted / legal warning |

The model is **not** allowed to invent HTML. It picks slugs from this list. Unknown slugs are dropped.

## Connect AI (WordPress 7)

Stay in the dashboard. You do not need wordpress.org.

1. **Settings → Connectors**.
2. Use **Install the AI plugin** on that screen (the notice WordPress already shows).
3. On the same screen, **Install** a connector (OpenAI, Google, or Anthropic — or another one listed there), then paste the API key and save.
4. **Settings → AI** → turn on **Enable AI** (top right).

If Generate fails with an approval / “not approved” message: **Settings → AI** may have **Connector Approvals** on. Then open **Tools → Connector Approvals**, approve **Second Coming** for that connector, and generate again.

## Generate a page (site owner)

1. Finish **Connect AI** above.
2. Open **Pages**.
3. In **Generate with Second Coming**, type a sentence, e.g. *A confidential-data page, Matrix office: file grid, warning, then jack-in.*
4. **Generate draft**. You land in the editor on a **draft** (never auto-published).
5. Edit copy, then publish.

The same box sits in the page editor sidebar.

Example that matches the theme:

> Add a confidential data page in the style of a Matrix office.

Expected stack: hero → classified grid → access denied → CTA.

## Abilities / MCP (WordPress 6.9+)

Same write path as the button:

- `second-coming/list-patterns` — live catalog
- `second-coming/create-page` — `title`, `patterns[]`, optional `copy` tokens (`sc_headline`, `sc_lede`, …)

Permission: **edit_pages**. Default status: **draft**. `mcp.public` is on so an MCP client can call them. The front of the site cannot.

## Rain and pills

Footer: blue pill stops the rain, red pill starts it. The rabbit on the edge opens speed / character-set controls. That is unrelated to Generate.

## Privacy

Generate sends your sentence and the pattern catalog to **your** configured provider. The theme does not ship a key and does not call a vendor of its own.
