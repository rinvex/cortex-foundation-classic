module.exports = {
    scanForCssSelectors: [],
    webpackPlugins: [],
    safelist: [],
    install: [
        "highcharts@^10.2.1",
    ],
    copy: [
        {from: 'app/cortex/foundation/resources/images/', to: 'public/images/'},
        {from: 'node_modules/tinymce/plugins', to: 'public/tinymce/plugins/'},
        {from: 'node_modules/tinymce/skins/', to: 'public/tinymce/skins/'},
        {from: 'node_modules/highcharts/highcharts.js', to: 'public/js/highcharts.js'},
    ],
    mix: {
        css: [
            {input: 'app/cortex/foundation/resources/sass/theme-frontarea.scss', output: 'public/css/theme-frontarea.css'},
            {input: 'app/cortex/foundation/resources/sass/theme-adminarea.scss', output: 'public/css/theme-adminarea.css'},
            {input: 'app/cortex/foundation/resources/sass/theme-tenantarea.scss', output: 'public/css/theme-tenantarea.css'},
            {input: 'app/cortex/foundation/resources/sass/theme-managerarea.scss', output: 'public/css/theme-managerarea.css'},
        ],
        js: [
            {input: 'app/cortex/foundation/resources/js/highcharts/datatable-highcharts.js', output: 'public/js/datatable-highcharts.js'},
        ],
    },
};
