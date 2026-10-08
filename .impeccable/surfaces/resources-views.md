---
version: 1
slug: "resources-views"
primary_target: "resources/views"
related_targets: []
---

# Surface brief: Folio app (all Blade views)

Scope: home, auth, dashboard (Manage Portfolio), stepped portfolio form, template selection, preview, and the three portfolio templates. Visitor mode: **Operate** for the app shell; the three rendered templates are **Experience** surfaces and each owns its own look (Simple / Modern / Creative), outside the app world.

Audience: students and early-career people building a portfolio; the instructor grading the required flow on the live URL.
Task: create → enter info (stepped sections, each saved on its own) → pick template → preview/generate → edit/delete. Many portfolios per account.
Constraints: Laravel + Blade + Tailwind v4; Supabase Postgres + Storage; Railway; working name "Folio" is a placeholder.

## Direction contract

THESIS: A portfolio is a personal identity. Folio is that identity's manual: you enter it once and it is shown applied across three matched templates. This refuses the generic indigo SaaS card dashboard.

OWN-WORLD: Ultramarine ink (#1F33C9) solid plate for the side rail and primary actions. Uncoated cool-white card stock ground. One fixed tonal ramp for every neutral. Extended grotesk (Archivo, width axis) for display and small tracked spec labels ("APPLICATION 02 · MODERN"); Archivo normal width for UI text. Hairline spec rules, square-ish corners (4px), no soft blur shadows on UI chrome; only the paper sheets (template previews) cast one short shadow. Coral (#FF5A36) is reserved for unsaved/changed state only. Green for saved/complete.

STORY: The visitor sees their own content become a designed identity at a glance, trusts that switching templates loses nothing, and moves from blank to published without getting lost.

FIRST VIEWPORT (dashboard): Ink rail on the left (logo, Dashboard, New portfolio, account). A header with the user's name, set large in extended Archivo, and a primary New Portfolio button. Below, a 12-column grid of portfolio cards. Each card shows a live scaled miniature of its chosen template, a spec label, a six-segment completeness bar with exact lengths, and View / Edit / Template / Delete.

FORM: Identity Program (corporate identity manual + stationery suite), candidate 6 of 7 on my ordered list; seed key 40271bab. Signature move: the "Applications" spread, where the template selection page renders the user's real data live in all three templates side by side as scaled sheets with spec captions. Raises: one coral reserved for unsaved changes (Boarding Pass); single neutral ramp (Exposure Record); strict 12-col armature (Crouwel); exact-length completeness segments (Labanotation).

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
