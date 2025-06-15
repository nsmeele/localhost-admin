# Localhost Admin

_A minimal PHP tool to quickly scaffold new Composer-based projects via your browser on your local machine._

## 🔍 About this project

**Localhost Admin** is a stateless PHP frontend for bootstrapping new Composer projects like:

- Symfony 7
- Drupal
- WordPress
- (or anything else you add yourself)

⚠️ Currently, only **'empty-directory'** is functionally implemented. Other project types are placeholders for future integration.

It runs locally on your PHP server and provides a simple UI to select and generate projects.

## ⚙️ Requirements

- PHP **8.4** or higher
- Composer (installed globally)
- Web server (e.g. PHP built-in server, Apache/Nginx, or preferably DDEV)

## 🚀 Installation

### Using DDEV (recommended)

1. Install [DDEV](https://ddev.readthedocs.io/en/stable/)
2. Clone this repository:

```bash
git clone https://github.com/nsmeele/localhost-admin.git
cd localhost-admin
ddev start && ddev composer install && ddev launch
```

### Alternative: PHP built-in server

```bash
git clone https://github.com/nsmeele/localhost-admin.git
cd localhost-admin
composer install
php -S localhost:8000
```

Open your browser at: [http://localhost:8000](http://localhost:8000)

## 🛠 Usage

1. Select a project type (e.g. Symfony).
2. Provide a project name.
3. The tool will run `composer create-project` in the designated folder.
4. Done. Open the folder in your IDE or browser.

The tool is currently fully static — no login, no session, no database. Simplicity first.

## 📦 Supported Project Types

These project types are selectable in the UI:

- ✅ Empty directory *(functional)*
- 🚧 Empty file *(not yet implemented)*
- 🚧 Symfony 6 + ViteJS + TailwindCSS
- 🚧 Symfony 7 + ViteJS + TailwindCSS
- 🚧 Symfony LTS + ViteJS + TailwindCSS
- 🚧 NextJS + TailwindCSS
- 🚧 ReactJS + TailwindCSS
- 🚧 Drupal
- 🚧 Drupal CMS
- 🚧 WordPress

Internally defined via a `ProjectType` enum.

## 📄 License

MIT License – use it, break it, learn from it.

## 🙋‍♂️ Why?

Because `localhost` tends to become a mess. This tool is a lightweight attempt at restoring order — nothing more, nothing less.
