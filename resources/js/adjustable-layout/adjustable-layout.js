window.addEventListener('turbolinks:load', function () {

    const dragContainer = document.querySelector('.drag-container');
    const gridElement = document.querySelector('.grid');

    window.grid = new Muuri(gridElement, {
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
                return element.getAttribute('data-index') || '';
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
        dragAutoScroll: {
            targets: [window],
            sortDuringScroll: false,
            syncAfterScroll: false,
        },
    }).on('move', function (data) {
        let items = [];
        let adjusted_el = window.grid.getItem(data.fromIndex).getElement();
        let dragged_el = window.grid.getItem(data.toIndex).getElement();

        items.push({element_id: $(adjusted_el).attr('id'), position: data.fromIndex})
        items.push({element_id: $(dragged_el).attr('id'), position: data.toIndex})

        $(dragged_el).attr('data-index', data.toIndex);
        $(adjusted_el).attr('data-index', data.fromIndex);

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

    // Sort the items.
    window.grid.sort(
        'index'
    );
})
