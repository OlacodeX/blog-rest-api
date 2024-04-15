<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'Admin';
    case VISITOR = 'Visitor';

    public function title(): string
    {
        return match($this){
            self::ADMIN => 'Admin',
            self::VISITOR => 'Visitor',
        };
    }
}
