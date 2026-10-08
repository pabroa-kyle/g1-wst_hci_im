---
name: Folio
description: An identity manual for your portfolio. Enter it once, see it applied three ways.
colors:
  ink-50: "#f1f2fd"
  ink-100: "#e2e5fb"
  ink-200: "#c6ccf6"
  ink-300: "#9aa4ee"
  ink-400: "#5b6ce0"
  ink-500: "#1f33c9"
  ink-600: "#1828a6"
  ink-700: "#121f80"
  ink-800: "#0d175e"
  n-0: "#ffffff"
  n-50: "#f6f7fb"
  n-100: "#eceef5"
  n-200: "#dadde8"
  n-300: "#bfc3d3"
  n-400: "#9398ae"
  n-500: "#646982"
  n-600: "#4c5168"
  n-700: "#373b4f"
  n-800: "#232637"
  n-900: "#151726"
  n-950: "#0d0f1a"
  coral-50: "#fff1ed"
  coral-400: "#ff5a36"
  coral-600: "#c9381a"
  go-50: "#eaf7f0"
  go-400: "#16a06a"
  go-700: "#0d6e48"
  danger-50: "#fdeeee"
  danger-600: "#b42318"
  danger-700: "#911b12"
typography:
  display:
    fontFamily: "Archivo Variable, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(2.5rem, 6.4vw, 5.75rem)"
    fontWeight: 800
    lineHeight: 0.95
    letterSpacing: "-0.04em"
    fontVariation: "'wdth' 125"
  headline:
    fontFamily: "Archivo Variable, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.75rem, 3vw, 2.5rem)"
    fontWeight: 800
    lineHeight: 1.05
    letterSpacing: "-0.03em"
    fontVariation: "'wdth' 125"
  title:
    fontFamily: "Archivo Variable, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: "-0.01em"
    fontVariation: "'wdth' 112"
  body:
    fontFamily: "Archivo Variable, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 400
    lineHeight: 1.625
    fontFeature: "'tnum' 1"
  label:
    fontFamily: "Archivo Variable, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.6875rem"
    fontWeight: 600
    lineHeight: 1.3
    letterSpacing: "0.08em"
    fontVariation: "'wdth' 125"
rounded:
  sheet: "2px"
  inset: "3px"
  ui: "4px"
  pill: "9999px"
spacing:
  gutter-sm: "16px"
  gutter-md: "24px"
  gutter-lg: "40px"
  grid-gap: "24px"
  grid-gap-lg: "32px"
  card-pad: "20px"
components:
  button-primary:
    backgroundColor: "{colors.ink-500}"
    textColor: "{colors.n-0}"
    rounded: "{rounded.ui}"
    padding: "10px 16px"
  button-primary-hover:
    backgroundColor: "{colors.ink-600}"
  button-primary-active:
    backgroundColor: "{colors.ink-700}"
  button-secondary:
    backgroundColor: "{colors.n-0}"
    textColor: "{colors.n-900}"
    rounded: "{rounded.ui}"
    padding: "10px 16px"
  button-secondary-hover:
    backgroundColor: "{colors.n-50}"
  button-ghost:
    textColor: "{colors.n-700}"
    rounded: "{rounded.ui}"
    padding: "10px 16px"
  button-ghost-hover:
    backgroundColor: "{colors.n-100}"
    textColor: "{colors.n-900}"
  button-danger:
    backgroundColor: "{colors.danger-600}"
    textColor: "{colors.n-0}"
    rounded: "{rounded.ui}"
    padding: "10px 16px"
  button-on-ink:
    backgroundColor: "{colors.n-0}"
    textColor: "{colors.ink-600}"
    rounded: "{rounded.ui}"
    padding: "12px 20px"
  input:
    backgroundColor: "{colors.n-0}"
    textColor: "{colors.n-900}"
    rounded: "{rounded.ui}"
    padding: "10px 12px"
  panel:
    backgroundColor: "{colors.n-0}"
    rounded: "{rounded.ui}"
  sheet:
    backgroundColor: "{colors.n-0}"
    rounded: "{rounded.sheet}"
  chip-generated:
    backgroundColor: "{colors.go-50}"
    textColor: "{colors.go-700}"
    rounded: "{rounded.pill}"
    padding: "4px 10px"
  chip-draft:
    backgroundColor: "{colors.n-100}"
    textColor: "{colors.n-600}"
    rounded: "{rounded.pill}"
    padding: "4px 10px"
  rail-nav-item:
    textColor: "{colors.ink-100}"
    rounded: "{rounded.ui}"
    padding: "8px 12px"
  rail-nav-item-active:
    backgroundColor: "{colors.ink-700}"
    textColor: "{colors.n-0}"
