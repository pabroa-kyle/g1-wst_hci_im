# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

- Framework: Laravel (PHP)
- Frontend: Blade templates + Tailwind CSS
- Database: PostgreSQL hosted and managed on Supabase (online/cloud; must be reachable from the deployed app)
- File storage: Supabase Storage bucket for profile pictures (survives redeploys)
- Hosting: Railway (public URL required)

## Users

- **Primary:** people who want a personal portfolio without building one by hand. They sign up, enter their information through forms, pick a template, and get a finished portfolio page.
- **Evaluator:** the course instructor. They open the public deployment URL in a browser, register an account, and test the whole flow from creating a portfolio through to deleting it. The app must work end to end for someone seeing it for the first time.

## Product Purpose

An Online Portfolio Template Generator. Users enter their portfolio information, save it to an online database, choose one of exactly three templates, then preview and generate their portfolio. Success means the full required flow works on the deployed site against the cloud database:

HOME → CREATE PORTFOLIO → ENTER INFORMATION → SAVE TO ONLINE DATABASE → SELECT 1 OF 3 TEMPLATES → GENERATE PORTFOLIO → PREVIEW PORTFOLIO → EDIT / DELETE

## Positioning

Users bring the content and the app supplies the design. They enter their information once and can render it in any of three visibly different templates (Simple, Modern, Creative), switching between them without re-entering anything.

## Operating Context

- An academic project (WST, HCI, IM courses, Group 1). It is graded on whether every required feature works, the online database, the public deployment, and UI quality.
- Repository is hosted on GitLab (`pabroa-kyle/g1-wst_hci_im`).
- The instructor tests in a desktop browser. Users may come from phones, so every page must be responsive.

## Capabilities and Constraints

**Accounts:** users register and log in. Each user sees and manages only their own portfolios; edit and delete are private to the owner.

**Portfolio information (required fields):**
- Full Name
- Profile Picture (uploaded image)
- Email
- Contact Number
- Address
- About Me
- Educational Background (repeatable entries)
- Skills (repeatable)
- Projects (repeatable entries)
- Work Experience (repeatable entries)
- Social Media / Website Links (repeatable)
- Additional fields are allowed when useful.

**CRUD against the online database (all required to work):** add, save, retrieve, edit, and delete portfolio information.

**Templates:** exactly three, with noticeably different designs:
1. **Simple:** clean, professional.
2. **Modern:** layout built from cards, sections, and visual elements.
3. **Creative:** a different, more inventive arrangement of the same information.

**Required pages:**
1. Home: website name, short description, Create Portfolio button.
2. Portfolio Information: form covering personal info, education, skills, projects, and experience.
3. Template Selection: shows the three templates.
4. Portfolio Preview: renders the generated portfolio in the selected template.
5. Manage Portfolio: view, edit, and delete the portfolio. This works as a clean dashboard.

Users can create, edit, complete, and delete their portfolio information.

**Open decisions:**
- Product name. The team is using a placeholder for now and must not present it as final branding.
- Whether a generated portfolio also gets a public, login-free share URL. This was not chosen, so treat it as undecided.

## Brand Commitments

None yet. The name is a placeholder. The user asked for a modern, sleek, responsive interface with a clean dashboard.

## Evidence on Hand

No real user content, testimonials, sample portfolios, logos, or brand assets exist in the repo. Do not invent testimonials, user counts, or claims about the product. Use clearly marked sample data only where a demo or template preview needs it.

## Product Principles

1. **The required flow comes first.** Every step from Home to Edit/Delete must be easy to find and must work on the live deployment. A grader should never get stuck.
2. **Enter once, render three ways.** Content is kept separate from presentation. Switching templates never changes or loses data.
3. **The templates must differ.** Simple, Modern, and Creative should differ in structure and character, not only in color.
4. **Forms should be easy to fill.** Long, repeatable sections (education, projects, experience) should stay organized and forgiving to edit.
5. **Online by design.** Data and uploads live in Supabase, and nothing depends on the developer's machine.

## Accessibility & Inclusion

The brief requires interfaces that are simple, clean, organized, easy to navigate, responsive, and easy to read. Aim for WCAG 2.1 AA contrast and keyboard-operable forms.
