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

var toastElList = [].slice.call(document.querySelectorAll('.toast'))
var toastList = toastElList.map(function (toastEl) {
    let toast = new bootstrap.Toast(toastEl, {
        autohide: true,
        animation: true,
        delay: 3000
    });
    toast.show();
})

const sortable = document.getElementsByClassName('sortable');
if (sortable && sortable.length) {
    [...sortable].forEach(function (element) {
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
        this.direction = element.getDataAttribute('sort-direction');
        this.param = element.getDataAttribute('sort-param');
    }
    sort()
    {
        let url = new URL(location.href);
        url.searchParams.append('sort', this.direction+this.param);
        location.href = url.toString();
    }
}