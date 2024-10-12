# Cool Kids Network WordPress Theme

## Overview
This WordPress theme is designed for the Cool Kids Network, a platform where children can create cartoon characters and engage in fun games. The theme provides a secure, user-friendly, and visually appealing environment tailored for young users.

## Key Features
- Custom user registration and login system
- Character creation functionality
- Role-based access control (Cool Kid, Cooler Kid, Coolest Kid)
- Dashboard to view other characters
- API endpoint for changing user roles
- Responsive and child-friendly design

## Live Demo
You can view the live site at: [https://coolkidsnetwork.stephenakinola.me](https://coolkidsnetwork.stephenakinola.me)

## Development

### Linter Setup
This project uses PHP_CodeSniffer for linting. The configuration can be found in `phpcs.xml.dist`. To run the linter:

```bash
composer run phpcs
```

### CI/CD
Continuous Integration and Deployment are set up using GitHub Actions. The workflow files can be found in the `.github/workflows` directory:

- `linter.yml`: Runs PHP_CodeSniffer on pull requests
- `deploy.yml`: Deploys the theme to the live site when changes are pushed to the `develop` branch

## API Usage
The theme includes an API endpoint for changing user roles. For detailed information on how to use the API, please refer to the [API Documentation](Explanation.md#how-to-use-the-api) section in the Explanation.md file.

## Installation
1. Clone this repository into your WordPress installation directory
2. Activate the theme through the WordPress admin panel
3. Configure the necessary pages (login, signup, dashboard, etc.) using the provided templates

## License
This project is licensed under the GPL-2.0 License - see the [LICENSE](LICENSE) file for details.
