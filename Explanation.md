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
   
5. **Character Profile** (`templates/avatar-template.php`):
   - Displays a user's character profile
   - Implements access control for logged-in users only
   
6. **Custom Gutenberg Blocks** (`src/hero/`):
   - Hero block for creating engaging homepage layouts
   - Add separate links for logged in and logged out users
   - Utilizes WordPress block editor API for seamless integration
   
7. **API Integration** (`inc/API/RandomUserAPI.php`):
   - Interfaces with external API to generate random user data for character creation
   - Handles API response and formats data for use in the theme
   
8. **Security Measures**:
   - Implements nonce verification and data encryption for enhanced security
   - Input sanitization and validation to prevent security vulnerabilities
   
8. **Frontend Enhancements** (`assets/js/coolkidsnetwork-scripts.js`):
   - Implements interactive elements like sparkles effect
   - Handles form submissions and UI updates
   

## Technical Decisions and Rationale

1. **Use of Gutenberg Blocks**:
   - Decision: Develop custom Gutenberg blocks (e.g., Hero block)
   - Rationale: Provides a flexible, user-friendly content creation experience for admins while maintaining design consistency also to support future growth. Also to showcase my ability to work with Gutenberg blocks.
   
2. **AJAX for Form Submissions**:
   - Decision: Use AJAX for form submissions
   - Rationale: Enhances user experience by providing instant feedback and reducing page reloads. Also to showcase my ability to work with AJAX.
   
3. **Modular PHP Structure**:
   - Decision: Organize theme functionality into modular PHP classes
   - Rationale: it is a project requirement and also improves code organization, maintainability, and allows for easier unit testing
   
4. **RandomUserAPI Integration**:
   - Decision: Use external API for generating user details
   - Rationale: it is a project requirement and also provides diverse and realistic user data, enhancing the gaming experience. Also to showcase my ability to work with external APIs.
   
5. **Custom Page Templates**:
   - Decision: Create specific templates for key pages (e.g., dashboard, avatar, login, signup)
   - Rationale: Improves code organization, maintainability, and allows for tailored layouts and functionality for different sections of the site. Also to showcase my ability to work with page templates.
   
6. **Extensive Use of WordPress Hooks and Filters**:
   - Decision: Leverage WordPress's extensibility features
   - Rationale: Ensures flexibility, maintainability, and compatibility with WordPress ecosystem and allows for easy customization. Also to showcase my ability to work with WordPress hooks and filters. 
   
7. **Frontend JavaScript Enhancements**:
   - Decision: Implement interactive features using JavaScript
   - Rationale: Creates an engaging, interactive experience suitable for the target audience (children)

8. **Responsive Design**:
   - Decision: Ensure design is mobile-friendly and responsive
   - Rationale: Ensures accessibility across various devices, crucial for the target demographic
   
9. **Security Measures**:
   - Decision: Implement security best practices
   - Rationale: Protects user data and ensures a secure experience for the target audience
   

## Approach and Thought Process
1. **Understanding the Requirements**: 
   - Analyzed the user story to identify key features and potential challenges
   - Considered the target audience (children) and their specific needs

2. **Planning the Architecture**:
   - Decided on a modular approach to keep the code organized and maintainable
   - Chose to use a theme over a plugin to have more control over the site's appearance and functionality
   - Used the custom template and Ajax to showcase my ability to work with page templates and Ajax
   
3. **Prioritizing User Experience**:
   - Focused on creating a clean, engaging, and secure environment for children. using the sparkles effect to enhance the user experience and the rocket svg inspired from the wp media website to give a sense of speed and adventure.
   - Implemented features like AJAX form submissions and animations to enhance interactivity
   
4. **Ensuring Security and Data Protection**:
   - Used WordPress security best practices and added extra layers of protection (e.g., data encryption)
   - Sanitized user inputs to prevent security vulnerabilities

5. **Iterative Development**:
   - Started with core functionality and progressively added features
   - Conducted thorough testing at each stage to ensure reliability and address any issues early in the development process
   
6. **Code Quality and Best Practices**:
   - Followed best practices for PHP and JavaScript coding
   - Kept the code clean, readable, and well-documented
   - Added the unit test drafts but was not able to finish up on till due to time constraints
   - Set up CI/CD pipelines for automated testing and deployment to a hostinger setup https://coolkidsnetwork.stephenakinola.me
   - Used proper naming conventions and comments to enhance code readability and maintainability

## How to Use the API

The Cool Kids Network theme provides an API endpoint for changing user roles. This API can be accessed at:

https://coolkidsnetwork.stephenakinola.me/wp-json/cool-kids-network/v1/change-role

To use the API:

1. **Authentication**: 
   - The API requires authentication using an API key.
   - Use the API key: `8f7d9e2a3b1c5f4e6g8h7i9j0k1l2m3n`

2. **Endpoint**: POST to `/wp-json/cool-kids-network/v1/change-role`

3. **Headers**:
   - `Content-Type: application/json`
   - `X-API-Key: 8f7d9e2a3b1c5f4e6g8h7i9j0k1l2m3n`

4. **Request Body**:
   ```json
   {
     "user_identifier": "user@example.com",
     "new_role": "cooler_kid"
   }
   ```

   Note: `user_identifier` can be an email address, display name, or full name.

5. **Possible Roles**:
   - `cool_kid` (default)
   - `cooler_kid`
   - `coolest_kid`

6. **Response**:
   - Success (HTTP 200):
     ```json
     {
       "success": true,
       "message": "User role updated successfully",
       "user": {
         "id": 123,
         "username": "johndoe",
         "email": "john@example.com",
         "display_name": "John Doe",
         "old_role": "cool_kid",
         "new_role": "cooler_kid"
       }
     }
     ```
   - Error (HTTP 400, 401, or 404):
     ```json
     {
       "success": false,
       "message": "Error message describing the issue",
       "error_code": "error_code_string"
     }
     ```

7. **Example cURL Request**:
   ```bash
   curl -X POST 
     https://coolkidsnetwork.stephenakinola.me/wp-json/cool-kids-network/v1/change-role 
     -H 'Content-Type: application/json' 
     -H 'X-API-Key: 8f7d9e2a3b1c5f4e6g8h7i9j0k1l2m3n' 
     -d '{
       "user_identifier": "user@example.com",
       "new_role": "cooler_kid"
     }'
   ```

The API implementation can be found in `wp-content/themes/coolkidsnetwork/inc/API/RoleChangeApi.php`.

By approaching the problem methodically and keeping the end-users (both admins and children) in mind throughout the development process, the resulting theme provides a robust, secure, and engaging platform that meets and exceeds the initial requirements of the Cool Kids Network.