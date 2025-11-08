# Skill Management

## Install
1. Place `skill_management` under `modules/custom/`.
2. Run `drush en skill_management -y` or enable via admin UI.
3. Build the React app:
   ```bash
   cd modules/custom/skill_management/js
   npm install
   npm run build
   ```
4. Clear caches: `drush cr`.

## Usage
- Create skills via the admin UI (or implement a simple admin form).
- Place the "Skill Selector" block on a page for authenticated users.

## Next tasks
- Add access checks and validation.
- Improve entity annotations (links, permissions).
- Add unit tests and update Views integration.
