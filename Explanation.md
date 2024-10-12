# Cool Kids Network Theme Documentation
## Problem Statement
The Cool Kids Network is a platform designed for children to create cartoon characters and engage in fun games with other kids. The primary challenge was to develop a WordPress website that provides a secure, user-friendly, and visually appealing environment for young users. The website needed to incorporate features such as user registration, login functionality, character creation with special roles, ability to assign a special role to a user through and API and a dashboard to view other characters.

We chose to implement this as a WordPress theme rather than a plugin for several reasons:

1. Deep integration with site appearance: A theme allows for seamless integration of custom functionality with the site's visual design, ensuring a cohesive user experience.

2. Full control over page templates: As a theme, we can create custom page templates for character creation, user profiles, and other specific features, which requires less code compared to a plugin approach.

3. Easier customization of core WordPress elements: A theme provides direct access to modify header, footer, and other structural elements of the site, which is crucial for creating a child-friendly interface.

4. Performance optimization: By integrating functionality directly into the theme, we can optimize performance by reducing the need for additional plugin hooks and filters.

5. Simplified maintenance: Having all custom functionality within the theme simplifies updates and maintenance, as opposed to managing separate theme and plugin codebases.

## Technical Specification

### Theme Structure

The Cool Kids Network theme is built using a modular approach, leveraging WordPress's block editor (Gutenberg), shortcodes and custom templates for content creation. The theme is structured as follows:

1. **Core Theme Files**: 
   - `style.css`: Main stylesheet for the theme
   
   - `functions.php`: Core functionality and hooks
   
   - `index.php`, `header.php`, `footer.php`, `page.php`: Basic template files.
   
2. **Custom Blocks**:
   - `wp-content/themes/coolkidsnetwork/src/hero/`: Custom Hero block for the homepage.
   
3. **Templates**:
   - `wp-content/themes/coolkidsnetwork/templates/`: Custom page templates which includes `login.php`, `signup.php`, `avatar-template.php` and `dashboard-template.php`.
   
4. **PHP Classes**:
   - `wp-content/themes/coolkidsnetwork/inc/`: Core PHP classes for theme functionality.
   
5. **Assets**:
   - `wp-content/themes/coolkidsnetwork/assets/`: JavaScript, CSS, and image files.

### Key Components

1. **Registration System** (`inc/Features/Registration.php`):
   - Handles user registration using AJAX
   - Includes features for user role assignment based on user input.
   - Integrates with RandomUserAPI for generating user details
   - Implements security measures like nonce verification and data encryption
   
2. **Login System** (`inc/Features/Login.php`):
   - Manages user authentication
   - Uses AJAX for a smooth login experience
   
3. **Character Creation** (`inc/Features/CharacterCreation.php`):
   - Allows users to create and customize their cartoon characters
   - Stores character data in user meta
   
4. **Dashboard** (`templates/dashboard-template.php`):
   - Displays other users' characters
   - Implements access control for logged-in users only
   - Cooler Kid role sees user name and country
   - Coolest Kid role sees all details including email and role