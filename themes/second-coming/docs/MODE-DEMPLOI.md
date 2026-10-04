# Second Coming — mode d’emploi

Thème FSE esthétique Matrix : fond noir, texte néon, pluie numérique. La version **1.4.0** ajoute un vocabulaire de **patterns** et un champ **Generate draft** (comme Simple Role Based Pricing) : une phrase → un brouillon assemblé **uniquement** avec ces sections.

## Prérequis

- WordPress 6.0+. Generate et les abilities : **WordPress 7** (écran Connectors).
- PHP 7.4+.
- **Pas de clé dans le thème.**

Sans IA le thème marche : pluie, templates, patterns dans l’inserteur. Generate reste inactif tant qu’un connector n’est pas prêt.

## Patterns (le vocabulaire)

Dans l’éditeur : **Patterns → Second Coming**.

| Slug | Rôle |
|---|---|
| `second-coming/hero-terminal` | Hero « nœud d’accès » (souvent en premier) |
| `second-coming/signal-strip` | Barre de statut |
| `second-coming/classified-grid` | Trois dossiers classifiés |
| `second-coming/stack-cards` | Trois cartes empilées (stack / services) |
| `second-coming/terminal-dump` | Briefing monospace |
| `second-coming/protocol-list` | Étapes numérotées |
| `second-coming/cta-jack-in` | CTA de fin |
| `second-coming/access-denied` | Accès refusé / mentions |

Le modèle **n’invente pas** de HTML. Il choisit des slugs. Le PHP jette le reste.

## Brancher l’IA (WordPress 7)

Tout se fait dans l’admin. Pas besoin d’aller sur wordpress.org.

1. **Réglages → Connectors**.
2. Clique **Install the AI plugin** / **Installer le plugin AI** (le bandeau que WordPress affiche déjà sur cet écran).
3. Sur le même écran, **Install** un connecteur (OpenAI, Google ou Anthropic — ou un autre listé), colle la clé API, enregistre.
4. **Réglages → AI** → active **Enable AI** (en haut à droite).

Si Generate échoue avec un message d’approbation / « not approved » : l’expérience **Connector Approvals** est peut‑être on (**Réglages → AI**). Va alors dans **Outils → Connector Approvals**, **Approve** **Second Coming** pour ce connecteur, puis relance Generate.

## Générer une page (propriétaire)

1. Finis **Brancher l’IA** ci‑dessus.
2. Va sur **Pages**.
3. Dans **Generate with Second Coming**, une phrase, ex. *Page données confidentielles, bureau Matrix : grille de fichiers, avertissement, CTA.*
4. **Generate draft**. Tu arrives sur un **brouillon** (jamais publié tout seul).
5. Corrige le copy, publie.

Le même bloc est dans la colonne de l’éditeur de page.

Exemple :

> Ajoute une page données confidentielles dans le style d’un bureau Matrix.

Stack attendu : hero → grille classifiée → access denied → CTA.

## Abilities / MCP (WP 6.9+)

Même écriture que le bouton :

- `second-coming/list-patterns`
- `second-coming/create-page` — `title`, `patterns[]`, `copy` optionnel (`sc_headline`, `sc_lede`, …)

Droit : **edit_pages**. Statut par défaut : **draft**. `mcp.public` pour un agent MCP. Le front public, non.

## Pluie et pilules

Pied de page : pilule bleue = stop pluie, rouge = restart. Le lapin ouvre vitesse / jeu de caractères. Rien à voir avec Generate.

## Vie privée

Generate envoie ta phrase et le catalogue de patterns au provider **que tu as** configuré. Le thème n’embarque aucune clé.
