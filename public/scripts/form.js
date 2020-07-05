$(document).ready(function () {
    $('#modal-log-in form').submit(function (event) {
        var json;
        var login = $('#login').val();
        var password = $('#password').val();

        event.preventDefault();
        if (password === "" || login === "") {
            alert('Заполните все поля!');
        } else {
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: {login: login, password: password},
                success: function (data) {
                    json = jQuery.parseJSON(data);
                    if (json.isAuthorized) {
                        location.reload();
                    } else {
                        alert('Такого аккаунта не существует!');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(status);
                    console.log(error);
                },
                dataType: 'text'
            });
        }
    });

    $('#log-out').on('click', function () {
        event.preventDefault();
        $.ajax({
            type: 'post',
            url: '/logout',
            success: function (data) {
                json = jQuery.parseJSON(data);
                if (json.logout) {
                    location.reload();
                } else {
                    alert('Что-то пошло не так!');
                }
            },
            error: function (xhr, status, error) {
                console.log(status);
                console.log(error);
            },
            dataType: 'text'
        });
    });

    $('#modal-add-task form').submit(function (event) {
        var json;
        var name = $('#name').val();
        var email = $('#email').val();
        var text = $('#text').val();

        event.preventDefault();

        if (validateEmail(email) === false) {
            alert('Почта заполнена некорректно');
        } else if (name === "" || email === "" || text === "") {
            alert('Заполните все поля!')
        } else {
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: {name: name, email: email, text: text},
                success: function (data) {
                    json = jQuery.parseJSON(data);

                    if (json.isSuccess) {
                        location.reload();
                        alert('Задание успешно добавлено!');
                    } else {
                        alert('Ошибка добавления задания!');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(status);
                    console.log(error);
                },
                dataType: 'text'
            });
        }
    });

    $('.is-completed').on('click', function () {
        var id = $(this).data('id');
        var isCompleted = $(this).prop("checked");

        event.preventDefault();
        $.ajax({
            type: 'post',
            url: '/task/change',
            data: {id: id, isCompleted: isCompleted},
            success: function (data) {
                json = jQuery.parseJSON(data);
                if (json.status) {
                    location.reload();
                } else {
                    alert('Что-то пошло не так!');
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                console.log(status);
                console.log(error);
            },
            dataType: 'text'
        });
    })

    $('.text-form').on('change', function () {
        var id = $(this).data('id');
        var text = $(this).val();

        event.preventDefault();
        $.ajax({
            type: 'post',
            url: '/task/change',
            data: {id: id, text: text},
            success: function (data) {
                console.log(data);
                json = jQuery.parseJSON(data);
                if (json.status) {
                    alert('Сохранено');
                    location.reload();
                } else {
                    alert('Что-то пошло не так!');
                    location.reload();
                }
            },
            error: function (xhr, status, error) {
                console.log(status);
                console.log(error);
            },
            dataType: 'text'
        });
    })

    $('.columnSort').on('click', function () {
        if (localStorage.getItem('page') === null) {
            localStorage.setItem('page', 1)
        }

        var data = {
            'column' : $(this).data('column'),
            'typeSort' : $(this).data('sort'),
            'page' : localStorage.getItem('page'),
        }

        if (data.typeSort == 'DESC' && data.column == localStorage.getItem('column') && localStorage.getItem('sort') != 'ASC') {
            data.typeSort = 'ASC';
        } else {
            data.typeSort = 'DESC';
        }

        localStorage.setItem('column', data.column);
        localStorage.setItem('sort', data.typeSort);

        redirectPost('/', data);
    })

    $('.paginate').on('click', function () {
        if (localStorage.getItem('column') === null) {
            localStorage.setItem('column', 'id')
        }
        if (localStorage.getItem('sort') === null) {
            localStorage.setItem('sort', 'ASC')
        }

        var data = {
            'column' : localStorage.getItem('column'),
            'typeSort' : localStorage.getItem('sort'),
            'page' : $(this).html(),
        }
        localStorage.setItem('page', data.page);
        redirectPost('/', data);
    })

    $('#authorization').on('click', function () {
        $("#modal-log-in").fadeIn();
    });

    $('#close-modal-log-in').on('click', function () {
        $("#modal-log-in").fadeOut();
    });

    $('#add-task').on('click', function () {
        $("#modal-add-task").fadeIn();
    });

    $('#close-modal-add-task').on('click', function () {
        $("#modal-add-task").fadeOut();
    });
});

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function redirectPage(url, page, ) {
    var form = document.createElement('form');
    var input = document.createElement('input');

    document.body.appendChild(form);
    form.method = 'post';
    form.action = url;
    input.type = 'hidden';
    input.name = 'page';
    input.value = page;

    form.appendChild(input);
    form.submit();
}

function redirectPost(url, data) {
    var form = document.createElement('form');
    document.body.appendChild(form);
    form.method = 'post';
    form.action = url;

    for (var name in data) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = data[name];
        form.appendChild(input);
    }

    form.submit();
}