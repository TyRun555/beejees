/**
 * Bootstrap валидация
 */

(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()

/**
 * Уведомления
 */
var toastElList = [].slice.call(document.querySelectorAll('.toast'))
var toastList = toastElList.map(function (toastEl) {
    let toast = new bootstrap.Toast(toastEl, {
        autohide: true,
        animation: true,
        delay: 3000
    });
    toast.show();
})

/**
 * Сортировка таблиц
 *
 * Используется для превращения заголовков таблиц триггеры сортировки
 *
 * TODO В дальнейшем лучше вынести в отдельный компонент
 */
const sortable = document.getElementsByClassName('sortable');
if (sortable && sortable.length) {
    [...sortable].forEach(function (element) {
        let url = new URL(location.href);
        let sort = url.searchParams.get('sort');
        if (sort) {
            let sorted = document.querySelector('[data-sort-param="' + sort.replace('-', '') + '"');
            if (sorted) {
                sorted.dataset.sortDirection = sort.substring(0, 1) === '-' ? '' : '-';
                sorted.dataset.sorted = true;
                console.log(sort);
                console.log(element.dataset.sortDirection);
                console.log(sort.replace(element.dataset.sortDirection, ''));

            }
        }
        element.addEventListener('click', function (e) {
            let sortable = new Sortable(element);
            sortable.sort()
        })
    })
}

class Sortable {
    element;
    direction;
    param;

    constructor(element) {
        this.element = element;
        this.direction = element.dataset.sortDirection || '';
        this.param = element.dataset.sortParam;
    }

    sort() {
        let url = new URL(location.href);
        url.searchParams.set('sort', this.direction + this.param);
        location.href = url.toString();
    }
}