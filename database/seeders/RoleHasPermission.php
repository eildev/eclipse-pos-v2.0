<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleHasPermissions = [
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 9, 'role_id' => 1],
            ['permission_id' => 10, 'role_id' => 1],
            ['permission_id' => 11, 'role_id' => 1],
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 13, 'role_id' => 1],
            ['permission_id' => 14, 'role_id' => 1],
            ['permission_id' => 15, 'role_id' => 1],
            ['permission_id' => 16, 'role_id' => 1],
            ['permission_id' => 17, 'role_id' => 1],
            ['permission_id' => 18, 'role_id' => 1],
            ['permission_id' => 19, 'role_id' => 1],
            ['permission_id' => 20, 'role_id' => 1],
            ['permission_id' => 21, 'role_id' => 1],
            ['permission_id' => 22, 'role_id' => 1],
            ['permission_id' => 23, 'role_id' => 1],
            ['permission_id' => 24, 'role_id' => 1],
            ['permission_id' => 25, 'role_id' => 1],
            ['permission_id' => 26, 'role_id' => 1],
            ['permission_id' => 27, 'role_id' => 1],
            ['permission_id' => 28, 'role_id' => 1],
            ['permission_id' => 29, 'role_id' => 1],
            ['permission_id' => 30, 'role_id' => 1],
            ['permission_id' => 31, 'role_id' => 1],
            ['permission_id' => 32, 'role_id' => 1],
            ['permission_id' => 33, 'role_id' => 1],
            ['permission_id' => 34, 'role_id' => 1],
            ['permission_id' => 35, 'role_id' => 1],
            ['permission_id' => 36, 'role_id' => 1],
            ['permission_id' => 37, 'role_id' => 1],
            ['permission_id' => 38, 'role_id' => 1],
            ['permission_id' => 39, 'role_id' => 1],
            ['permission_id' => 40, 'role_id' => 1],
            ['permission_id' => 41, 'role_id' => 1],
            ['permission_id' => 42, 'role_id' => 1],
            ['permission_id' => 43, 'role_id' => 1],
            ['permission_id' => 44, 'role_id' => 1],
            ['permission_id' => 45, 'role_id' => 1],
            ['permission_id' => 46, 'role_id' => 1],
            ['permission_id' => 47, 'role_id' => 1],
            ['permission_id' => 48, 'role_id' => 1],
            ['permission_id' => 49, 'role_id' => 1],
            ['permission_id' => 50, 'role_id' => 1],
            ['permission_id' => 51, 'role_id' => 1],
            ['permission_id' => 52, 'role_id' => 1],
            ['permission_id' => 53, 'role_id' => 1],
            ['permission_id' => 54, 'role_id' => 1],
            ['permission_id' => 55, 'role_id' => 1],
            ['permission_id' => 56, 'role_id' => 1],
            ['permission_id' => 57, 'role_id' => 1],
            ['permission_id' => 58, 'role_id' => 1],
            ['permission_id' => 59, 'role_id' => 1],
            ['permission_id' => 60, 'role_id' => 1],
            ['permission_id' => 61, 'role_id' => 1],
            ['permission_id' => 62, 'role_id' => 1],
            ['permission_id' => 63, 'role_id' => 1],
            ['permission_id' => 64, 'role_id' => 1],
            ['permission_id' => 65, 'role_id' => 1],
            ['permission_id' => 66, 'role_id' => 1],
            ['permission_id' => 67, 'role_id' => 1],
            ['permission_id' => 68, 'role_id' => 1],
            ['permission_id' => 69, 'role_id' => 1],
            ['permission_id' => 70, 'role_id' => 1],
            ['permission_id' => 71, 'role_id' => 1],
            ['permission_id' => 72, 'role_id' => 1],
            ['permission_id' => 73, 'role_id' => 1],
            ['permission_id' => 74, 'role_id' => 1],
            ['permission_id' => 75, 'role_id' => 1],
            ['permission_id' => 76, 'role_id' => 1],
            ['permission_id' => 77, 'role_id' => 1],
            ['permission_id' => 78, 'role_id' => 1],
            ['permission_id' => 79, 'role_id' => 1],
            ['permission_id' => 80, 'role_id' => 1],
            ['permission_id' => 81, 'role_id' => 1],
            ['permission_id' => 82, 'role_id' => 1],
            ['permission_id' => 83, 'role_id' => 1],
            ['permission_id' => 84, 'role_id' => 1],
            ['permission_id' => 85, 'role_id' => 1],
            ['permission_id' => 86, 'role_id' => 1],
            ['permission_id' => 87, 'role_id' => 1],
            ['permission_id' => 88, 'role_id' => 1],
            ['permission_id' => 89, 'role_id' => 1],
            ['permission_id' => 90, 'role_id' => 1],
            ['permission_id' => 91, 'role_id' => 1],
            ['permission_id' => 92, 'role_id' => 1],
            ['permission_id' => 93, 'role_id' => 1],
            ['permission_id' => 94, 'role_id' => 1],
            ['permission_id' => 95, 'role_id' => 1],
            ['permission_id' => 96, 'role_id' => 1],
            ['permission_id' => 97, 'role_id' => 1],
            ['permission_id' => 98, 'role_id' => 1],
            ['permission_id' => 99, 'role_id' => 1],
            ['permission_id' => 100, 'role_id' => 1],
            ['permission_id' => 101, 'role_id' => 1],
            ['permission_id' => 104, 'role_id' => 1],
            ['permission_id' => 105, 'role_id' => 1],
            ['permission_id' => 106, 'role_id' => 1],
            ['permission_id' => 107, 'role_id' => 1],
            ['permission_id' => 108, 'role_id' => 1],
            ['permission_id' => 109, 'role_id' => 1],
            ['permission_id' => 110, 'role_id' => 1],
            ['permission_id' => 111, 'role_id' => 1],
            ['permission_id' => 112, 'role_id' => 1],
            ['permission_id' => 113, 'role_id' => 1],
            ['permission_id' => 114, 'role_id' => 1],
            ['permission_id' => 115, 'role_id' => 1],
            ['permission_id' => 116, 'role_id' => 1],
            ['permission_id' => 117, 'role_id' => 1],
            ['permission_id' => 118, 'role_id' => 1],
            ['permission_id' => 119, 'role_id' => 1],
            ['permission_id' => 120, 'role_id' => 1],
            ['permission_id' => 121, 'role_id' => 1],
            ['permission_id' => 122, 'role_id' => 1],
            ['permission_id' => 123, 'role_id' => 1],
            ['permission_id' => 124, 'role_id' => 1],
            ['permission_id' => 125, 'role_id' => 1],
            ['permission_id' => 126, 'role_id' => 1],
            ['permission_id' => 128, 'role_id' => 1],
            ['permission_id' => 129, 'role_id' => 1],
            ['permission_id' => 130, 'role_id' => 1],
            ['permission_id' => 131, 'role_id' => 1],
            ['permission_id' => 132, 'role_id' => 1],
            ['permission_id' => 133, 'role_id' => 1],
            ['permission_id' => 134, 'role_id' => 1],
            ['permission_id' => 135, 'role_id' => 1],
            ['permission_id' => 137, 'role_id' => 1],
            ['permission_id' => 147, 'role_id' => 1],
            //Sales Report
            ['permission_id' => 158, 'role_id' => 1],
            ['permission_id' => 159, 'role_id' => 1],
            ['permission_id' => 160, 'role_id' => 1],
            ['permission_id' => 161, 'role_id' => 1],
            ['permission_id' => 162, 'role_id' => 1],
            ['permission_id' => 163, 'role_id' => 1],
            //Purchase Report
            ['permission_id' => 155, 'role_id' => 1],
            ['permission_id' => 156, 'role_id' => 1],
            ['permission_id' => 157, 'role_id' => 1],
            ['permission_id' => 164, 'role_id' => 1],
            ['permission_id' => 165, 'role_id' => 1],
            //pos setting
            ['permission_id' => 166, 'role_id' => 1],
            ['permission_id' => 167, 'role_id' => 1],
            ['permission_id' => 168, 'role_id' => 1],
            ['permission_id' => 169, 'role_id' => 1],
            ['permission_id' => 170, 'role_id' => 1],
            ['permission_id' => 171, 'role_id' => 1],
            ['permission_id' => 172, 'role_id' => 1],
            ['permission_id' => 173, 'role_id' => 1],
            ['permission_id' => 174, 'role_id' => 1],
            ['permission_id' => 175, 'role_id' => 1],
            ['permission_id' => 176, 'role_id' => 1],
            ['permission_id' => 177, 'role_id' => 1],
            ['permission_id' => 178, 'role_id' => 1],
            ['permission_id' => 179, 'role_id' => 1],
            ['permission_id' => 180, 'role_id' => 1],
            ['permission_id' => 181, 'role_id' => 1],
            ['permission_id' => 182, 'role_id' => 1],
            ['permission_id' => 183, 'role_id' => 1],
            ['permission_id' => 184, 'role_id' => 1],
            ['permission_id' => 185, 'role_id' => 1],
            ['permission_id' => 186, 'role_id' => 1],
            ['permission_id' => 187, 'role_id' => 1],
            ['permission_id' => 188, 'role_id' => 1],
            ['permission_id' => 189, 'role_id' => 1],
            ['permission_id' => 190, 'role_id' => 1],
            ['permission_id' => 191, 'role_id' => 1],
            ['permission_id' => 192, 'role_id' => 1],
            ['permission_id' => 193, 'role_id' => 1],
            ['permission_id' => 194, 'role_id' => 1],
            ['permission_id' => 195, 'role_id' => 1],
            ['permission_id' => 197, 'role_id' => 1],
            ['permission_id' => 198, 'role_id' => 1],
            ['permission_id' => 199, 'role_id' => 1],
            ['permission_id' => 200, 'role_id' => 1],
            ['permission_id' => 201, 'role_id' => 1],
            ['permission_id' => 202, 'role_id' => 1],
            ['permission_id' => 203, 'role_id' => 1],
            ['permission_id' => 204, 'role_id' => 1],
            ['permission_id' => 205, 'role_id' => 1],
            ['permission_id' => 206, 'role_id' => 1],
            ['permission_id' => 207, 'role_id' => 1],
            ['permission_id' => 208, 'role_id' => 1],
            ['permission_id' => 209, 'role_id' => 1],
            ['permission_id' => 210, 'role_id' => 1],
            ['permission_id' => 211, 'role_id' => 1],
                /////This one for sale  search ///
            ['permission_id' => 212, 'role_id' => 1],
            ['permission_id' => 213, 'role_id' => 1],
            ['permission_id' => 214, 'role_id' => 1],
            ['permission_id' => 215, 'role_id' => 1],
            ['permission_id' => 216, 'role_id' => 1],

            ['permission_id' => 1, 'role_id' => 2],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 3, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 6, 'role_id' => 2],
            ['permission_id' => 7, 'role_id' => 2],
            ['permission_id' => 8, 'role_id' => 2],
            ['permission_id' => 9, 'role_id' => 2],
            ['permission_id' => 10, 'role_id' => 2],
            ['permission_id' => 11, 'role_id' => 2],
            ['permission_id' => 12, 'role_id' => 2],
            ['permission_id' => 13, 'role_id' => 2],
            ['permission_id' => 14, 'role_id' => 2],
            ['permission_id' => 15, 'role_id' => 2],
            ['permission_id' => 16, 'role_id' => 2],
            ['permission_id' => 17, 'role_id' => 2],
            ['permission_id' => 18, 'role_id' => 2],
            ['permission_id' => 19, 'role_id' => 2],
            ['permission_id' => 20, 'role_id' => 2],
            ['permission_id' => 21, 'role_id' => 2],
            ['permission_id' => 22, 'role_id' => 2],
            ['permission_id' => 23, 'role_id' => 2],
            ['permission_id' => 24, 'role_id' => 2],
            ['permission_id' => 25, 'role_id' => 2],
            ['permission_id' => 26, 'role_id' => 2],
            ['permission_id' => 27, 'role_id' => 2],
            ['permission_id' => 28, 'role_id' => 2],
            ['permission_id' => 29, 'role_id' => 2],
            ['permission_id' => 30, 'role_id' => 2],
            ['permission_id' => 31, 'role_id' => 2],
            ['permission_id' => 32, 'role_id' => 2],
            ['permission_id' => 33, 'role_id' => 2],
            ['permission_id' => 34, 'role_id' => 2],
            ['permission_id' => 35, 'role_id' => 2],
            ['permission_id' => 36, 'role_id' => 2],
            ['permission_id' => 37, 'role_id' => 2],
            ['permission_id' => 38, 'role_id' => 2],
            ['permission_id' => 39, 'role_id' => 2],
            ['permission_id' => 40, 'role_id' => 2],
            ['permission_id' => 41, 'role_id' => 2],
            ['permission_id' => 42, 'role_id' => 2],
            ['permission_id' => 43, 'role_id' => 2],
            ['permission_id' => 44, 'role_id' => 2],
            ['permission_id' => 45, 'role_id' => 2],
            ['permission_id' => 46, 'role_id' => 2],
            ['permission_id' => 47, 'role_id' => 2],
            ['permission_id' => 48, 'role_id' => 2],
            ['permission_id' => 49, 'role_id' => 2],
            ['permission_id' => 50, 'role_id' => 2],
            ['permission_id' => 51, 'role_id' => 2],
            ['permission_id' => 52, 'role_id' => 2],
            ['permission_id' => 53, 'role_id' => 2],
            ['permission_id' => 54, 'role_id' => 2],
            ['permission_id' => 55, 'role_id' => 2],
            ['permission_id' => 56, 'role_id' => 2],
            ['permission_id' => 57, 'role_id' => 2],
            ['permission_id' => 58, 'role_id' => 2],
            ['permission_id' => 59, 'role_id' => 2],
            ['permission_id' => 60, 'role_id' => 2],
            ['permission_id' => 61, 'role_id' => 2],
            ['permission_id' => 62, 'role_id' => 2],
            ['permission_id' => 63, 'role_id' => 2],
            ['permission_id' => 64, 'role_id' => 2],
            ['permission_id' => 65, 'role_id' => 2],
            ['permission_id' => 66, 'role_id' => 2],
            ['permission_id' => 67, 'role_id' => 2],
            ['permission_id' => 68, 'role_id' => 2],
            ['permission_id' => 69, 'role_id' => 2],
            ['permission_id' => 70, 'role_id' => 2],
            ['permission_id' => 71, 'role_id' => 2],
            ['permission_id' => 72, 'role_id' => 2],
            ['permission_id' => 73, 'role_id' => 2],
            ['permission_id' => 74, 'role_id' => 2],
            ['permission_id' => 75, 'role_id' => 2],
            ['permission_id' => 76, 'role_id' => 2],
            ['permission_id' => 90, 'role_id' => 2],
            ['permission_id' => 91, 'role_id' => 2],
            ['permission_id' => 92, 'role_id' => 2],
            ['permission_id' => 93, 'role_id' => 2],
            ['permission_id' => 94, 'role_id' => 2],
            ['permission_id' => 95, 'role_id' => 2],
            ['permission_id' => 96, 'role_id' => 2],
            ['permission_id' => 97, 'role_id' => 2],
            ['permission_id' => 98, 'role_id' => 2],
            ['permission_id' => 99, 'role_id' => 2],
            ['permission_id' => 100, 'role_id' => 2],
            ['permission_id' => 101, 'role_id' => 2],
            ['permission_id' => 103, 'role_id' => 2],
            ['permission_id' => 104, 'role_id' => 2],
            ['permission_id' => 105, 'role_id' => 2],
            ['permission_id' => 106, 'role_id' => 2],
            ['permission_id' => 107, 'role_id' => 2],
            ['permission_id' => 108, 'role_id' => 2],
            ['permission_id' => 109, 'role_id' => 2],
            ['permission_id' => 110, 'role_id' => 2],
            ['permission_id' => 111, 'role_id' => 2],
            ['permission_id' => 112, 'role_id' => 2],
            ['permission_id' => 113, 'role_id' => 2],
            ['permission_id' => 114, 'role_id' => 2],
            ['permission_id' => 115, 'role_id' => 2],
            ['permission_id' => 116, 'role_id' => 2],
            ['permission_id' => 117, 'role_id' => 2],
            ['permission_id' => 118, 'role_id' => 2],
            ['permission_id' => 119, 'role_id' => 2],
            ['permission_id' => 120, 'role_id' => 2],
            ['permission_id' => 121, 'role_id' => 2],
            ['permission_id' => 122, 'role_id' => 2],
            ['permission_id' => 123, 'role_id' => 2],
            ['permission_id' => 124, 'role_id' => 2],
            ['permission_id' => 125, 'role_id' => 2],
            ['permission_id' => 126, 'role_id' => 2],
            ['permission_id' => 128, 'role_id' => 2],
            ['permission_id' => 129, 'role_id' => 2],
            ['permission_id' => 130, 'role_id' => 2],
            ['permission_id' => 131, 'role_id' => 2],
            ['permission_id' => 132, 'role_id' => 2],
            ['permission_id' => 133, 'role_id' => 2],
            ['permission_id' => 134, 'role_id' => 2],
            ['permission_id' => 135, 'role_id' => 2],
            ['permission_id' => 137, 'role_id' => 2],
            ['permission_id' => 147, 'role_id' => 2],
             //Sales Report
            ['permission_id' => 158, 'role_id' => 2],
            ['permission_id' => 159, 'role_id' => 2],
            ['permission_id' => 160, 'role_id' => 2],
            ['permission_id' => 161, 'role_id' => 2],
            ['permission_id' => 162, 'role_id' => 2],
            ['permission_id' => 163, 'role_id' => 2],
            //Purchase Report
            ['permission_id' => 155, 'role_id' => 2],
            ['permission_id' => 156, 'role_id' => 2],
            ['permission_id' => 157, 'role_id' => 2],
            ['permission_id' => 164, 'role_id' => 2],
            ['permission_id' => 165, 'role_id' => 2],
            //pos setting
            ['permission_id' => 166, 'role_id' => 2],
            ['permission_id' => 167, 'role_id' => 2],
            ['permission_id' => 168, 'role_id' => 2],
            ['permission_id' => 169, 'role_id' => 2],
            ['permission_id' => 170, 'role_id' => 2],
            ['permission_id' => 171, 'role_id' => 2],
            ['permission_id' => 172, 'role_id' => 2],
            ['permission_id' => 173, 'role_id' => 2],
            ['permission_id' => 174, 'role_id' => 2],
            ['permission_id' => 175, 'role_id' => 2],
            ['permission_id' => 176, 'role_id' => 2],
            ['permission_id' => 177, 'role_id' => 2],
            ['permission_id' => 178, 'role_id' => 2],
            ['permission_id' => 179, 'role_id' => 2],
            ['permission_id' => 180, 'role_id' => 2],
            ['permission_id' => 181, 'role_id' => 2],
            ['permission_id' => 182, 'role_id' => 2],
            ['permission_id' => 183, 'role_id' => 2],
            ['permission_id' => 184, 'role_id' => 2],
            ['permission_id' => 185, 'role_id' => 2],
            ['permission_id' => 186, 'role_id' => 2],
            ['permission_id' => 187, 'role_id' => 2],
            ['permission_id' => 188, 'role_id' => 2],
            ['permission_id' => 189, 'role_id' => 2],
            ['permission_id' => 190, 'role_id' => 2],
            ['permission_id' => 191, 'role_id' => 2],
            ['permission_id' => 192, 'role_id' => 2],
            ['permission_id' => 193, 'role_id' => 2],
            ['permission_id' => 194, 'role_id' => 2],
            ['permission_id' => 195, 'role_id' => 2],
            ['permission_id' => 197, 'role_id' => 2],
            ['permission_id' => 198, 'role_id' => 2],
            ['permission_id' => 199, 'role_id' => 2],
            ['permission_id' => 200, 'role_id' => 2],
            ['permission_id' => 201, 'role_id' => 2],
            ['permission_id' => 202, 'role_id' => 2],
            ['permission_id' => 203, 'role_id' => 2],
            ['permission_id' => 204, 'role_id' => 2],
            ['permission_id' => 205, 'role_id' => 2],
            ['permission_id' => 206, 'role_id' => 2],
            ['permission_id' => 207, 'role_id' => 2],
            ['permission_id' => 208, 'role_id' => 2],
            ['permission_id' => 209, 'role_id' => 2],
            ['permission_id' => 210, 'role_id' => 2],
            ['permission_id' => 211, 'role_id' => 2],
            /////==Sale search==/////
            ['permission_id' => 212, 'role_id' => 2],
            ['permission_id' => 213, 'role_id' => 2],
            ['permission_id' => 214, 'role_id' => 2],
            ['permission_id' => 215, 'role_id' => 2],
            ['permission_id' => 216, 'role_id' => 2],

            ['permission_id' => 1, 'role_id' => 3],
            ['permission_id' => 2, 'role_id' => 3],
            ['permission_id' => 3, 'role_id' => 3],
            ['permission_id' => 4, 'role_id' => 3],
            ['permission_id' => 5, 'role_id' => 3],
            ['permission_id' => 6, 'role_id' => 3],
            ['permission_id' => 7, 'role_id' => 3],
            ['permission_id' => 8, 'role_id' => 3],
            ['permission_id' => 9, 'role_id' => 3],
            ['permission_id' => 10, 'role_id' => 3],
            ['permission_id' => 11, 'role_id' => 3],
            ['permission_id' => 12, 'role_id' => 3],
            ['permission_id' => 13, 'role_id' => 3],
            ['permission_id' => 14, 'role_id' => 3],
            ['permission_id' => 15, 'role_id' => 3],
            ['permission_id' => 16, 'role_id' => 3],
            ['permission_id' => 17, 'role_id' => 3],
            ['permission_id' => 18, 'role_id' => 3],
            ['permission_id' => 19, 'role_id' => 3],
            ['permission_id' => 20, 'role_id' => 3],
            ['permission_id' => 21, 'role_id' => 3],
            ['permission_id' => 22, 'role_id' => 3],
            ['permission_id' => 23, 'role_id' => 3],
            ['permission_id' => 24, 'role_id' => 3],
            ['permission_id' => 25, 'role_id' => 3],
            ['permission_id' => 26, 'role_id' => 3],
            ['permission_id' => 27, 'role_id' => 3],
            ['permission_id' => 28, 'role_id' => 3],
            ['permission_id' => 29, 'role_id' => 3],
            ['permission_id' => 30, 'role_id' => 3],
            ['permission_id' => 31, 'role_id' => 3],
            ['permission_id' => 32, 'role_id' => 3],
            ['permission_id' => 33, 'role_id' => 3],
            ['permission_id' => 34, 'role_id' => 3],
            ['permission_id' => 35, 'role_id' => 3],
            ['permission_id' => 36, 'role_id' => 3],
            ['permission_id' => 37, 'role_id' => 3],
            ['permission_id' => 38, 'role_id' => 3],
            ['permission_id' => 39, 'role_id' => 3],
            ['permission_id' => 40, 'role_id' => 3],
            ['permission_id' => 41, 'role_id' => 3],
            ['permission_id' => 42, 'role_id' => 3],
            ['permission_id' => 43, 'role_id' => 3],
            ['permission_id' => 44, 'role_id' => 3],
            ['permission_id' => 45, 'role_id' => 3],
            ['permission_id' => 46, 'role_id' => 3],
            ['permission_id' => 47, 'role_id' => 3],
            ['permission_id' => 48, 'role_id' => 3],
            ['permission_id' => 49, 'role_id' => 3],
            ['permission_id' => 50, 'role_id' => 3],
            ['permission_id' => 51, 'role_id' => 3],
            ['permission_id' => 52, 'role_id' => 3],
            ['permission_id' => 53, 'role_id' => 3],
            ['permission_id' => 54, 'role_id' => 3],
            ['permission_id' => 55, 'role_id' => 3],
            ['permission_id' => 56, 'role_id' => 3],
            ['permission_id' => 57, 'role_id' => 3],
            ['permission_id' => 58, 'role_id' => 3],
            ['permission_id' => 59, 'role_id' => 3],
            ['permission_id' => 60, 'role_id' => 3],
            ['permission_id' => 61, 'role_id' => 3],
            ['permission_id' => 62, 'role_id' => 3],
            ['permission_id' => 63, 'role_id' => 3],
            ['permission_id' => 64, 'role_id' => 3],
            ['permission_id' => 65, 'role_id' => 3],
            ['permission_id' => 66, 'role_id' => 3],
            ['permission_id' => 67, 'role_id' => 3],
            ['permission_id' => 68, 'role_id' => 3],
            ['permission_id' => 69, 'role_id' => 3],
            ['permission_id' => 70, 'role_id' => 3],
            ['permission_id' => 71, 'role_id' => 3],
            ['permission_id' => 72, 'role_id' => 3],
            ['permission_id' => 73, 'role_id' => 3],
            ['permission_id' => 74, 'role_id' => 3],
            ['permission_id' => 75, 'role_id' => 3],
            ['permission_id' => 76, 'role_id' => 3],
            ['permission_id' => 90, 'role_id' => 3],
            ['permission_id' => 95, 'role_id' => 3],
            ['permission_id' => 96, 'role_id' => 3],
            ['permission_id' => 97, 'role_id' => 3],
            ['permission_id' => 98, 'role_id' => 3],
            ['permission_id' => 99, 'role_id' => 3],

            ['permission_id' => 166, 'role_id' => 3],
            ['permission_id' => 167, 'role_id' => 3],
            ['permission_id' => 168, 'role_id' => 3],
            ['permission_id' => 169, 'role_id' => 3],
            ['permission_id' => 170, 'role_id' => 3],
            ['permission_id' => 171, 'role_id' => 3],
            ['permission_id' => 172, 'role_id' => 3],
            ['permission_id' => 173, 'role_id' => 3],
            ['permission_id' => 174, 'role_id' => 3],
            ['permission_id' => 175, 'role_id' => 3],
            ['permission_id' => 176, 'role_id' => 3],
            ['permission_id' => 177, 'role_id' => 3],
            ['permission_id' => 178, 'role_id' => 3],
            ['permission_id' => 179, 'role_id' => 3],
            ['permission_id' => 180, 'role_id' => 3],
            ['permission_id' => 181, 'role_id' => 3],
            ['permission_id' => 182, 'role_id' => 3],
            ['permission_id' => 183, 'role_id' => 3],
            ['permission_id' => 184, 'role_id' => 3],
            ['permission_id' => 185, 'role_id' => 3],
            ['permission_id' => 186, 'role_id' => 3],
             /////==Sale search==/////
            ['permission_id' => 212, 'role_id' => 3],
            ['permission_id' => 213, 'role_id' => 3],
            ['permission_id' => 214, 'role_id' => 3],
            ['permission_id' => 215, 'role_id' => 3],
            ['permission_id' => 216, 'role_id' => 3],
            //Role 4
            ['permission_id' => 1, 'role_id' => 4],
            ['permission_id' => 2, 'role_id' => 4],
            ['permission_id' => 3, 'role_id' => 4],
            ['permission_id' => 4, 'role_id' => 4],
            ['permission_id' => 5, 'role_id' => 4],
            ['permission_id' => 6, 'role_id' => 4],
            ['permission_id' => 7, 'role_id' => 4],
            ['permission_id' => 8, 'role_id' => 4],
            ['permission_id' => 9, 'role_id' => 4],
            ['permission_id' => 10, 'role_id' => 4],
            ['permission_id' => 11, 'role_id' => 4],
            ['permission_id' => 12, 'role_id' => 4],
            ['permission_id' => 13, 'role_id' => 4],
            ['permission_id' => 14, 'role_id' => 4],
            ['permission_id' => 15, 'role_id' => 4],
            ['permission_id' => 16, 'role_id' => 4],
            ['permission_id' => 17, 'role_id' => 4],
            ['permission_id' => 18, 'role_id' => 4],
            ['permission_id' => 19, 'role_id' => 4],
            ['permission_id' => 20, 'role_id' => 4],
            ['permission_id' => 21, 'role_id' => 4],
            ['permission_id' => 22, 'role_id' => 4],
            ['permission_id' => 23, 'role_id' => 4],
            ['permission_id' => 24, 'role_id' => 4],
            ['permission_id' => 25, 'role_id' => 4],
            ['permission_id' => 26, 'role_id' => 4],
            ['permission_id' => 27, 'role_id' => 4],
            ['permission_id' => 28, 'role_id' => 4],
            ['permission_id' => 29, 'role_id' => 4],
            ['permission_id' => 30, 'role_id' => 4],
            ['permission_id' => 31, 'role_id' => 4],
            ['permission_id' => 32, 'role_id' => 4],
            ['permission_id' => 33, 'role_id' => 4],
            ['permission_id' => 34, 'role_id' => 4],
            ['permission_id' => 35, 'role_id' => 4],
            ['permission_id' => 36, 'role_id' => 4],
            ['permission_id' => 37, 'role_id' => 4],
            ['permission_id' => 38, 'role_id' => 4],
            ['permission_id' => 39, 'role_id' => 4],
            ['permission_id' => 40, 'role_id' => 4],
            ['permission_id' => 41, 'role_id' => 4],
            ['permission_id' => 42, 'role_id' => 4],
            ['permission_id' => 43, 'role_id' => 4],
            ['permission_id' => 44, 'role_id' => 4],
            ['permission_id' => 45, 'role_id' => 4],
            ['permission_id' => 46, 'role_id' => 4],
            ['permission_id' => 47, 'role_id' => 4],
            ['permission_id' => 48, 'role_id' => 4],
            ['permission_id' => 49, 'role_id' => 4],
            ['permission_id' => 50, 'role_id' => 4],
            ['permission_id' => 51, 'role_id' => 4],
            ['permission_id' => 52, 'role_id' => 4],
            ['permission_id' => 53, 'role_id' => 4],
            ['permission_id' => 54, 'role_id' => 4],
            ['permission_id' => 55, 'role_id' => 4],
            ['permission_id' => 56, 'role_id' => 4],
            ['permission_id' => 57, 'role_id' => 4],
            ['permission_id' => 58, 'role_id' => 4],
            ['permission_id' => 59, 'role_id' => 4],
            ['permission_id' => 60, 'role_id' => 4],
            ['permission_id' => 61, 'role_id' => 4],
            ['permission_id' => 62, 'role_id' => 4],
            ['permission_id' => 63, 'role_id' => 4],
            ['permission_id' => 64, 'role_id' => 4],
            ['permission_id' => 65, 'role_id' => 4],
            ['permission_id' => 66, 'role_id' => 4],
            ['permission_id' => 67, 'role_id' => 4],
            ['permission_id' => 68, 'role_id' => 4],
            ['permission_id' => 69, 'role_id' => 4],
            ['permission_id' => 70, 'role_id' => 4],
            ['permission_id' => 71, 'role_id' => 4],
            ['permission_id' => 72, 'role_id' => 4],
            ['permission_id' => 73, 'role_id' => 4],
            ['permission_id' => 74, 'role_id' => 4],
            ['permission_id' => 75, 'role_id' => 4],
            ['permission_id' => 76, 'role_id' => 4],
            ['permission_id' => 77, 'role_id' => 4],
            ['permission_id' => 78, 'role_id' => 4],
            ['permission_id' => 79, 'role_id' => 4],
            ['permission_id' => 80, 'role_id' => 4],
            ['permission_id' => 81, 'role_id' => 4],
            ['permission_id' => 82, 'role_id' => 4],
            ['permission_id' => 83, 'role_id' => 4],
            ['permission_id' => 84, 'role_id' => 4],
            ['permission_id' => 85, 'role_id' => 4],
            ['permission_id' => 86, 'role_id' => 4],
            ['permission_id' => 87, 'role_id' => 4],
            ['permission_id' => 88, 'role_id' => 4],
            ['permission_id' => 89, 'role_id' => 4],
            ['permission_id' => 90, 'role_id' => 4],
            ['permission_id' => 91, 'role_id' => 4],
            ['permission_id' => 92, 'role_id' => 4],
            ['permission_id' => 93, 'role_id' => 4],
            ['permission_id' => 94, 'role_id' => 4],
            ['permission_id' => 95, 'role_id' => 4],
            ['permission_id' => 96, 'role_id' => 4],
            ['permission_id' => 97, 'role_id' => 4],
            ['permission_id' => 98, 'role_id' => 4],
            ['permission_id' => 99, 'role_id' => 4],
            ['permission_id' => 100, 'role_id' => 4],
            ['permission_id' => 101, 'role_id' => 4],
            ['permission_id' => 102, 'role_id' => 4],
            ['permission_id' => 103, 'role_id' => 4],
            ['permission_id' => 104, 'role_id' => 4],
            ['permission_id' => 105, 'role_id' => 4],
            ['permission_id' => 106, 'role_id' => 4],
            ['permission_id' => 107, 'role_id' => 4],
            ['permission_id' => 108, 'role_id' => 4],
            ['permission_id' => 109, 'role_id' => 4],
            ['permission_id' => 110, 'role_id' => 4],
            ['permission_id' => 111, 'role_id' => 4],
            ['permission_id' => 112, 'role_id' => 4],
            ['permission_id' => 113, 'role_id' => 4],
            ['permission_id' => 114, 'role_id' => 4],
            ['permission_id' => 115, 'role_id' => 4],
            ['permission_id' => 116, 'role_id' => 4],
            ['permission_id' => 117, 'role_id' => 4],
            ['permission_id' => 118, 'role_id' => 4],
            ['permission_id' => 119, 'role_id' => 4],
            ['permission_id' => 120, 'role_id' => 4],
            ['permission_id' => 121, 'role_id' => 4],
            ['permission_id' => 122, 'role_id' => 4],
            ['permission_id' => 123, 'role_id' => 4],
            ['permission_id' => 124, 'role_id' => 4],
            ['permission_id' => 125, 'role_id' => 4],
            ['permission_id' => 126, 'role_id' => 4],
            ['permission_id' => 128, 'role_id' => 4],
            ['permission_id' => 129, 'role_id' => 4],
            ['permission_id' => 130, 'role_id' => 4],
            ['permission_id' => 131, 'role_id' => 4],
            ['permission_id' => 132, 'role_id' => 4],
            ['permission_id' => 133, 'role_id' => 4],
            ['permission_id' => 134, 'role_id' => 4],
            ['permission_id' => 135, 'role_id' => 4],
              //Sales Report
            ['permission_id' => 158, 'role_id' => 4],
            ['permission_id' => 159, 'role_id' => 4],
            ['permission_id' => 160, 'role_id' => 4],
            ['permission_id' => 161, 'role_id' => 4],
            ['permission_id' => 162, 'role_id' => 4],
            ['permission_id' => 163, 'role_id' => 4],
        ];

        DB::table('role_has_permissions')->upsert(
            $roleHasPermissions,
            ['permission_id', 'role_id'],
            ['permission_id', 'role_id']
        );
    }
}