---

# Design System: Folio

## Overview

**Creative North Star: "The Identity Program"**

Folio is the manual for a personal identity. A corporate identity program has three things: a single solid colour plate, card stock that the applications are printed on, and a labelling voice that names each application precisely. Folio's app shell is built from exactly those. Ultramarine ink is the plate (the side rail, the applications spread, the closing call to action, every primary action). Cool white card stock is the ground. An extended grotesk sets headings and the tracked spec captions that label each application ("Template 02 · Sora"). The user's own content, rendered live in three templates, is the artwork the manual exists to show.

Density is calm but working: a 256px ink rail, a 12-column content armature, hairline rules instead of boxes wherever a rule will do. Corners are square-ish. Depth is reserved for paper: the scaled template sheets are the only objects that lie on the table and cast a shadow. The build rejects the generic indigo SaaS card dashboard. Ink is a solid flat plate, not a gradient, not a glow.

State has its own small vocabulary that never overlaps the brand: coral for unsaved, green for saved and complete, danger red for destruction.

**Key Characteristics:**
- One solid ultramarine plate (ink-500) carrying navigation and primary action.
- One neutral ramp (n-0 to n-950), tinted toward the ink, for every grey.
- Archivo on its width axis: extended for identity, normal width for UI and reading.
- Hairline rules and 4px corners; only paper sheets cast a shadow.
- Live, scaled template sheets as the signature object.
- Coral means unsaved. Nothing else is coral.

## Colors

A single cold ultramarine plate on cool white stock, with three narrow state colours kept off the brand.

### Primary
- **Ultramarine Ink Plate** (ink-500): the rail background, the full-bleed applications band on Home and the template page, the closing CTA band, primary buttons, focus outlines, text selection, and the in-progress fill of completeness segments. Always used at full strength as a flat field.
- **Pressed Ink** (ink-600 / ink-700): hover and active for primary buttons; ink-700 is the active rail item; ink-600 is the text colour of the current stepper item and of white buttons sitting on the plate.
- **Ink Tints** (ink-50 to ink-300): text and rules on the plate (ink-100 body copy, ink-200 spec captions, ink-400 at reduced opacity for hairlines), the focus ring halo on inputs (ink-100), and the informational notice strip in Preview (ink-50 ground, ink-200 rule, ink-800 text).

### Secondary
- **Unsaved Coral** (coral-400, text coral-600): the dot after a changed field's label, that field's border, and the "Unsaved changes" status in the save bar. It is the only signal for unsaved state.
- **Saved Green** (go-400, chip and text go-700, ground go-50): completed segments, the saved dot, the Generated chip, success flash, and step check marks.

### Tertiary
- **Destructive Red** (danger-600 / danger-700, ground danger-50): delete buttons, invalid field borders, error text and the "Nothing was saved" alert.

### Neutral
- **Card Stock** (n-0): sheets, panels, inputs, dialog, auth and Home grounds.
- **Table** (n-50): the app body ground behind panels.
- **Tray** (n-100): the preview stage, sheet tray at the top of a portfolio card, segmented-control track, draft chip.
- **Hairline** (n-200): dividers, panel borders, empty segment tracks. n-300 for input and secondary-button strokes and for dashed empty states.
- **Quiet Text** (n-400 to n-600): placeholders, step numbers, spec captions on stock, secondary copy.
- **Ink Black** (n-800 to n-950): labels, body text (n-900), headings (n-950).

### Named Rules
**The One Plate Rule.** Ink is applied as a flat, full-strength plate (ink-500) or as text and rules on that plate. No gradients, no glows. The pale ink-50 tint is limited to small notice strips and empty-state placeholder sheets, never a section or page wash.

**The Coral Means Unsaved Rule.** Coral marks changed, unsaved form state and nothing else: not links, not badges, not emphasis, not decoration.

**The One Ramp Rule.** Every grey comes from the n-* ramp. No stock Tailwind gray, slate, or zinc in the app shell.

## Typography

**Display Font:** Archivo Variable at 125% width (with ui-sans-serif, system-ui)
**Body Font:** Archivo Variable at normal width
**Label Font:** Archivo Variable at 125% width, tracked uppercase (the spec caption)

