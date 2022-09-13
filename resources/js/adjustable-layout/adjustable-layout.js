window.addEventListener('turbolinks:load', function () {
    const dragContainer = document.querySelector('.drag-container');
    const gridElement = document.querySelector('.grid');

    function initMuuri() {
        let grid = new Muuri(gridElement, {
            items: "*",
            showDuration: 400,
            showEasing: 'ease',
            hideDuration: 400,
            hideEasing: 'ease',
            layoutDuration: 400,
            layoutOnResize: true,
            dragEnabled: true,
            sortData: {
                index(item, element) {
                    console.log(element.getAttribute('data-indexxx'));

                    return parseInt(element.getAttribute('data-index')) || 1000;
                }
            },
            dragHandle: '.grid-card-handle',
            dragContainer: dragContainer,
            dragAxis: 'xy',
            dragSort: true,
            dragRelease: {
                duration: 800,
                easing: 'cubic-bezier(0.625, 0.225, 0.100, 0.890)',
                useDragContainer: true,
            },
            dragPlaceholder: {
                enabled: true,
                createElement(item) {
                    return item.getElement().cloneNode(true);
                },
            },
        }).on('move', function (data) {
            let items = [];

            grid.getItems().forEach( function (item, key) {
                const el = $(item.getElement());
                items.push({element_id: el.attr('id'), position: (1 + key)})
                el.attr('data-index', key);
            })

            $.ajax({
                url: routes.route('adminarea.update-layout'),
                data: {items: items},
                headers: { 'X-CSRF-TOKEN' : window.Laravel.csrfToken },
                error: function() {
                },
                success: function(data) {
                },
                type: 'POST'
            });
        })

        return grid;
    }

    // load layout position memory from database
    $.ajax({
        url: routes.route('adminarea.layout-memory'),
        error: function() {
        },
        success: function(data) {
            $.each(data.items, function (elId, key) {
                console.log(elId, key);
                $('#'+elId).attr('data-index', key);
            })
            let grid = initMuuri();

            grid.sort('index');
        },
        type: 'GET'
    });
})
