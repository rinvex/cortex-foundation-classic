window.addEventListener('turbolinks:load', function () {
    const dragContainer = document.querySelector('.drag-container');
    const gridElement = document.querySelector('.grid');
    const grid2Element = document.querySelector('.grid-2');
    const filterField = document.querySelector('.grid-control-field.filter-field');
    const searchField = document.querySelector('.grid-control-field.search-field');
    const sortField = document.querySelector('.grid-control-field.sort-field');
    const layoutField = document.querySelector('.grid-control-field.layout-field');
    let disableItems = [];
    let enableItems = [];
    let sortFieldValue;
    let searchFieldValue;
    initResizeElement();

    $("#grid-items").children().each( function (key, item) {
        item = $(item);
        if (item.attr('data-enable') != 1) {
            disableItems.push(item.get(0));
        }
        else {
            enableItems.push(item.get(0));
        }
    });

    let grid2 = new Muuri(grid2Element, {
        items: disableItems,
        dragEnabled: true,
        showEasing: 'ease',
        hideDuration: 400,
        hideEasing: 'ease',
        layoutDuration: 400,
        dragHandle: '.grid-card-handle',
        dragContainer: dragContainer,
        dragSort: function () {
            return [grid, grid2]
        }
    })
        .on('layoutStart', function (items) {
            let maxWidth = $('.grid-2').width() - 20;
            items.forEach(function (item, key) {
                $(item.getElement()).find('.panel-body').addClass("narrow-layout");
                item.getElement().style.width = maxWidth + 'px';
                item.getElement().style.height = '40px';
                grid2.refreshItems([item]).layout();
            })
        })
        .on('receive', function (data) {
            disableItem(data.item);
        });

    function disableItem(item) {
        let maxWidth = $('.grid-2').width() - 20;
        let el = item.getElement();
        el.style.width = maxWidth + 'px';
        el.style.height = '40px';
        $(el).find('.panel-body').addClass("narrow-layout");
        $(el).attr('data-enable', 0);
        grid2.refreshItems([item]).layout();
        updateItems([item]);


    }

    let grid = new Muuri(gridElement, {
        items: enableItems,
        showDuration: 400,
        showEasing: 'ease',
        hideDuration: 400,
        hideEasing: 'ease',
        layoutDuration: 400,
        layoutOnResize: true,
        dragEnabled: true,
        sortData: {
            index(item, element) {
                return parseInt(element.getAttribute('data-index')) || 1000;
            },
            title(item, element) {
                return element.getAttribute('data-title')
            },
            color(item, element) {
                return element.getAttribute('data-title')
            },
        },
        dragHandle: '.grid-card-handle',
        dragContainer: dragContainer,
        dragSort: function () {
            return [grid, grid2]
        },
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
        dragStartPredicate: function (item) {
            // For other items use the default drag start predicate.
            const el = item.getElement();

            return parseInt(el.getAttribute('sortable')) == 1? true : false;
        }
    })
        .on('move', function (data) {
            updateItems(grid.getItems());
        })
        .on('receive', function (data) {
            enableItem(data.item);
        });

    function enableItem(item) {
        let el = item.getElement();
        el.style.width = $(el).data('width') + 'px';
        el.style.height = $(el).data('height') + 'px';
        $(el).find('.panel-body').removeClass("narrow-layout");
        $(el).attr('data-enable', 1);
        grid.refreshItems([item]).layout();

        updateItems([item]);
    }

    grid.sort('index');

    function initResizeElement() {
        var popups = $('.grid-item');
        var element = null;
        var startX, startY, startWidth, startHeight;

        $.each(popups, function (key, p) {
            var right = document.createElement("div");
            right.className = "resizer-right";
            p.appendChild(right);
            right.addEventListener("mousedown", initDrag, false);
            right.parentPopup = p;

            var bottom = document.createElement("div");
            bottom.className = "resizer-bottom";
            p.appendChild(bottom);
            bottom.addEventListener("mousedown", initDrag, false);
            bottom.parentPopup = p;

            var both = document.createElement("div");
            both.className = "resizer-both";
            p.appendChild(both);
            both.addEventListener("mousedown", initDrag, false);
            both.parentPopup = p;
        })

        function initDrag(e) {
            element = this.parentPopup;
            startX = e.clientX;
            startY = e.clientY;
            startWidth = parseInt( document.defaultView.getComputedStyle(element).width, 10 );
            startHeight = parseInt( document.defaultView.getComputedStyle(element).height, 10 );
            document.documentElement.addEventListener("mousemove", doDrag, false);
            document.documentElement.addEventListener("mouseup", stopDrag, false);
        }

        function doDrag(e) {
            gridElement.classList.remove('drag-enabled');
            element.style.width = startWidth + e.clientX - startX + "px";
            element.style.height = startHeight + e.clientY - startY + "px";
        }

        function stopDrag(e) {
            document.documentElement.removeEventListener("mousemove", doDrag, false);
            document.documentElement.removeEventListener("mouseup", stopDrag, false);
            grid.refreshItems();
            grid.layout();
            gridElement.classList.add('drag-enabled');
            updateItems(grid.getItems());
        }
    }

    function updateItems(data) {
        let items = [];
        data.forEach( function (item, key) {
            const el = $(item.getElement());
            let is_enable = parseInt(el.attr('data-enable'));
            let width = item.getWidth();
            let height = item.getHeight();

            if (is_enable == 0) {
                width = el.data('width');
                height = el.data('height');
            }
            items.push({
                element_id: el.attr('id'),
                data: {
                    position: (1 + key),
                    width: width,
                    height: height,
                    is_enable: is_enable
                }
            })
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
    }

    function filter(onFinish = null) {
        const filterFieldValue = filterField.value;
        grid.filter(
            (item) => {
                const element = item.getElement();
                const isSearchMatch =
                    !searchFieldValue ||
                    (element.getAttribute('data-title') || '').toLowerCase().indexOf(searchFieldValue) > -1;
                const isFilterMatch =
                    !filterFieldValue || filterFieldValue === element.getAttribute('data-color') || filterFieldValue === 'all';
                return isSearchMatch && isFilterMatch;
            },
            { onFinish: onFinish }
        );
    }

    function sort() {
        var currentSort = sortField.value;
        if (sortFieldValue === currentSort) return;
        // Sort the items.
        grid.sort(
            currentSort === 'title' ? 'title' : 'index'
        );
        // Update active sort value.
        sortFieldValue = currentSort;
        grid.refreshItems().layout();
    }

    $("#layout-field").on('change', function (e) {
        const { layout } = grid._settings;
        const val = $(this).find(":selected").val();
        layout.alignRight = val.indexOf('right') > -1;
        layout.alignBottom = val.indexOf('bottom') > -1;
        layout.fillGaps = val.indexOf('fillgaps') > -1;
        grid.layout();
    });

    $('.sort-field').on('change', function (e) {
        sort();
    });

    $(".filter-field").on('change', function (e) {
        filter();
    })

    function initGridOptions() {
        // Reset field values.
        searchField.value = '';
        [sortField, filterField, layoutField].forEach((field) => {
            field.value = field.querySelectorAll('option')[0].value;
        });

        // Set inital search query, active filter, active sort value and active layout.
        searchFieldValue = searchField.value.toLowerCase();
        sortFieldValue = sortField.value;

        // Search field binding.
        searchField.addEventListener('keyup', function () {
            var newSearch = searchField.value.toLowerCase();
            if (searchFieldValue !== newSearch) {
                searchFieldValue = newSearch;
                filter();
            }
        });

        // Filter, sort and layout bindings.
        filterField.addEventListener('change', filter);
        sortField.addEventListener('change', sort);
    }

    initGridOptions();
})