**Character:** One family split by its width axis. The extended cut is the identity voice, wide and heavy like a manual's cover; the normal cut does the quiet work of forms and reading. Numerals are tabular throughout.

### Hierarchy
- **Display** (800, clamp(2.5rem, 6.4vw, 5.75rem), 0.95, -0.04em, extended): the Home thesis only.
- **Headline** (800, clamp(1.75rem, 3vw, 2.5rem), 1.05, -0.03em, extended): page titles and Home section heads. The dashboard greeting scales this up to clamp(2rem, 4vw, 3.25rem) at 1.02; auth headings use clamp(2rem, 3.4vw, 2.75rem).
- **Title** (700, 1.125rem to 1.5rem, -0.01em, semi-extended 112%): card titles, dialog titles, empty-state heads, flow step names. Template names on the ink plate use the extended cut at 800.
- **Body** (400, 0.9375rem, 1.625): reading copy capped at 40 to 65ch; UI text runs at 0.875rem, help and meta text at 0.8125rem.
- **Label** (600, 0.6875rem, 1.3, 0.08em, uppercase, extended): spec captions such as "Modern template · edited 8 minutes ago", "Template 03 · Unbounded + Karla", the rail's "Your portfolios" group label, and section tags in Home's field list.

### Named Rules
**The Width Is the Voice Rule.** Extended width is for identity: headings, the wordmark, template names, spec captions, flow numerals. Buttons, fields, navigation and paragraphs stay at normal width.

**The Spec Sits Beside Rule.** A spec caption sits beside or below the thing it names (a sheet, a card, a list entry), never as an eyebrow above a heading.

## Layout

A fixed ink rail (256px, sticky full height at lg and up; collapses to a top bar with a Menu toggle below lg) and a content column on a strict 12-column grid. Cards span 12 / 6 / 4 columns at mobile / md / 2xl; the template spread and Home applications span 12 then 4 each at md. Forms take 8 columns at xl with a sticky 4-column live preview beside them.

Page gutters step 16px / 24px / 40px at base / sm / lg; grid gaps are 20 to 24px, 32px at lg on the spreads. Home's marketing sections cap at 90rem. Long forms end in a fixed save bar (white at 95%, top hairline) offset past the rail; the template page uses the same bar, sticky. Sections are separated by hairlines (n-200) or by switching ground (n-0 to n-50 to the ink plate), not by boxes.

### Named Rules
**The Twelve-Column Armature Rule.** Every content region lays out on the 12-column grid; nothing floats at an arbitrary width except reading measures capped in ch.

## Elevation & Depth

Flat by construction. Chrome (rail, panels, buttons, fields, bars) sits on the page with hairlines and tonal grounds. Depth is reserved for paper: the scaled template sheets lie on the table and cast one short, ink-tinted shadow. On hover a sheet lifts 4px (translateY) with the expo ease rather than gaining a bigger shadow.

### Shadow Vocabulary
- **Sheet on the table** (`box-shadow: 0 0 0 1px rgb(13 15 26 / 0.06), 0 18px 32px -20px rgb(10 17 69 / 0.45)`): live template sheets on the dashboard, Home, auth, the form sidebar, the template spread and the preview stage. Nothing else.

### Named Rules
**The Only Sheets Cast Shadows Rule.** A paper sheet is the only element with a shadow. Raise state with a ground change, a white outline (the chosen template), or a 4px lift, not with a blur.

## Shapes

Square-ish and printed. Sheets have a near-sharp 2px corner like trimmed stock; UI chrome (buttons, fields, panels, dialog, alerts, rail items) uses 4px; segments nested inside a 4px track use 3px. Full pills are reserved for state indicators: status chips, completeness segment bars, status dots, and the selected-template check badge. Borders are 1px hairlines; dashed n-300 or ink-300 strokes mean "empty, add something here". The wordmark is three stacked 1px-radius sheets.

## Components

### Buttons
Confident and plain: solid ink or quiet stroke, never decorated.
- **Shape:** gently squared (4px), semibold 0.875rem at normal width, 16px Lucide icon with an 8px gap.
- **Primary:** ink-500 plate with white text, 10px 16px; hover ink-600, active ink-700. Colour transitions at 150ms.
- **Secondary:** white with an n-300 hairline and n-900 text; hover n-50 ground and n-400 stroke.
- **Ghost:** n-700 text, no stroke; hover n-100 ground. Destructive ghost swaps to danger-600 text with a danger-50 hover.
- **Danger:** danger-600 solid, hover danger-700, only inside the delete confirmation.
- **On ink:** white with ink-600 text, hover ink-50, for actions placed on the plate.
- **Sizes:** sm (6px 12px, 0.8125rem), default, lg (12px 20px, 1rem). Disabled at 50% opacity.
- **Focus:** a 2px ink-500 outline offset 2px (white on the rail).

