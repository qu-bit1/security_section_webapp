<?php

namespace App\Enums;

enum PermissionsEnum: string {
    case ADMIN_ACCESS = "admin access";
    case ACCESS_USERS = "access users";
    case CREATE_USERS = "create users";
    case EDIT_USERS = "edit users";
    case DELETE_USERS = "delete users";
    case ACCESS_ALL_REPORTS = "access all reports";
    case ACCESS_OWN_REPORTS = "access own reports";
    case APPROVE_REPORTS = "approve reports";
    case CREATE_REPORTS = "create reports";
    case EDIT_ALL_REPORTS = "edit all reports";
    case EDIT_OWN_REPORTS = "edit own reports";
    case DELETE_ALL_REPORTS = "delete all reports";
    case DELETE_OWN_REPORTS = "delete own reports";
    case ACCESS_ALL_REMARKS = "access all remarks";
    case ACCESS_OWN_REMARKS = "access own remarks";
    case CREATE_REMARKS = "create remarks";
    case EDIT_ALL_REMARKS = "edit all remarks";
    case EDIT_OWN_REMARKS = "edit own remarks";
    case DELETE_ALL_REMARKS = "delete all remarks";
    case DELETE_OWN_REMARKS = "delete own remarks";
    case ACCESS_ROLES = "access roles";
    case CREATE_ROLES = "create roles";
    case EDIT_ROLES = "edit roles";
    case DELETE_ROLES = "delete roles";
    case ASSIGN_ROLES = "assign roles";
    case ACCESS_PERMISSIONS = "access permissions";
    case ASSIGN_PERMISSIONS = "assign permissions";
}
