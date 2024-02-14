<?php

namespace App\Enums;

enum PermissionEnum: string {
    case ACCESS_USERS = "access users";
    case CREATE_USERS = "create users";
    case EDIT_USERS = "edit users";
    case DELETE_USERS = "delete users";
    case ACCESS_ALL_REPORTS = "access all reports";
    case ACCESS_OWN_REPORTS = "access own reports";
    case CREATE_REPORTS = "create reports";
    case EDIT_ALL_REPORTS = "edit all reports";
    case EDIT_OWN_REPORTS = "edit own reports";
    case DELETE_ALL_REPORTS = "delete all reports";
    case DELETE_OWN_REPORTS = "delete own reports";
    case ACCESS_ROLES = "access roles";
    case CREATE_ROLES = "create roles";
    case EDIT_ROLES = "edit roles";
    case DELETE_ROLES = "delete roles";
    case ASSIGN_ROLES = "assign roles";
    case ACCESS_PERMISSIONS = "access permissions";
    case ASSIGN_PERMISSIONS = "assign permissions";
}
