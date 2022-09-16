window.addEventListener('turbolinks:load', function () {
    const dragContainer = document.querySelector('.drag-container');
    const gridElement = document.querySelector('.grid');
    initResizeElement();
    let layoutEditing = false;

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
                return parseInt(element.getAttribute('data-index')) || 1000;
            }
        },
        dragHandle: '.grid-card-handle',
        dragContainer: dragContainer,
        dragSort: true,
        dragSortPredicate:{
            action: "swap",
            migrateAction: 'swap',
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
        .on('layoutStart', function (items) {
            $.each(items, function (key, item) {
                let el = $(item.getElement());
                if (el.attr('data-enable') != 1) {
                    el.addClass('tile-disabled');
                    if ( !layoutEditing ) {
                        grid.hide([item]);
                    }
                }
            })
        });

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
            items.push({
                element_id: el.attr('id'),
                data: {
                    position: (1 + key),
                    width: item.getWidth(),
                    height: item.getHeight(),
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

    $(document).on('click', '.disable-tile', function (e) {
        let item = grid.getItems()[parseInt($(this).attr('data-index')) - 1];
        let el = $(item.getElement());
        el.attr('data-enable', 0);
        el.addClass('tile-disabled');
        initEditing();
        updateItems([item]);
    });

    $(document).on('click','.enable-tile', function (e) {
        let item = grid.getItems()[parseInt($(this).attr('data-index')) - 1];
        let el = $(item.getElement());
        el.attr('data-enable', 1);
        el.removeClass('tile-disabled');
        initEditing();
        updateItems([item]);
    });

    $('#grid-edit').on('click', function (e) {

        if (!layoutEditing) {
            layoutEditing = true;
            $(this).removeClass('btn-primary')
            $(this).addClass('btn-default')
            $(this).html(Lang.get('cortex/foundation::common.close'));
            initEditing();
        }
        else {
            layoutEditing = false;
            $(this).removeClass('btn-default')
            $(this).addClass('btn-primary')
            destroyEditing()
        }
    });

    function destroyEditing() {
        $('.hover-tile-btn').remove();
        $('#grid-edit').html(Lang.get('cortex/foundation::common.layout_edit'));
        grid.layout();
    }

    function initEditing() {
        $('.hover-tile-btn').remove();
        let items = grid.getItems();
        grid.show(items);
        let disableBtn = `<button class="btn btn-danger btn-sm disable-tile hover-tile-btn">
                                            <span class="fa fa-minus fa-lg"></span>
                                        </button>`;
        let enableBtn = `<button class="btn btn-success btn-sm hover-tile-btn enable-tile">
                                        <span class="fa fa-plus fa-lg"></span>
                                    </button>`;
        $.each(items, function (k, item) {
            let el = $(item.getElement());
            if (el.attr('data-enable') == 1) {
                let btn = $(disableBtn).attr('data-index', k + 1);
                el.prepend(disableBtn, btn.get());
            }
            else {
                let btn = $(enableBtn).attr('data-index', k + 1);
                el.prepend(btn);
            }
        })
    }
})
