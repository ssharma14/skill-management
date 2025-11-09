# Skill Management System

A Drupal-based skill management system that allows users to track and manage their professional skills with experience levels.

## Overview

This project provides a comprehensive skill management solution built on Drupal 11.2.7. It enables users to search, select, and track their skills along with their experience in each skill area. The system automatically calculates proficiency levels based on the experience duration.

## Features

- **Skill Search & Selection**: Autocomplete search interface for finding and adding skills
- **Experience Tracking**: Record experience in years or months for each skill
- **Automatic Level Classification**: Skills are automatically categorized into proficiency levels:
  - Beginner: < 1 year
  - Intermediate: 1-3 years
  - Advanced: 3-5 years
  - Expert: 5+ years
- **Real-time Validation**: Experience data is validated before saving
- **Persistent Storage**: User skills and experience are stored in the database
- **Modern UI**: Clean, responsive interface built with React
- **REST API**: RESTful endpoints for skill management operations

## Technical Stack

- **Drupal Version**: 11.2.7
- **PHP**: 8.3+ (required for Drupal 11)
- **Frontend**: React (for the skill selector interface)
- **Database**: MySQL/MariaDB (standard Drupal database)
- **Package Manager**: Composer

## Project Structure

```
skill-management/
├── composer.json          # Composer dependencies
├── vendor/                # Composer packages
└── web/                   # Drupal web root
    ├── core/              # Drupal core files
    ├── modules/
    │   └── custom/
    │       └── skill_management/    # Custom skill management module
    │           ├── src/
    │           │   ├── Controller/  # API controllers
    │           │   ├── Entity/      # Custom entity definitions
    │           │   └── Plugin/      # Block plugins
    │           ├── js/              # React application
    │           │   ├── src/         # React source files
    │           │   └── dist/        # Compiled JavaScript
    │           └── css/             # Stylesheets
    └── sites/
        └── default/       # Site configuration
```

## Custom Module: Skill Management

The core functionality is provided by the `skill_management` custom module located at `web/modules/custom/skill_management/`.

### Key Components

1. **SkillApiController**: REST API endpoints for skill operations
   - `GET /api/skills`: Search skills
   - `GET /api/user-skills`: Retrieve user's saved skills
   - `POST /api/user-skills/save`: Save user skill with experience

2. **UserSkill Entity**: Custom content entity for storing user-skill relationships

3. **SkillSelectorBlock**: Block plugin that renders the skill selector interface

4. **React Frontend**: Interactive UI for skill search and management

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/ssharma14/skill-management.git
   cd skill-management
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure database settings in `web/sites/default/settings.php`

4. Install Drupal:
   ```bash
   cd web
   ../vendor/bin/drush site:install --db-url=mysql://user:pass@localhost/dbname
   ```

5. Enable the skill management module:
   ```bash
   ../vendor/bin/drush en skill_management
   ```

6. Clear cache:
   ```bash
   ../vendor/bin/drush cr
   ```

## Usage

1. Navigate to any page where the Skill Selector block is placed
2. Start typing to search for skills
3. Click on a skill to add it to your list
4. Click "Add Experience" to record your experience level
5. Enter the duration and select the unit (years/months)
6. Click "Save" to persist the data

## API Endpoints

### Search Skills
```
GET /api/skills?query=javascript
```

### Get User Skills
```
GET /api/user-skills?user_id=1
```

### Save User Skill
```
POST /api/user-skills/save
Content-Type: application/json

{
  "user_id": 1,
  "skills": [
    {
      "id": 123,
      "experience": 36,
      "unit": "months",
      "level": "advanced"
    }
  ]
}
```

## Development

### Frontend Development

The React application is located in `web/modules/custom/skill_management/js/`:

```bash
cd web/modules/custom/skill_management/js
npm install
npm run build
```

### Clearing Cache

After making changes to PHP code or configuration:

```bash
vendor/bin/drush cr
```
