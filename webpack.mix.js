const mix = require("laravel-mix");
const fs = require("fs");
const wpPot = require("wp-pot");

mix.options({
    autoprefixer: {
        remove: false,
    },
    processCssUrls: false,
    terser: {
        terserOptions: {
            keep_fnames: true,
        },
    },
});

mix.webpackConfig({
    target: "web",
    externals: {
        jquery: "window.jQuery",
        $: "window.jQuery",
        wp: "window.wp",
        _jltwp_spotlight: "window.jltwp_spotlight",
    },
});



// Disable notification on dev mode
if (process.env.NODE_ENV.trim() !== "production") mix.disableNotifications();


mix.sourceMaps(false, "source-map");
if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'inline-source-map'
    })
}

if (process.env.NODE_ENV.trim() === "production") {
    // Language pot file generator
    wpPot({
        destFile: "languages/wp-spotlight.pot",
        domain: "wp-spotlight",
        package: "WP Spotlight",
        src: "**/*.php",
    });
}

// SCSS to CSS
// mix.sass("dev/scss/wp-spotlight.scss", "assets/css/wp-spotlight.css");
// mix.sass("dev/scss/sdk.scss", "assets/css/wp-spotlight.min.css");
mix.sass("dev/scss/sdk.scss", "assets/css/wp-spotlight.css")
    .minify('assets/css/wp-spotlight.css');

mix.sass("dev/js/index.scss", "assets/css/wp-spotlight-form.css")
    .minify('assets/css/wp-spotlight-form.css');
// mix.sass("dev/scss/survey.scss", "assets/css/wp-spotlight-survey.css");

// mix.sass("dev/scss/admin-settings.scss", "assets/css/wp-spotlight-admin-settings.min.css");
// mix.sass("dev/scss/premium/wp-spotlight-pro-styles.scss", "Pro/assets/css/wp-spotlight-pro.min.css");

// Scripts to js - regular
// mix.scripts( 'dev/js/wp-spotlight.js', 'assets/js/wp-spotlight.js' );
mix.js("dev/js/index.js", "assets/js/wp-spotlight.js")
    .react()
    .minify('assets/js/wp-spotlight.js');
// mix.scripts("dev/js/wp-spotlight.js", "assets/js/");

// Third Party Plugins Support
// fs.readdirSync('dev/scss/plugins').forEach(
//     file => {
//         mix.sass('dev/scss/plugins/' + file, 'assets/css/plugins/' + file.substring(1).replace('.scss', '.min.css'));
//     }
// );

// fs.readdirSync('dev/scss/premium/plugins/').forEach(
//     file => {
//         mix.sass('dev/scss/premium/plugins/' + file, 'Pro/assets/css/plugins/' + file.substring(1).replace('.scss', '.min.css'));
//     }
// );