### Chips
- **Style:** pill, 0.75rem semibold, 4px 10px. Generated: go-50 ground, go-700 text, check icon. Draft: n-100 ground, n-600 text.

### Cards / Containers
- **Corner Style:** 4px for panels; 2px for sheets.
- **Background:** n-0 panel on the n-50 table. A portfolio card opens with an n-100 tray holding its live sheet, then the body.
- **Shadow Strategy:** none on panels; see Elevation.
- **Border:** 1px n-200.
- **Internal Padding:** 20px on cards, 20 to 24px on repeater rows; card actions sit below an n-200 hairline.

### Inputs / Fields
- **Style:** n-0 ground, 1px n-300 stroke, 4px corners, 10px 12px, 0.9375rem; label above in n-800 semibold with "Optional" in n-500.
- **Focus:** stroke shifts to ink-500 with a 3px ink-100 ring; caret is ink-500.
- **Error:** danger-600 stroke and a danger-50 ring, error text with an alert icon below.
- **Dirty:** a changed field holds a coral-400 stroke and a 7px coral dot after its label until saved.

### Navigation
- **Ink rail:** wordmark, then 0.9375rem medium items with 18px icons. Idle ink-100 text, hover ink-600 ground; active ink-700 ground with white text and aria-current. A spec-captioned list of recent portfolios, then the account block and Log out under an ink-400 hairline. Below lg the rail is a top bar with a Menu / Close toggle.
- **Breadcrumb:** 0.875rem n-500 links with chevrons, current page n-700.
- **Segmented control:** n-100 track with 4px padding; the selected item is a white 3px segment with ink-600 text.

### Completeness Segments (signature)
Six segments, one per section, on n-200 tracks (6px tall on cards, 4px in the stepper). Each fill is that section's exact completion: ink-500 while partial, go-400 when complete. In the form stepper the current section's track turns ink-200 and its label ink-600 bold, with a green check on finished sections.

### Template Sheet (signature)
A live page rendered at 1280px and scaled to fit a sheet. On the ink plate the three sheets sit side by side as the Applications spread, each captioned below with its name (extended 800, white) and a spec caption (ink-200). The chosen sheet is framed by a 3px white outline and a white check badge; unchosen sheets lift 4px on hover.

## Do's and Don'ts

### Do:
- **Do** put navigation and primary action on the ink-500 plate, flat and full strength.
- **Do** take every grey from the n-* ramp and every corner from 2px (sheets), 3px (nested segments) or 4px (chrome).
- **Do** set headings, template names and spec captions in extended Archivo; keep UI and reading text at normal width.
- **Do** show completeness as six exact-length segments, ink while partial and green when complete.
- **Do** use coral only for unsaved changes, and clear it when the form saves.
- **Do** let template sheets carry the one sheet shadow and lift 4px on hover with cubic-bezier(0.16, 1, 0.3, 1).

### Don't:
- **Don't** put gradients, glows, or tinted washes on the ink plate.
- **Don't** use coral for links, emphasis, badges or decoration.
- **Don't** give chrome (buttons, panels, bars, dialogs, toggles) a blur shadow; only paper sheets cast one.
- **Don't** place a spec caption as an eyebrow above a heading.
- **Don't** round containers or buttons into pills; pills are for chips, segments, dots and the check badge.
- **Don't** pull the portfolio templates' faces or colours into the app shell.

## Portfolio templates

The three user-facing templates (`resources/views/templates/*.blade.php`, styled by `resources/css/portfolio.css`) are deliberately separate worlds, excluded from the app stylesheet's sources. They are the artwork the identity manual displays; none of their tokens belong to the app system.

- **Simple:** Source Serif 4, ink on white, navy accent (#1d3a6b). A single serif column.
- **Modern:** Sora, a #0b1220 dark header, teal accents (#2dd4bf / #14b8a6), cards and skill meters.
- **Creative:** Unbounded with Karla, tangerine (#ff5b1f), lime (#d4ff3f) and #111, a poster-like split layout.
