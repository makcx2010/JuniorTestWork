<?php
    if ($vars['isAuthorized'] === false) {
        echo '
        <a id="authorization" class="btn btn-primary" data-toggle="collapse" href="#authorization" role="button" aria-expanded="false" aria-controls="collapseExample" style="margin-right: 10px">
            Авторизация
        </a>';
    } else {
        echo '
        <a id="log-out" class="btn btn-danger" data-toggle="collapse" href="#log-out" role="button" aria-expanded="false" aria-controls="collapseExample" style="margin-right: 10px">
            Выйти
        </a>';
    }
?>
<a id="add-task" class="btn btn-success" data-toggle="collapse" href="#add-task" role="button" aria-expanded="false" aria-controls="collapseExample">
    Добавить задание
</a>
<table class="table">
    <thead>
    <tr>
        <th scope="col"><a data-column="id" data-sort="DESC" class="columnSort" onMouseOver="this.style.color='rgb(33,150,243)'" onMouseOut="this.style.color='#000'">ID</a></th>
        <th scope="col"><a data-column="user" data-sort="DESC" class="columnSort" onMouseOver="this.style.color='rgb(33,150,243)'" onMouseOut="this.style.color='#000'">Имя</a></th>
        <th scope="col"><a data-column="email" data-sort="DESC" class="columnSort" onMouseOver="this.style.color='rgb(33,150,243)'" onMouseOut="this.style.color='#000'">Почта</a></th>
        <th scope="col">Задание</th>
        <th scope="col"><a data-column="is_completed" data-sort="DESC" class="columnSort" onMouseOver="this.style.color='rgb(33,150,243)'" onMouseOut="this.style.color='#000'">Выполнено</a></th>
        <th scope="col">Отредактировано администратором</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $disabled = ($vars['user']['role'] == 'admin') ? '' : 'disabled';

    foreach ($vars['tasks'] as $columns) {
        $checkedComplete = ($columns['is_completed']) ? 'checked' : '';
        $checkedEdited = ($columns['is_admin_edited']) ? 'checked' : '';

        echo '
        <tr>
            <th scope="row">' . $columns['id'] . '</th>
            <td>' . $columns['user'] . '</td>
            <td>' . $columns['email'] . '</td>
            <td>
                <div class="form-group">
                    <textarea class="form-control text-form" data-id="' . $columns['id'] . '" rows="3"' . $disabled . '>' . $columns['text'] . '</textarea>
                </div>
            </td>
            <td> <input type="checkbox" class="form-check-input is-completed" data-id="' . $columns['id'] . '" ' .  $checkedComplete . ' ' . $disabled . ' style="margin-left: 2px"></td>
            <td> <input type="checkbox" class="form-check-input is-admin-edited" data-id="' . $columns['id'] . '" ' .  $checkedEdited . ' disabled style="margin-left: 2px"></td>
        </tr>';
    }

    ?>


    </tbody>
</table>
    <?php
        for($i = 1; $i <= $vars['countPages']; $i++) {
            echo '<button type="button" class="btn btn-danger paginate" style="margin-right: 8px">' . $i . '</button>';
        }
    ?>

<!-- Modals -->
<div id="modal-log-in" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="/login" method="post">
                <div class="input-group input-group-sm mb-3" style="outline: none">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Логин</span>
                    </div>
                    <input type="text" id="login" name="login" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" style="outline: none; box-shadow: none">
                </div>
                <div class="input-group input-group-sm mb-3" style="outline: none">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Пароль</span>
                    </div>
                    <input type="password"  id="password" name="password" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" style="outline: none; box-shadow: none">
                </div>

                <input type="submit" value="Войти" class="btn btn-primary" style="margin: 0 0 5px 5px;">

                <button id="close-modal-log-in" type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 10px;">
                    <span aria-hidden="true" style="color:red;">&times;</span>
                </button>
            </form>

        </div>
    </div>
</div>

<div id="modal-add-task" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="/add/task" method="post">
                <div class="input-group input-group-sm mb-3" style="outline: none">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Имя</span>
                    </div>
                    <input type="text" id="name" name="name" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" maxlength="31" style="outline: none; box-shadow: none">
                </div>
                <div class="input-group input-group-sm mb-3" style="outline: none">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Почта</span>
                    </div>
                    <input type="text"  id="email" name="email" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" maxlength="31" style="outline: none; box-shadow: none">
                </div>
                <div class="input-group input-group-sm mb-3" style="outline: none">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Задание</span>
                    </div>
                    <input type="text"  id="text" name="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" maxlength="255" style="outline: none; box-shadow: none">
                </div>

                <input type="submit" value="Добавить" class="btn btn-primary" style="margin: 0 0 5px 5px;">

                <button id="close-modal-add-task" type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 10px;">
                    <span aria-hidden="true" style="color:red;">&times;</span>
                </button>
            </form>

        </div>
    </div>
</div>

