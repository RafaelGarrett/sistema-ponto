<?php

namespace App;

enum UserRole: string
{
    case ADMIN = 'ADMIN';
    case GESTOR = 'GESTOR';
    case COLABORADOR = 'COLABORADOR';
}
