function renderPagination() {
    let paginationHtml = '';

    if (currentPage > 1) {
        paginationHtml +=
            `<button class="paginate-btn prev-btn btn btn-sm btn-outline-primary mx-1" data-page="${currentPage - 1}"><i class="fa fa-circle-chevron-left"></i></button>`;
    }

    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPage, currentPage + 2);

    if (startPage > 1) {
        paginationHtml += `<button class="paginate-btn page-btn btn btn-sm btn-primary" data-page="1">1</button>`;
        if (startPage > 2) {
            paginationHtml +=
                `<button class="btn btn-sm btn-primary" style="pointer-events: none;"><i class="fa fa-ellipsis"></i></button>`;
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        paginationHtml +=
            `<button class="paginate-btn page-btn btn btn-sm btn-primary ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }

    if (endPage < totalPage) {
        if (endPage < totalPage - 1) {
            paginationHtml +=
                `<button class="btn btn-sm btn-primary" style="pointer-events: none;"><i class="fa fa-ellipsis"></i></button>`;
        }
        paginationHtml +=
            `<button class="paginate-btn page-btn btn btn-sm btn-primary" data-page="${totalPage}">${totalPage}</button>`;
    }

    if (currentPage < totalPage) {
        paginationHtml +=
            `<button class="paginate-btn next-btn btn btn-sm btn-outline-primary mx-1" data-page="${currentPage + 1}"><i class="fa fa-circle-chevron-right"></i></button>`;
    }

    $('#pagination-js').html(paginationHtml);

    $('#pagination-js').off('click', '.paginate-btn').on('click', '.paginate-btn', async function(e) {
        const newPage = parseInt($(this).data('page'));
        if (!isNaN(newPage)) {
            currentPage = newPage;
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                customFilter);
        }
    });
}

async function searchList() {
    $('#limitPage').on('change', async function() {
        defaultLimitPage = parseInt($(this).val());
        currentPage = 1;
        await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
            customFilter);
    });

    $('.tb-search').on('input', debounce(async () => {
        defaultSearch = $('.tb-search').val();
        currentPage = 1;
        await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
            customFilter);
    }, 500));
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
