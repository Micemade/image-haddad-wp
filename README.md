# Gulp Workflow for WPPB!

This is a simple workflow to help developing with the WordPress Plugin Boilerplate <https://wppb.me/>. This is nothing fancy.
![Image description](https://wppb.me/wp-content/themes/petite-blanc/dist/images/idea@2x.png)

## Quick Note:
I currently only need CSS to be all juiced up. Moving on to JS will be coming shortly.

## Installation
```bash
cd into/your/wppb/directory
```

```bash
git clone https://gitlab.com/alexanderwinstoncoleman/gulp-workflow-for-wordpress-plugin-boilerplate.git
```

```bash
npm install
```
## First Run:
```bash
gulp startUp
```
This will create some ```src``` folders, then move the ```.css``` and ```.js``` into ```src``` and remove them from the initial folder:

```bash
public/css/src/
public/js/src/
admin/css/src/
admin/js/src/
```

## How it is setup

The CSS files in the ```src``` folder above will be automagically compressed, prefixed, spit-shined, and placed in the parent directory.

## Currently Available Tasks:

Only admin CSS
```bash
gulp adminCss
```
Only public CSS
```bash
gulp publicCss
```
Both public and admin CSS
```bash
gulp styles
```
As described above
```bash
gulp startUp
```

watch both public and admin CSS
```bash
gulp watchCSS
```

## TODO:

1. ~~On startUp: copy file from parent to ```src``` folder, remove original file.~~
2. Get JS linting/transpiling working.
3. Get a production zip built and which will removed all ```src``` files, and create a zip that can be dropped into ```wp-content/plugins/```.
