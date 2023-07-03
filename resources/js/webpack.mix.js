module.exports = {
    scanForCssSelectors: [],
    webpackPlugins: [],
    safelist: [],
    install: [],
    copy: [
        {from: 'app/modules/cortex/foundation/resources/images/', to: 'public/images/'},
        {from: 'node_modules/tinymce/plugins', to: 'public/tinymce/plugins/'},
        {from: 'node_modules/tinymce/skins/', to: 'public/tinymce/skins/'},
    ],
    mix: {
        css: [
            {input: 'app/modules/cortex/foundation/resources/sass/theme-frontarea.scss', output: 'public/css/theme-frontarea.css'},
            {input: 'app/modules/cortex/foundation/resources/sass/theme-adminarea.scss', output: 'public/css/theme-adminarea.css'},
        ],
        js: [],
    },
};
