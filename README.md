# Localhost Admin

_Een minimale PHP-tool om eenvoudig nieuwe Composer-projecten aan te maken via je browser op je lokale machine._

## 🔍 Over dit project

**Localhost Admin** is een sessieloze, database-loze PHP frontend voor het starten van nieuwe Composer-projecten, zoals:

- Symfony 7
- Drupal
- WordPress
- (of wat je zelf maar toevoegt)

⚠️ Momenteel is **alleen 'empty-directory'** functioneel geïmplementeerd. De overige types zijn placeholders voor toekomstige integratie.

Het draait lokaal op je PHP-server en geeft je een simpel overzicht van beschikbare installatiemogelijkheden via Composer.

## ⚙️ Systeemvereisten

- PHP **8.4** of nieuwer
- Composer (globaal geïnstalleerd)
- Webserver (bijv. PHP’s built-in server, Apache/Nginx, of bij voorkeur DDEV)

## 🚀 Installatie

### Met DDEV (aanbevolen)

1. Installeer [DDEV](https://ddev.readthedocs.io/en/stable/)
2. Clone deze repository:

```bash
git clone https://github.com/nsmeele/localhost-admin.git
cd localhost-admin
ddev config --project-type=php --docroot=. --create-docroot
ddev start && ddev launch
```

### Alternatief: PHP built-in server

```bash
git clone https://github.com/nsmeele/localhost-admin.git
cd localhost-admin
php -S localhost:8000
```

Open in je browser: [http://localhost:8000](http://localhost:8000)

## 🛠 Gebruik

1. Selecteer een projecttype (bijvoorbeeld Symfony).
2. Geef de projectnaam op.
3. De tool voert `composer create-project` uit in de juiste map.
4. Klaar. Open de map in je IDE of browser.

Momenteel is dit een statische tool zonder login, database of sessies. Eenvoud voorop.

## 📦 Ondersteunde projecttypes

De volgende projecttypes worden getoond in de interface:

- ✅ Empty directory *(functioneel)*
- 🚧 Empty file *(nog niet geïmplementeerd)*
- 🚧 Symfony 6 + ViteJS + TailwindCSS
- 🚧 Symfony 7 + ViteJS + TailwindCSS
- 🚧 Symfony LTS + ViteJS + TailwindCSS
- 🚧 NextJS + TailwindCSS
- 🚧 ReactJS + TailwindCSS
- 🚧 Drupal
- 🚧 Drupal CMS
- 🚧 WordPress

Onder de motorkap gedefinieerd via een `ProjectType` enum.

## 📄 Licentie

MIT-licentie – gebruik het, breek het, leer ervan.

## 🙋‍♂️ Waarom?

Omdat `localhost` vaak een rommeltje wordt. Deze tool is een poging tot wat orde in de chaos – niets meer, niets minder.
