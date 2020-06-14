module.exports = {
    scanForCssSelectors: [],
    whitelistPatterns: [],
    webpackPlugins: [],
    install: [],
    copy: [
        {from: 'app/cortex/foundation/resources/images/', to: 'public/images/'},
        {from: 'node_modules/bootstrap-sass/assets/fonts/bootstrap/', to: 'public/fonts/bootstrap/'},
        {from: 'node_modules/font-awesome/fonts/', to: 'public/fonts/fontawesome/'},
        {from: 'node_modules/tinymce/skins/', to: 'public/tinymce/'},
        {from: 'node_modules/intl-tel-input/build/img/flags.png', to: 'public/images/'},
        {from: 'node_modules/intl-tel-input/build/img/flags@2x.png', to: 'public/images/'},
    ],
    mix: {
        css: [
            {input: 'app/cortex/foundation/resources/sass/theme-frontarea.scss', output: 'public/css/theme-frontarea.css'},
            {input: 'app/cortex/foundation/resources/sass/theme-adminarea.scss', output: 'public/css/theme-adminarea.css'},
            {input: 'app/cortex/foundation/resources/sass/theme-tenantarea.scss', output: 'public/css/theme-tenantarea.css'},
            {input: 'app/cortex/foundation/resources/sass/theme-managerarea.scss', output: 'public/css/theme-managerarea.css'},
        ],
        js: [],
    },
};
