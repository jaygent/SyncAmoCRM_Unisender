<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager;

/**
 *  Создание таблицы пользователей
 */

class AddUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Manager::schema()->create('Users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clientId');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->string('baseDomain');
            $table->string('unisender_api_key')->nullable();
            $table->integer('expires');
            $table->timestamps();
        });


    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Manager::schema()->drop('Users');
    }
}
