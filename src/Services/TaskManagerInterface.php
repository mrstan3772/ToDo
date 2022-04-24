<?php

namespace App\Service;

use App\Entity\Task;
use Exception;

interface TaskManagerInterface
{
    /**
     * Création d'une tâche
     * @throws Exception
     */
    public function createTask(Task $task): void;

    /**
     * Modification du statut terminée
     * @throws Exception
     */
    public function toggleTask(Task $task): void;
}
