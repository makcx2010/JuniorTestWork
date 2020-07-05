<?php

namespace application\models;

use application\core\Model;

class Main extends Model
{
    const LIMIT_TASKS = 3;

    public function getTasks($pageNumber, $columnSort, $sort)
    {
        $vars['sort'] = $sort;
        $vars['columnSort'] = $columnSort;

        $offset = 3 * ($pageNumber - 1);
        $result = $this->db->fetchAll('SELECT * FROM tasks ORDER BY	' . $columnSort . ' ' . $sort . ' LIMIT ' . self::LIMIT_TASKS . ' OFFSET ' . $offset, $vars);

        return $result;
    }

    public function getCountTasks()
    {
        $result = $this->db->rowCount('SELECT * FROM tasks');

        return $result;
    }

    public function insertTask($vars)
    {
        $isSuccess = $this->db->query('INSERT INTO `tasks`(`id`, `user`, `email`, `text`, `is_completed`) VALUES (null, :name, :email, :text, 0)', $vars);

        return $isSuccess;
    }

    public function getUsers()
    {
        $result = $this->db->fetchAll('SELECT * FROM users');

        return $result;
    }

    public function changeStatusTask($vars)
    {
        $isSuccess = $this->db->query('UPDATE `tasks` SET `is_completed` = :isCompleted WHERE id = :id', $vars);

        return $isSuccess;
    }

    public function changeTextTask($vars)
    {
        $isSuccess = $this->db->query('UPDATE `tasks` SET `text` = :text, `is_admin_edited` = :isAdminEdited WHERE id = :id', $vars);

        return $isSuccess;
    }

}